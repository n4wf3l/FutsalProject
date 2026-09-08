import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Loader2, Save } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { RichTextEditor } from '@/Components/ui/RichTextEditor';
import { Field } from '@/Components/ui/Field';
import type { AboutSection } from '@/types/models';

interface Props {
    section: AboutSection | null;
}

export default function AboutSectionForm({ section }: Props) {
    const isEdit = !!section;

    const { data, setData, post, processing, errors } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        title: section?.title ?? '',
        content: section?.content ?? '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/about-sections/${section!.id}` : '/about-sections';
        post(url);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier section' : 'Nouvelle section'} />

            <div className="mb-6">
                <Link
                    href="/about"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux sections
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouvelle'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit ? section!.title : 'Créer une section'}
                    </h1>
                </div>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="rounded-2xl border border-border bg-card p-6 sm:p-8"
                >
                    <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
                        Contenu
                    </div>
                    <div className="mt-4 space-y-5">
                        <Field label="Titre" required error={errors.title}>
                            <Input
                                value={data.title}
                                onChange={(e) => setData('title', e.target.value)}
                                required
                                placeholder="Notre histoire, Nos valeurs, ..."
                            />
                        </Field>
                        <Field
                            label="Contenu"
                            required
                            error={errors.content}
                            hint="Utilise la barre d'outils pour mettre en forme."
                        >
                            <RichTextEditor
                                value={data.content}
                                onChange={(html) => setData('content', html)}
                                placeholder="Rédige le contenu de la section…"
                                minHeight={400}
                            />
                        </Field>
                    </div>
                </motion.div>

                <div className="flex items-center justify-end gap-3">
                    <Button asChild variant="outline" type="button">
                        <Link href="/about">Annuler</Link>
                    </Button>
                    <Button type="submit" size="lg" disabled={processing}>
                        {processing ? (
                            <Loader2 className="h-4 w-4 animate-spin" />
                        ) : (
                            <Save className="h-4 w-4" />
                        )}
                        {isEdit ? 'Enregistrer' : 'Créer la section'}
                    </Button>
                </div>
            </form>
        </AdminLayout>
    );
}
