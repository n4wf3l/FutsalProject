import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { CalendarClock, Pencil, Plus, Search, Trash2, X } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { cn } from '@/lib/utils';
import type { Season } from '@/types/models';

interface Props {
    seasons: Season[];
}

export default function SeasonsIndex({ seasons }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Season | null>(null);
    const [selected, setSelected] = useState<Set<number>>(new Set());
    const [confirmBulk, setConfirmBulk] = useState(false);

    const filtered = seasons.filter((s) => {
        if (!search) return true;
        const q = search.toLowerCase();
        return (
            s.season_label.toLowerCase().includes(q) ||
            s.division.toLowerCase().includes(q) ||
            (s.coach ?? '').toLowerCase().includes(q)
        );
    });

    const allVisibleSelected = filtered.length > 0 && filtered.every((s) => selected.has(s.id));
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
            if (allVisibleSelected) filtered.forEach((s) => next.delete(s.id));
            else filtered.forEach((s) => next.add(s.id));
            return next;
        });
    };

    const clearSelection = () => setSelected(new Set());

    const bulkDelete = () => {
        router.delete('/seasons/bulk', {
            data: { ids: Array.from(selected) },
            onSuccess: () => {
                setSelected(new Set());
                setConfirmBulk(false);
            },
        });
    };

    return (
        <AdminLayout title="Historique">
            <Head title="Historique" />

            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        Compétitions
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        Historique <span className="text-muted-foreground">· {seasons.length}</span>
                    </h1>
                </div>
                <Button asChild size="lg">
                    <Link href="/seasons/create">
                        <Plus className="h-4 w-4" />
                        Nouvelle saison
                    </Link>
                </Button>
            </div>

            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        placeholder="Rechercher par saison, division ou coach…"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="pl-10"
                    />
                </div>
                <div className="text-sm text-muted-foreground">
                    {filtered.length} affichée{filtered.length > 1 ? 's' : ''}
                </div>
            </div>

            {filtered.length === 0 ? (
                <EmptyState
                    icon={CalendarClock}
                    title={search ? 'Aucune saison trouvée' : 'Aucune saison'}
                    description={
                        search
                            ? 'Essaie une autre recherche ou vide le filtre.'
                            : 'Ajoute une première saison pour construire l\'historique.'
                    }
                    action={
                        !search ? (
                            <Button asChild>
                                <Link href="/seasons/create">
                                    <Plus className="h-4 w-4" />
                                    Nouvelle saison
                                </Link>
                            </Button>
                        ) : undefined
                    }
                />
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
                                <th className="px-4 py-3 text-left">Saison</th>
                                <th className="px-4 py-3 text-left">Division</th>
                                <th className="px-4 py-3 text-left">Position</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Coupe</th>
                                <th className="hidden px-4 py-3 text-left lg:table-cell">Coach</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((s, i) => {
                                const isSelected = selected.has(s.id);
                                return (
                                    <motion.tr
                                        key={s.id}
                                        initial={{ opacity: 0, y: 4 }}
                                        animate={{ opacity: 1, y: 0 }}
                                        transition={{ delay: (i % 20) * 0.02 }}
                                        className={cn(
                                            'border-b border-border last:border-0 hover:bg-muted/30',
                                            isSelected && 'bg-champagne/5'
                                        )}
                                    >
                                        <td className="px-4 py-3">
                                            <input
                                                type="checkbox"
                                                checked={isSelected}
                                                onChange={() => toggle(s.id)}
                                                className="h-4 w-4 cursor-pointer accent-crimson"
                                                aria-label={`Sélectionner ${s.season_label}`}
                                            />
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="font-mono text-sm font-semibold">{s.season_label}</div>
                                            {s.badge && (
                                                <div className="mt-1 font-mono text-[10px] uppercase tracking-widest text-champagne">
                                                    {s.badge}
                                                </div>
                                            )}
                                        </td>
                                        <td className="px-4 py-3 font-display text-sm font-semibold">{s.division}</td>
                                        <td className="px-4 py-3 text-sm">
                                            {s.position_label ?? (s.position !== null ? `${s.position}` : '—')}
                                        </td>
                                        <td className="hidden px-4 py-3 text-sm text-muted-foreground md:table-cell">
                                            {s.cup_result ?? '—'}
                                        </td>
                                        <td className="hidden px-4 py-3 text-sm text-muted-foreground lg:table-cell">
                                            {s.coach ?? '—'}
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="flex justify-end gap-1">
                                                <Link
                                                    href={`/seasons/${s.id}/edit`}
                                                    className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-crimson"
                                                    aria-label="Éditer"
                                                >
                                                    <Pencil className="h-4 w-4" />
                                                </Link>
                                                <button
                                                    onClick={() => setToDelete(s)}
                                                    className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-plasma/10 hover:text-plasma"
                                                    aria-label="Supprimer"
                                                >
                                                    <Trash2 className="h-4 w-4" />
                                                </button>
                                            </div>
                                        </td>
                                    </motion.tr>
                                );
                            })}
                        </tbody>
                    </table>
                </div>
            )}

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
                            {selected.size} sélectionnée{selected.size > 1 ? 's' : ''}
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

            <ConfirmDialog
                open={!!toDelete}
                title={toDelete ? `Supprimer la saison ${toDelete.season_label} ?` : ''}
                description="Cette saison sera supprimée définitivement de l'historique."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(`/seasons/${toDelete.id}`, {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />

            <ConfirmDialog
                open={confirmBulk}
                title={`Supprimer ${selected.size} saison${selected.size > 1 ? 's' : ''} ?`}
                description="Cette action est irréversible. Les saisons sélectionnées seront supprimées définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setConfirmBulk(false)}
                onConfirm={bulkDelete}
            />
        </AdminLayout>
    );
}
