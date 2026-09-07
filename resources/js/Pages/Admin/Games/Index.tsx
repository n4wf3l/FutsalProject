import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Calendar as CalIcon, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Badge } from '@/Components/ui/Badge';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import type { Game } from '@/types/models';

interface Props { games: Game[]; }

export default function GamesIndex({ games }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Game | null>(null);

    const filtered = games.filter((g) => {
        if (!search) return true;
        const q = search.toLowerCase();
        return (g.homeTeam?.name.toLowerCase().includes(q) || g.awayTeam?.name.toLowerCase().includes(q));
    });

    return (
        <AdminLayout title="Matchs">
            <Head title="Matchs" />
            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">Compétition</div>
                    <h1 className="mt-1 font-display text-3xl font-bold">Matchs <span className="text-muted-foreground">· {games.length}</span></h1>
                </div>
                <Button asChild size="lg"><Link href="/games/create"><Plus className="h-4 w-4" />Nouveau match</Link></Button>
            </div>
            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input placeholder="Rechercher une équipe…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-10" />
                </div>
            </div>
            {filtered.length === 0 ? (
                <EmptyState icon={CalIcon} title="Aucun match" description="Ajoute le calendrier de la saison." action={<Button asChild><Link href="/games/create"><Plus className="h-4 w-4" />Nouveau match</Link></Button>} />
            ) : (
                <div className="overflow-hidden rounded-2xl border border-border bg-card">
                    <table className="w-full">
                        <thead>
                            <tr className="border-b border-border bg-muted/50 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                <th className="px-4 py-3 text-left">Date</th>
                                <th className="px-4 py-3 text-left">Domicile</th>
                                <th className="px-4 py-3 text-center">Score</th>
                                <th className="px-4 py-3 text-left">Extérieur</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((g, i) => (
                                <motion.tr key={g.id} initial={{ opacity: 0, y: 4 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: (i % 20) * 0.02 }} className="border-b border-border last:border-0 hover:bg-muted/30">
                                    <td className="px-4 py-3 font-mono text-xs">{new Date(g.match_date).toLocaleDateString('fr-FR', { day: '2-digit', month: 'short', year: 'numeric' })}</td>
                                    <td className="px-4 py-3 font-semibold">{g.homeTeam?.name ?? '—'}</td>
                                    <td className="px-4 py-3 text-center">
                                        {g.home_score !== null && g.away_score !== null
                                            ? <Badge variant="win">{g.home_score} - {g.away_score}</Badge>
                                            : <Badge variant="muted">vs</Badge>}
                                    </td>
                                    <td className="px-4 py-3 font-semibold">{g.awayTeam?.name ?? '—'}</td>
                                    <td className="px-4 py-3">
                                        <div className="flex justify-end gap-1">
                                            <Link href={`/games/${g.id}/edit`} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-muted hover:text-crimson"><Pencil className="h-4 w-4" /></Link>
                                            <button onClick={() => setToDelete(g)} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-plasma/10 hover:text-plasma"><Trash2 className="h-4 w-4" /></button>
                                        </div>
                                    </td>
                                </motion.tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
            <ConfirmDialog open={!!toDelete} title={toDelete ? `Supprimer ce match ?` : ''} description="Les statistiques des équipes seront ajustées." confirmLabel="Supprimer" variant="destructive" onCancel={() => setToDelete(null)} onConfirm={() => { if (!toDelete) return; router.delete(`/games/${toDelete.id}`, { onSuccess: () => setToDelete(null) }); }} />
        </AdminLayout>
    );
}
