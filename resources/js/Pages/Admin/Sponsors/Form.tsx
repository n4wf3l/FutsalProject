import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Building2, Camera, Loader2, Save, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Sponsor } from '@/types/models';

interface Props {
    sponsor: Sponsor | null;
}

export default function SponsorForm({ sponsor }: Props) {
    const isEdit = !!sponsor;
    const [preview, setPreview] = useState<string | null>(
        sponsor?.logo ? `/storage/${sponsor.logo}` : null
    );

    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        name: sponsor?.name ?? '',
        website: sponsor?.website ?? '',
        logo: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/sponsors/${sponsor!.id}` : '/sponsors';
        post(url, { forceFormData: true });
    };

    const onLogoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('logo', file);
        if (file) {
            const reader = new FileReader();
            reader.onload = () => setPreview(reader.result as string);
            reader.readAsDataURL(file);
        }
    };

    const removeLogo = () => {
        setData('logo', null);
        setPreview(null);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier sponsor' : 'Nouveau sponsor'} />

            <div className="mb-6">
                <Link
                    href="/sponsors"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux sponsors
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouveau'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit ? sponsor!.name : 'Ajouter un sponsor'}
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
                            Logo
                        </div>
                        <div className="mt-4 flex aspect-square items-center justify-center overflow-hidden rounded-xl border border-border bg-white p-6">
                            {preview ? (
                                <img
                                    src={preview}
                                    alt="Aperçu"
                                    className="h-full w-full object-contain"
                                />
                            ) : (
                                <div className="flex flex-col items-center justify-center gap-2 text-muted-foreground/60">
                                    <Building2 className="h-16 w-16" strokeWidth={1} />
                                    <span className="text-xs">Aucun logo</span>
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
                                    onChange={onLogoChange}
                                    className="hidden"
                                />
                            </label>
                            {preview && (
                                <button
                                    type="button"
                                    onClick={removeLogo}
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
                            {errors.logo && (
                                <p className="text-xs text-plasma">{errors.logo}</p>
                            )}
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
                            Informations
                        </div>
                        <div className="mt-4 space-y-5">
                            <Field label="Nom" required error={errors.name}>
                                <Input
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    required
                                    placeholder="Nom du sponsor"
                                />
                            </Field>
                            <Field
                                label="Site web"
                                error={errors.website}
                                hint="URL complète (avec https://) ou domaine simple."
                            >
                                <Input
                                    type="url"
                                    value={data.website}
                                    onChange={(e) => setData('website', e.target.value)}
                                    placeholder="https://exemple.com"
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button">
                            <Link href="/sponsors">Annuler</Link>
                        </Button>
                        <Button type="submit" size="lg" disabled={processing}>
                            {processing ? (
                                <Loader2 className="h-4 w-4 animate-spin" />
                            ) : (
                                <Save className="h-4 w-4" />
                            )}
                            {isEdit ? 'Enregistrer' : 'Créer le sponsor'}
                        </Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}
