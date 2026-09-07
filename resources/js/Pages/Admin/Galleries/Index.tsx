import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { Image as ImageIcon, Pencil, Plus, Search, Trash2, X } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { cn } from '@/lib/utils';
import type { Gallery } from '@/types/models';

interface Props { galleries: (Gallery & { photos_count?: number })[]; }

export default function GalleriesIndex({ galleries }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Gallery | null>(null);
    const [selected, setSelected] = useState<Set<number>>(new Set());
    const [confirmBulk, setConfirmBulk] = useState(false);
    const filtered = galleries.filter((g) => !search || g.name.toLowerCase().includes(search.toLowerCase()));

    const someSelected = selected.size > 0;

    const toggle = (id: number) => {
        setSelected((prev) => {
            const next = new Set(prev);
            if (next.has(id)) next.delete(id);
            else next.add(id);
            return next;
        });
    };

    const clearSelection = () => setSelected(new Set());

    const bulkDelete = () => {
        router.delete('/admin/galleries/bulk', {
            data: { ids: Array.from(selected) },
            onSuccess: () => {
                setSelected(new Set());
                setConfirmBulk(false);
            },
        });
    };

    return (
        <AdminLayout title="Galeries">
            <Head title="Galeries" />
            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">Contenu</div>
                    <h1 className="mt-1 font-display text-3xl font-bold">Galeries <span className="text-muted-foreground">· {galleries.length}</span></h1>
                </div>
                <Button asChild size="lg"><Link href="/admin/galleries/create"><Plus className="h-4 w-4" />Nouvelle galerie</Link></Button>
            </div>
            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input placeholder="Rechercher…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-10" />
                </div>
            </div>
            {filtered.length === 0 ? (
                <EmptyState icon={ImageIcon} title="Aucune galerie" description="Crée une galerie pour organiser tes photos." action={<Button asChild><Link href="/admin/galleries/create"><Plus className="h-4 w-4" />Nouvelle galerie</Link></Button>} />
            ) : (
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    {filtered.map((g, i) => {
                        const isSelected = selected.has(g.id);
                        return (
                        <motion.div key={g.id} initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: (i % 6) * 0.04 }} className={cn('group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40', isSelected && 'border-champagne/60 bg-champagne/5')}>
                            <label className="absolute left-3 top-3 z-10 flex h-8 w-8 cursor-pointer items-center justify-center rounded-full border border-champagne/40 bg-obsidian/70 backdrop-blur-sm transition-colors hover:bg-obsidian/90">
                                <input
                                    type="checkbox"
                                    checked={isSelected}
                                    onChange={() => toggle(g.id)}
                                    className="h-4 w-4 cursor-pointer accent-crimson"
                                    aria-label={`Sélectionner ${g.name}`}
                                    onClick={(e) => e.stopPropagation()}
                                />
                            </label>
                            <Link href={`/admin/galleries/${g.id}/photos`} className="block">
                                <div className="relative aspect-video overflow-hidden bg-muted">
                                    {g.cover_image ? <img src={`/storage/${g.cover_image}`} alt="" loading="lazy" className="h-full w-full object-cover transition-transform group-hover:scale-105" /> : <div className="flex h-full w-full items-center justify-center"><ImageIcon className="h-12 w-12 text-muted-foreground/40" /></div>}
                                </div>
                                <div className="p-4">
                                    <div className="font-display text-lg font-semibold group-hover:text-crimson">{g.name}</div>
                                    <div className="mt-1 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">{g.photos_count ?? 0} photo{(g.photos_count ?? 0) > 1 ? 's' : ''}</div>
                                </div>
                            </Link>
                            <div className="flex items-center gap-1 border-t border-border p-2">
                                <Link href={`/galleries/${g.id}/edit`} className="inline-flex h-8 flex-1 items-center justify-center gap-1.5 rounded-lg text-xs text-muted-foreground hover:bg-muted hover:text-crimson"><Pencil className="h-3.5 w-3.5" />Modifier</Link>
                                <Link href={`/admin/galleries/${g.id}/photos`} className="inline-flex h-8 flex-1 items-center justify-center gap-1.5 rounded-lg text-xs text-muted-foreground hover:bg-muted hover:text-champagne"><ImageIcon className="h-3.5 w-3.5" />Photos</Link>
                                <button onClick={() => setToDelete(g)} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-plasma/10 hover:text-plasma"><Trash2 className="h-3.5 w-3.5" /></button>
                            </div>
                        </motion.div>
                        );
                    })}
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

            <ConfirmDialog open={!!toDelete} title={toDelete ? `Supprimer "${toDelete.name}" ?` : ''} description="La galerie ET toutes ses photos seront supprimées définitivement." confirmLabel="Supprimer" variant="destructive" onCancel={() => setToDelete(null)} onConfirm={() => { if (!toDelete) return; router.delete(`/galleries/${toDelete.id}`, { onSuccess: () => setToDelete(null) }); }} />

            <ConfirmDialog
                open={confirmBulk}
                title={`Supprimer ${selected.size} galerie${selected.size > 1 ? 's' : ''} ?`}
                description="Cette action est irréversible. Les galeries ET toutes leurs photos seront supprimées définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setConfirmBulk(false)}
                onConfirm={bulkDelete}
            />
        </AdminLayout>
    );
}
