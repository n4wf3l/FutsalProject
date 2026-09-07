import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Loader2, Save } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Game, Team } from '@/types/models';

interface Props { game: Game | null; teams: Team[]; }

export default function GameForm({ game, teams }: Props) {
    const isEdit = !!game;
    const hasResult = isEdit && game!.home_score !== null && game!.away_score !== null;
    const [editScore, setEditScore] = useState(hasResult);

    const { data, setData, post, processing, errors } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        home_team_id: game?.home_team_id ?? 0,
        away_team_id: game?.away_team_id ?? 0,
        match_date: game?.match_date ?? '',
    });

    const scoreForm = useForm({
        home_team_score: game?.home_score ?? 0,
        away_team_score: game?.away_score ?? 0,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(isEdit ? `/games/${game!.id}` : '/games');
    };

    const submitScores: FormEventHandler = (e) => {
        e.preventDefault();
        scoreForm.post(`/games/${game!.id}/scores`);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier match' : 'Nouveau match'} />
            <div className="mb-6">
                <Link href="/games" className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-crimson"><ArrowLeft className="h-4 w-4" />Retour</Link>
                <h1 className="mt-3 font-display text-3xl font-bold">{isEdit ? 'Modifier le match' : 'Ajouter un match'}</h1>
            </div>

            <div className="grid gap-6 lg:grid-cols-[1fr_360px]">
                <motion.form onSubmit={submit} initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} className="space-y-6">
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">Rencontre</div>
                        <div className="mt-4 grid gap-5 sm:grid-cols-2">
                            <Field label="Équipe domicile" required error={errors.home_team_id}>
                                <select value={data.home_team_id} onChange={(e) => setData('home_team_id', parseInt(e.target.value))} required className="flex h-10 w-full rounded-lg border border-input bg-card px-4 py-2 text-sm text-foreground focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20">
                                    <option value={0}>— Sélectionner —</option>
                                    {teams.map((t) => <option key={t.id} value={t.id}>{t.name}</option>)}
                                </select>
                            </Field>
                            <Field label="Équipe extérieure" required error={errors.away_team_id}>
                                <select value={data.away_team_id} onChange={(e) => setData('away_team_id', parseInt(e.target.value))} required className="flex h-10 w-full rounded-lg border border-input bg-card px-4 py-2 text-sm text-foreground focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20">
                                    <option value={0}>— Sélectionner —</option>
                                    {teams.map((t) => <option key={t.id} value={t.id}>{t.name}</option>)}
                                </select>
                            </Field>
                            <Field label="Date du match" required error={errors.match_date} className="sm:col-span-2">
                                <Input type="date" value={data.match_date} onChange={(e) => setData('match_date', e.target.value)} required />
                            </Field>
                        </div>
                    </div>
                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button"><Link href="/games">Annuler</Link></Button>
                        <Button type="submit" size="lg" disabled={processing}>{processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}{isEdit ? 'Enregistrer' : 'Créer'}</Button>
                    </div>
                </motion.form>

                {isEdit && (
                    <motion.aside initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.05 }} className="space-y-4">
                        <div className="rounded-2xl border border-champagne/30 bg-card p-6">
                            <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">Score</div>
                            {editScore ? (
                                <form onSubmit={submitScores} className="mt-4 space-y-4">
                                    <div className="grid grid-cols-2 gap-3">
                                        <Field label="Domicile">
                                            <Input type="number" min="0" value={scoreForm.data.home_team_score} onChange={(e) => scoreForm.setData('home_team_score', parseInt(e.target.value) || 0)} />
                                        </Field>
                                        <Field label="Extérieur">
                                            <Input type="number" min="0" value={scoreForm.data.away_team_score} onChange={(e) => scoreForm.setData('away_team_score', parseInt(e.target.value) || 0)} />
                                        </Field>
                                    </div>
                                    <Button type="submit" className="w-full" disabled={scoreForm.processing}>
                                        {scoreForm.processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}
                                        Enregistrer les scores
                                    </Button>
                                </form>
                            ) : (
                                <>
                                    <p className="mt-3 text-sm text-muted-foreground">Aucun score enregistré pour ce match.</p>
                                    <Button type="button" variant="outline" className="mt-4 w-full" onClick={() => setEditScore(true)}>Saisir le score</Button>
                                </>
                            )}
                        </div>
                    </motion.aside>
                )}
            </div>
        </AdminLayout>
    );
}
