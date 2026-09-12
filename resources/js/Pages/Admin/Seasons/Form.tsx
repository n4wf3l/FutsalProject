import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Loader2, Save } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Season } from '@/types/models';

interface Props {
    season: Season | null;
}

export default function SeasonForm({ season }: Props) {
    const isEdit = !!season;

    const { data, setData, post, processing, errors } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        season_label: season?.season_label ?? '',
        season_start_year: season?.season_start_year ?? new Date().getFullYear(),
        division: season?.division ?? 'D1',
        position: season?.position ?? '',
        position_label: season?.position_label ?? '',
        cup_result: season?.cup_result ?? '',
        coach: season?.coach ?? '',
        badge: season?.badge ?? '',
        notes: season?.notes ?? '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/seasons/${season!.id}` : '/seasons';
        post(url);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier saison' : 'Nouvelle saison'} />

            <div className="mb-6">
                <Link
                    href="/seasons"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour à l'historique
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouveau'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit ? season!.season_label : 'Ajouter une saison'}
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
                        Informations
                    </div>
                    <div className="mt-4 grid gap-5 sm:grid-cols-2">
                        <Field label="Libellé de la saison" required error={errors.season_label} hint="Ex: 2025-2026">
                            <Input
                                value={data.season_label}
                                onChange={(e) => setData('season_label', e.target.value)}
                                required
                                placeholder="2025-2026"
                            />
                        </Field>
                        <Field label="Année de début" required error={errors.season_start_year} hint="Utilisée pour le tri">
                            <Input
                                type="number"
                                min={1900}
                                max={2100}
                                value={data.season_start_year}
                                onChange={(e) => setData('season_start_year', Number(e.target.value))}
                                required
                            />
                        </Field>
                        <Field label="Division" required error={errors.division} hint="D1, D2, Ligue du Gharb…">
                            <Input
                                value={data.division}
                                onChange={(e) => setData('division', e.target.value)}
                                required
                                placeholder="D1"
                            />
                        </Field>
                        <Field label="Position finale" error={errors.position} hint="Nombre, laisse vide si en cours">
                            <Input
                                type="number"
                                min={1}
                                max={100}
                                value={data.position === null ? '' : data.position}
                                onChange={(e) => setData('position', e.target.value === '' ? '' : Number(e.target.value))}
                                placeholder="3"
                            />
                        </Field>
                        <Field label="Libellé position" error={errors.position_label} hint="Champion, En cours, …">
                            <Input
                                value={data.position_label}
                                onChange={(e) => setData('position_label', e.target.value)}
                                placeholder="En cours"
                            />
                        </Field>
                        <Field label="Coupe du Trône" error={errors.cup_result}>
                            <Input
                                value={data.cup_result}
                                onChange={(e) => setData('cup_result', e.target.value)}
                                placeholder="16e tour, Demi-finale, …"
                            />
                        </Field>
                        <Field label="Coach" error={errors.coach}>
                            <Input
                                value={data.coach}
                                onChange={(e) => setData('coach', e.target.value)}
                                placeholder="Driss Talmoust"
                            />
                        </Field>
                        <Field label="Badge" error={errors.badge} hint="Champion, Promu, Relégué, Vice-champion, En cours">
                            <Input
                                value={data.badge}
                                onChange={(e) => setData('badge', e.target.value)}
                                placeholder="Champion"
                            />
                        </Field>
                        <Field label="Notes" error={errors.notes} className="sm:col-span-2">
                            <Textarea
                                value={data.notes}
                                onChange={(e) => setData('notes', e.target.value)}
                                rows={3}
                                placeholder="Faits marquants, top scorers, événements…"
                            />
                        </Field>
                    </div>
                </motion.div>

                <div className="flex items-center justify-end gap-3">
                    <Button asChild variant="outline" type="button">
                        <Link href="/seasons">Annuler</Link>
                    </Button>
                    <Button type="submit" size="lg" disabled={processing}>
                        {processing ? (
                            <Loader2 className="h-4 w-4 animate-spin" />
                        ) : (
                            <Save className="h-4 w-4" />
                        )}
                        {isEdit ? 'Enregistrer' : 'Ajouter la saison'}
                    </Button>
                </div>
            </form>
        </AdminLayout>
    );
}
