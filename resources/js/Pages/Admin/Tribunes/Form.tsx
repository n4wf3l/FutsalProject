import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Loader2, Save, Ticket, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Tribune } from '@/types/models';

interface Props {
    tribune: Tribune | null;
}

const CURRENCIES = ['DH', 'MAD', 'EUR', 'USD'];

export default function TribuneForm({ tribune }: Props) {
    const isEdit = !!tribune;
    const [preview, setPreview] = useState<string | null>(
        tribune?.photo ? `/storage/${tribune.photo}` : null
    );

    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        name: tribune?.name ?? '',
        description: tribune?.description ?? '',
        price: tribune?.price ?? 0,
        currency: tribune?.currency ?? 'DH',
        available_seats: tribune?.available_seats ?? 0,
        photo: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/tribunes/${tribune!.id}` : '/tribunes';
        post(url, { forceFormData: true });
    };

    const onPhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('photo', file);
        if (file) {
            const reader = new FileReader();
            reader.onload = () => setPreview(reader.result as string);
            reader.readAsDataURL(file);
        }
    };

    const removePhoto = () => {
        setData('photo', null);
        setPreview(null);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier tribune' : 'Nouvelle tribune'} />

            <div className="mb-6">
                <Link
                    href="/tribunes"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux tribunes
                </Link>
                <div className="mt-3 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                            {isEdit ? 'Édition' : 'Nouveau'}
                        </div>
                        <h1 className="mt-1 font-display text-3xl font-bold">
                            {isEdit ? tribune!.name : 'Ajouter une tribune'}
                        </h1>
                    </div>
                </div>
            </div>

            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[300px_1fr]">
                {/* Photo panel */}
                <motion.aside
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="space-y-4"
                >
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <div className="font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Photo de la tribune
                        </div>
                        <div className="mt-4 aspect-video overflow-hidden rounded-xl border border-border bg-muted">
                            {preview ? (
                                <img src={preview} alt="Aperçu" className="h-full w-full object-cover" />
                            ) : (
                                <div className="flex h-full w-full flex-col items-center justify-center gap-2 text-muted-foreground/60">
                                    <Ticket className="h-12 w-12" strokeWidth={1} />
                                    <span className="text-xs">Aucune photo</span>
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
                                    onChange={onPhotoChange}
                                    className="hidden"
                                />
                            </label>
                            {preview && (
                                <button
                                    type="button"
                                    onClick={removePhoto}
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
                            {errors.photo && <p className="text-xs text-plasma">{errors.photo}</p>}
                        </div>
                    </div>
                </motion.aside>

                {/* Fields panel */}
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.05 }}
                    className="space-y-6"
                >
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Informations" />
                        <div className="mt-4 space-y-5">
                            <Field label="Nom" required error={errors.name}>
                                <Input
                                    value={data.name}
                                    onChange={(e) => setData('name', e.target.value)}
                                    required
                                    placeholder="Tribune Nord"
                                />
                            </Field>
                            <Field
                                label="Description"
                                error={errors.description}
                                hint="Courte présentation affichée dans la billetterie."
                            >
                                <Textarea
                                    value={data.description ?? ''}
                                    onChange={(e) => setData('description', e.target.value)}
                                    rows={5}
                                    placeholder="Emplacement, ambiance, avantages…"
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Tarification" />
                        <div className="mt-4 grid gap-5 sm:grid-cols-3">
                            <Field label="Prix" required error={errors.price}>
                                <Input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    value={data.price}
                                    onChange={(e) => setData('price', parseFloat(e.target.value) || 0)}
                                    required
                                />
                            </Field>
                            <Field label="Devise" required error={errors.currency}>
                                <select
                                    value={data.currency}
                                    onChange={(e) => setData('currency', e.target.value)}
                                    required
                                    className="flex h-10 w-full rounded-lg border border-input bg-card px-4 py-2 text-sm text-foreground shadow-sm focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20"
                                >
                                    {CURRENCIES.map((c) => (
                                        <option key={c} value={c}>
                                            {c}
                                        </option>
                                    ))}
                                </select>
                            </Field>
                            <Field label="Places dispo" required error={errors.available_seats}>
                                <Input
                                    type="number"
                                    min="0"
                                    value={data.available_seats}
                                    onChange={(e) => setData('available_seats', parseInt(e.target.value) || 0)}
                                    required
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button">
                            <Link href="/tribunes">Annuler</Link>
                        </Button>
                        <Button type="submit" size="lg" disabled={processing}>
                            {processing ? (
                                <Loader2 className="h-4 w-4 animate-spin" />
                            ) : (
                                <Save className="h-4 w-4" />
                            )}
                            {isEdit ? 'Enregistrer' : 'Créer la tribune'}
                        </Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}

function SectionTitle({ title }: { title: string }) {
    return (
        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
            {title}
        </div>
    );
}
