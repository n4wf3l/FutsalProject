import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Loader2, Newspaper, Save, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Article } from '@/types/models';

interface Props {
    article: Article | null;
}

export default function ArticleForm({ article }: Props) {
    const isEdit = !!article;
    const [preview, setPreview] = useState<string | null>(
        article?.image ? `/storage/${article.image}` : null
    );

    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        title: article?.title ?? '',
        description: article?.description ?? '',
        image: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/articles/${article!.id}` : '/articles';
        post(url, { forceFormData: true });
    };

    const onImageChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('image', file);
        if (file) {
            const reader = new FileReader();
            reader.onload = () => setPreview(reader.result as string);
            reader.readAsDataURL(file);
        }
    };

    const removeImage = () => {
        setData('image', null);
        setPreview(null);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier article' : 'Nouvel article'} />

            <div className="mb-6">
                <Link
                    href="/articles"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux articles
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouveau'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit ? article!.title : 'Créer un article'}
                    </h1>
                </div>
            </div>

            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[300px_1fr]">
                <motion.aside
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="space-y-4"
                >
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <div className="font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Image de l'article
                        </div>
                        <div className="mt-4 aspect-video overflow-hidden rounded-xl border border-border bg-muted">
                            {preview ? (
                                <img src={preview} alt="Aperçu" className="h-full w-full object-cover" />
                            ) : (
                                <div className="flex h-full w-full flex-col items-center justify-center gap-2 text-muted-foreground/60">
                                    <Newspaper className="h-12 w-12" strokeWidth={1} />
                                    <span className="text-xs">Aucune image</span>
                                </div>
                            )}
                        </div>
                        <div className="mt-4 space-y-2">
                            <label className="flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm font-medium transition-colors hover:border-crimson/50 hover:text-crimson">
                                <Camera className="h-4 w-4" />
                                {preview ? 'Changer' : 'Téléverser'}
                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={onImageChange}
                                    className="hidden"
                                />
                            </label>
                            {preview && (
                                <button
                                    type="button"
                                    onClick={removeImage}
                                    className="flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-plasma hover:bg-plasma/10"
                                >
                                    <Trash2 className="h-4 w-4" />
                                    Retirer
                                </button>
                            )}
                            {progress && (
                                <div className="h-1 overflow-hidden rounded-full bg-muted">
                                    <div
                                        className="h-full bg-crimson transition-all"
                                        style={{ width: `${progress.percentage}%` }}
                                    />
                                </div>
                            )}
                            {errors.image && <p className="text-xs text-plasma">{errors.image}</p>}
                        </div>
                    </div>
                </motion.aside>

                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.05 }}
                    className="space-y-6"
                >
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
                            Contenu
                        </div>
                        <div className="mt-4 space-y-5">
                            <Field label="Titre" required error={errors.title}>
                                <Input
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    required
                                    placeholder="Dina Kenitra remporte le derby..."
                                />
                            </Field>
                            <Field
                                label="Contenu"
                                required
                                error={errors.description}
                                hint="HTML autorisé pour la mise en forme (paragraphes, liens, images embed)."
                            >
                                <Textarea
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    required
                                    rows={18}
                                    placeholder="<p>Contenu de l'article...</p>"
                                    className="font-mono text-sm"
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button">
                            <Link href="/articles">Annuler</Link>
                        </Button>
                        <Button type="submit" size="lg" disabled={processing}>
                            {processing ? (
                                <Loader2 className="h-4 w-4 animate-spin" />
                            ) : (
                                <Save className="h-4 w-4" />
                            )}
                            {isEdit ? 'Enregistrer' : "Créer l'article"}
                        </Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}
