import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { Pencil, Plus, Search, Shield, Trash2, X } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { cn } from '@/lib/utils';
import type { Team } from '@/types/models';

interface Props { teams: Team[]; }

export default function TeamsIndex({ teams }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Team | null>(null);
    const [selected, setSelected] = useState<Set<number>>(new Set());
    const [confirmBulk, setConfirmBulk] = useState(false);

    const filtered = teams.filter((t) => !search || t.name.toLowerCase().includes(search.toLowerCase()));

    const allVisibleSelected = filtered.length > 0 && filtered.every((t) => selected.has(t.id));
    const someSelected = selected.size > 0;

    const toggle = (id: number) => {
        setSelected((prev) => {
            const next = new Set(prev);
            if (next.has(id)) next.delete(id);
            else next.add(id);
            return next;
        });
    };

    const toggleAllVisible = () => {
        setSelected((prev) => {
            const next = new Set(prev);
            if (allVisibleSelected) {
                filtered.forEach((t) => next.delete(t.id));
            } else {
                filtered.forEach((t) => next.add(t.id));
            }
            return next;
        });
    };

    const clearSelection = () => setSelected(new Set());

    const bulkDelete = () => {
        router.delete('/manage-teams/bulk', {
            data: { ids: Array.from(selected) },
            onSuccess: () => {
                setSelected(new Set());
                setConfirmBulk(false);
            },
        });
    };

    return (
        <AdminLayout title="Équipes">
            <Head title="Équipes" />
            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">Compétition</div>
                    <h1 className="mt-1 font-display text-3xl font-bold">Équipes <span className="text-muted-foreground">· {teams.length}</span></h1>
                </div>
                <Button asChild size="lg"><Link href="/manage-teams/create"><Plus className="h-4 w-4" />Nouvelle équipe</Link></Button>
            </div>
            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input placeholder="Rechercher…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-10" />
                </div>
            </div>
            {filtered.length === 0 ? (
                <EmptyState icon={Shield} title="Aucune équipe" description="Ajoute les équipes du championnat." action={<Button asChild><Link href="/manage-teams/create"><Plus className="h-4 w-4" />Nouvelle équipe</Link></Button>} />
            ) : (
                <div className="overflow-hidden rounded-2xl border border-border bg-card">
                    <table className="w-full">
                        <thead>
                            <tr className="border-b border-border bg-muted/50 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                <th className="w-10 px-4 py-3">
                                    <input
                                        type="checkbox"
                                        checked={allVisibleSelected}
                                        onChange={toggleAllVisible}
                                        className="h-4 w-4 cursor-pointer accent-crimson"
                                        aria-label="Tout sélectionner"
                                    />
                                </th>
                                <th className="w-16 px-4 py-3"></th>
                                <th className="px-4 py-3 text-left">Équipe</th>
                                <th className="hidden px-4 py-3 text-right md:table-cell">MJ</th>
                                <th className="hidden px-4 py-3 text-right md:table-cell">Pts</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((t, i) => {
                                const isSelected = selected.has(t.id);
                                return (
                                <motion.tr key={t.id} initial={{ opacity: 0, y: 4 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: (i % 20) * 0.02 }} className={cn('border-b border-border last:border-0 hover:bg-muted/30', isSelected && 'bg-champagne/5')}>
                                    <td className="px-4 py-3">
                                        <input
                                            type="checkbox"
                                            checked={isSelected}
                                            onChange={() => toggle(t.id)}
                                            className="h-4 w-4 cursor-pointer accent-crimson"
                                            aria-label={`Sélectionner ${t.name}`}
                                        />
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border border-border bg-background">
                                            {t.logo_path ? <img src={`/storage/${t.logo_path}`} alt="" loading="lazy" className="h-full w-full object-contain p-1" /> : <Shield className="h-5 w-5 text-muted-foreground/40" />}
                                        </div>
                                    </td>
                                    <td className="px-4 py-3 font-semibold">{t.name}</td>
                                    <td className="hidden px-4 py-3 text-right font-mono text-xs md:table-cell">{t.games_played ?? 0}</td>
                                    <td className="hidden px-4 py-3 text-right font-mono text-sm font-bold md:table-cell">{t.points ?? 0}</td>
                                    <td className="px-4 py-3">
                                        <div className="flex justify-end gap-1">
                                            <Link href={`/manage-teams/${t.id}/edit`} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-muted hover:text-crimson"><Pencil className="h-4 w-4" /></Link>
                                            <button onClick={() => setToDelete(t)} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-plasma/10 hover:text-plasma"><Trash2 className="h-4 w-4" /></button>
                                        </div>
                                    </td>
                                </motion.tr>
                                );
                            })}
                        </tbody>
                    </table>
                </div>
            )}

            {/* Sticky bulk action bar */}
            <AnimatePresence>
                {someSelected && (
                    <motion.div
                        initial={{ y: 60, opacity: 0 }}
                        animate={{ y: 0, opacity: 1 }}
                        exit={{ y: 60, opacity: 0 }}
                        transition={{ type: 'spring', damping: 26, stiffness: 260 }}
                        className="fixed bottom-6 left-1/2 z-40 flex -translate-x-1/2 items-center gap-3 rounded-full border border-champagne/40 bg-card px-5 py-3 shadow-2xl shadow-black/40"
                    >
                        <span className="font-mono text-xs font-semibold uppercase tracking-widest text-champagne">
                            {selected.size} sélectionné{selected.size > 1 ? 's' : ''}
                        </span>
                        <button
                            onClick={clearSelection}
                            className="inline-flex h-8 items-center gap-1.5 rounded-full px-3 text-xs text-muted-foreground hover:bg-muted hover:text-foreground"
                        >
                            <X className="h-3.5 w-3.5" />
                            Désélectionner
                        </button>
                        <Button variant="destructive" size="sm" onClick={() => setConfirmBulk(true)}>
                            <Trash2 className="h-4 w-4" />
                            Supprimer
                        </Button>
                    </motion.div>
                )}
            </AnimatePresence>

            <ConfirmDialog open={!!toDelete} title={toDelete ? `Supprimer ${toDelete.name} ?` : ''} description="L'équipe et son logo seront supprimés." confirmLabel="Supprimer" variant="destructive" onCancel={() => setToDelete(null)} onConfirm={() => { if (!toDelete) return; router.delete(`/manage-teams/${toDelete.id}`, { onSuccess: () => setToDelete(null) }); }} />

            <ConfirmDialog
                open={confirmBulk}
                title={`Supprimer ${selected.size} équipe${selected.size > 1 ? 's' : ''} ?`}
                description="Cette action est irréversible. Les équipes et leurs logos seront supprimés définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setConfirmBulk(false)}
                onConfirm={bulkDelete}
            />
        </AdminLayout>
    );
}
