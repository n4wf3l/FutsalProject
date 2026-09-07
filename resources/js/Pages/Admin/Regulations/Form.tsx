import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, ExternalLink, FileText, Loader2, Save, Trash2, Upload } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Regulation } from '@/types/models';

interface Props {
    regulation: Regulation | null;
}

function filename(path: string): string {
    if (!path) return '';
    const parts = path.split('/');
    return parts[parts.length - 1] ?? path;
}

export default function RegulationForm({ regulation }: Props) {
    const isEdit = !!regulation;
    const [pickedName, setPickedName] = useState<string | null>(null);

    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        title: regulation?.title ?? '',
        pdf: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/regulations/${regulation!.id}` : '/regulations';
        post(url, { forceFormData: true });
    };

    const onPdfChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('pdf', file);
        setPickedName(file?.name ?? null);
    };

    const clearPdf = () => {
        setData('pdf', null);
        setPickedName(null);
    };

    const currentName = regulation?.pdf_path ? filename(regulation.pdf_path) : null;

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier règlementation' : 'Nouvelle règlementation'} />

            <div className="mb-6">
                <Link
                    href="/regulations"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux règlementations
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouvelle'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit ? regulation!.title : 'Créer une règlementation'}
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
                            Document PDF
                        </div>
                        <div className="mt-4 flex aspect-[3/4] flex-col items-center justify-center gap-3 rounded-xl border border-border bg-muted p-4 text-center">
                            <FileText className="h-12 w-12 text-muted-foreground/40" strokeWidth={1} />
                            {pickedName ? (
                                <div>
                                    <div className="font-mono text-[11px] text-crimson">
                                        {pickedName}
                                    </div>
                                    <div className="mt-1 text-[10px] text-muted-foreground">
                                        Nouveau fichier sélectionné
                                    </div>
                                </div>
                            ) : currentName ? (
                                <div>
                                    <div className="font-mono text-[11px] text-foreground">
                                        {currentName}
                                    </div>
                                    <a
                                        href={`/storage/${regulation!.pdf_path}`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="mt-1 inline-flex items-center gap-1 text-[10px] text-champagne hover:text-crimson"
                                    >
                                        Ouvrir
                                        <ExternalLink className="h-3 w-3" />
                                    </a>
                                </div>
                            ) : (
                                <div className="text-xs text-muted-foreground">Aucun fichier</div>
                            )}
                        </div>
                        <div className="mt-4 space-y-2">
                            <label className="flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm font-medium transition-colors hover:border-crimson/50 hover:text-crimson">
                                <Upload className="h-4 w-4" />
                                {currentName || pickedName ? 'Changer' : 'Téléverser'}
                                <input
                                    type="file"
                                    accept="application/pdf"
                                    onChange={onPdfChange}
                                    className="hidden"
                                />
                            </label>
                            {pickedName && (
                                <button
                                    type="button"
                                    onClick={clearPdf}
                                    className="flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-plasma hover:bg-plasma/10"
                                >
                                    <Trash2 className="h-4 w-4" />
                                    Annuler la sélection
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
                            {errors.pdf && <p className="text-xs text-plasma">{errors.pdf}</p>}
                            <p className="text-[10px] text-muted-foreground">
                                PDF uniquement, 8 Mo max.
                                {isEdit ? ' Laisser vide pour conserver le fichier actuel.' : ''}
                            </p>
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
                            <Field label="Titre" required error={errors.title}>
                                <Input
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    required
                                    placeholder="Règlement intérieur 2025/2026"
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button">
                            <Link href="/regulations">Annuler</Link>
                        </Button>
                        <Button type="submit" size="lg" disabled={processing}>
                            {processing ? (
                                <Loader2 className="h-4 w-4 animate-spin" />
                            ) : (
                                <Save className="h-4 w-4" />
                            )}
                            {isEdit ? 'Enregistrer' : 'Créer la règlementation'}
                        </Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}
