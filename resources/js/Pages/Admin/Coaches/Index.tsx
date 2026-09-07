import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { Pencil, Plus, Search, Trash2, User, UserCog, X } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { cn } from '@/lib/utils';
import type { Coach } from '@/types/models';

interface Props { coaches: Coach[]; }

export default function CoachesIndex({ coaches }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Coach | null>(null);
    const [selected, setSelected] = useState<Set<number>>(new Set());
    const [confirmBulk, setConfirmBulk] = useState(false);

    const filtered = coaches.filter((c) => !search || `${c.first_name} ${c.last_name}`.toLowerCase().includes(search.toLowerCase()));

    const allVisibleSelected = filtered.length > 0 && filtered.every((c) => selected.has(c.id));
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
                filtered.forEach((c) => next.delete(c.id));
            } else {
                filtered.forEach((c) => next.add(c.id));
            }
            return next;
        });
    };

    const clearSelection = () => setSelected(new Set());

    const bulkDelete = () => {
        router.delete('/coaches/bulk', {
            data: { ids: Array.from(selected) },
            onSuccess: () => { setSelected(new Set()); setConfirmBulk(false); },
        });
    };

    return (
        <AdminLayout title="Coachs">
            <Head title="Coachs" />
            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">Encadrement</div>
                    <h1 className="mt-1 font-display text-3xl font-bold">Coachs <span className="text-muted-foreground">· {coaches.length}</span></h1>
                </div>
                <Button asChild size="lg"><Link href="/coaches/create"><Plus className="h-4 w-4" />Nouveau coach</Link></Button>
            </div>
            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input placeholder="Rechercher…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-10" />
                </div>
            </div>
            {filtered.length === 0 ? (
                <EmptyState icon={UserCog} title="Aucun coach" description="Ajoute le head coach ou un adjoint." action={<Button asChild><Link href="/coaches/create"><Plus className="h-4 w-4" />Nouveau coach</Link></Button>} />
            ) : (
                <div className="overflow-hidden rounded-2xl border border-border bg-card">
                    <table className="w-full">
                        <thead>
                            <tr className="border-b border-border bg-muted/50 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                <th className="w-10 px-4 py-3">
                                    <input type="checkbox" checked={allVisibleSelected} onChange={toggleAllVisible} className="h-4 w-4 cursor-pointer accent-crimson" aria-label="Tout sélectionner" />
                                </th>
                                <th className="w-16 px-4 py-3"></th>
                                <th className="px-4 py-3 text-left">Nom</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Nationalité</th>
                                <th className="hidden px-4 py-3 text-left lg:table-cell">Coach depuis</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((c, i) => {
                                const isSelected = selected.has(c.id);
                                return (
                                    <motion.tr key={c.id} initial={{ opacity: 0, y: 4 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: (i % 20) * 0.02 }} className={cn('border-b border-border last:border-0 hover:bg-muted/30', isSelected && 'bg-champagne/5')}>
                                        <td className="px-4 py-3">
                                            <input type="checkbox" checked={isSelected} onChange={() => toggle(c.id)} className="h-4 w-4 cursor-pointer accent-crimson" aria-label={`Sélectionner ${c.first_name} ${c.last_name}`} />
                                        </td>
                                        <td className="px-4 py-3">
                                            <div className="h-10 w-10 shrink-0 overflow-hidden rounded-full border border-border bg-muted">
                                                {c.photo ? <img src={`/storage/${c.photo}`} alt="" loading="lazy" className="h-full w-full object-cover" /> : <div className="flex h-full w-full items-center justify-center"><User className="h-5 w-5 text-muted-foreground/40" /></div>}
                                            </div>
                                        </td>
                                        <td className="px-4 py-3"><div className="font-semibold">{c.first_name} {c.last_name}</div></td>
                                        <td className="hidden px-4 py-3 text-sm text-muted-foreground md:table-cell">{c.nationality ?? ''}</td>
                                        <td className="hidden px-4 py-3 font-mono text-xs text-muted-foreground lg:table-cell">{c.coaching_since ? new Date(c.coaching_since).getFullYear() : ''}</td>
                                        <td className="px-4 py-3">
                                            <div className="flex justify-end gap-1">
                                                <Link href={`/coaches/${c.id}/edit`} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-muted hover:text-crimson"><Pencil className="h-4 w-4" /></Link>
                                                <button onClick={() => setToDelete(c)} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-plasma/10 hover:text-plasma"><Trash2 className="h-4 w-4" /></button>
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
                            {selected.size} sélectionné{selected.size > 1 ? 's' : ''}
                        </span>
                        <button onClick={clearSelection} className="inline-flex h-8 items-center gap-1.5 rounded-full px-3 text-xs text-muted-foreground hover:bg-muted hover:text-foreground">
                            <X className="h-3.5 w-3.5" />Désélectionner
                        </button>
                        <Button variant="destructive" size="sm" onClick={() => setConfirmBulk(true)}>
                            <Trash2 className="h-4 w-4" />Supprimer
                        </Button>
                    </motion.div>
                )}
            </AnimatePresence>

            <ConfirmDialog open={!!toDelete} title={toDelete ? `Supprimer ${toDelete.first_name} ${toDelete.last_name} ?` : ''} description="Cette action est irréversible." confirmLabel="Supprimer" variant="destructive" onCancel={() => setToDelete(null)} onConfirm={() => { if (!toDelete) return; router.delete(`/coaches/${toDelete.id}`, { onSuccess: () => setToDelete(null) }); }} />

            <ConfirmDialog
                open={confirmBulk}
                title={`Supprimer ${selected.size} coach${selected.size > 1 ? 's' : ''} ?`}
                description="Cette action est irréversible. Les coachs et leurs photos seront supprimés définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setConfirmBulk(false)}
                onConfirm={bulkDelete}
            />
        </AdminLayout>
    );
}
