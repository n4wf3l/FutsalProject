import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { Building2, ExternalLink, Handshake, Pencil, Plus, Search, Trash2, X } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { cn } from '@/lib/utils';
import type { Sponsor } from '@/types/models';

interface Props {
    sponsors: Sponsor[];
}

export default function SponsorsIndex({ sponsors }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Sponsor | null>(null);
    const [selected, setSelected] = useState<Set<number>>(new Set());
    const [confirmBulk, setConfirmBulk] = useState(false);

    const filtered = sponsors.filter((s) => {
        if (!search) return true;
        return s.name.toLowerCase().includes(search.toLowerCase());
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
            if (allVisibleSelected) {
                filtered.forEach((s) => next.delete(s.id));
            } else {
                filtered.forEach((s) => next.add(s.id));
            }
            return next;
        });
    };

    const clearSelection = () => setSelected(new Set());

    const bulkDelete = () => {
        router.delete('/sponsors/bulk', {
            data: { ids: Array.from(selected) },
            onSuccess: () => { setSelected(new Set()); setConfirmBulk(false); },
        });
    };

    return (
        <AdminLayout title="Sponsors">
            <Head title="Sponsors" />

            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        Partenariats
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        Sponsors <span className="text-muted-foreground">· {sponsors.length}</span>
                    </h1>
                </div>
                <Button asChild size="lg">
                    <Link href="/sponsors/create">
                        <Plus className="h-4 w-4" />
                        Nouveau sponsor
                    </Link>
                </Button>
            </div>

            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        placeholder="Rechercher par nom…"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="pl-10"
                    />
                </div>
                <div className="text-sm text-muted-foreground">
                    {filtered.length} affiché{filtered.length > 1 ? 's' : ''}
                </div>
            </div>

            {filtered.length === 0 ? (
                <EmptyState
                    icon={Handshake}
                    title={search ? 'Aucun sponsor trouvé' : 'Aucun sponsor enregistré'}
                    description={
                        search
                            ? 'Essaie une autre recherche ou vide le filtre.'
                            : 'Ajoute un premier partenaire pour démarrer.'
                    }
                    action={
                        !search ? (
                            <Button asChild>
                                <Link href="/sponsors/create">
                                    <Plus className="h-4 w-4" />
                                    Nouveau sponsor
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
                                    <input type="checkbox" checked={allVisibleSelected} onChange={toggleAllVisible} className="h-4 w-4 cursor-pointer accent-crimson" aria-label="Tout sélectionner" />
                                </th>
                                <th className="w-20 px-4 py-3 text-left"></th>
                                <th className="px-4 py-3 text-left">Nom</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Site web</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((sponsor, i) => (
                                <SponsorRow
                                    key={sponsor.id}
                                    sponsor={sponsor}
                                    index={i}
                                    isSelected={selected.has(sponsor.id)}
                                    onToggle={() => toggle(sponsor.id)}
                                    onDelete={setToDelete}
                                />
                            ))}
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

            <ConfirmDialog
                open={!!toDelete}
                title={toDelete ? `Supprimer ${toDelete.name} ?` : ''}
                description="Le sponsor et son logo seront supprimés définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(`/sponsors/${toDelete.id}`, {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />

            <ConfirmDialog
                open={confirmBulk}
                title={`Supprimer ${selected.size} sponsor${selected.size > 1 ? 's' : ''} ?`}
                description="Cette action est irréversible. Les sponsors et leurs logos seront supprimés définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setConfirmBulk(false)}
                onConfirm={bulkDelete}
            />
        </AdminLayout>
    );
}

function SponsorRow({
    sponsor,
    index,
    isSelected,
    onToggle,
    onDelete,
}: {
    sponsor: Sponsor;
    index: number;
    isSelected: boolean;
    onToggle: () => void;
    onDelete: (s: Sponsor) => void;
}) {
    return (
        <motion.tr
            initial={{ opacity: 0, y: 4 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: (index % 20) * 0.02 }}
            className={cn('border-b border-border last:border-0 hover:bg-muted/30', isSelected && 'bg-champagne/5')}
        >
            <td className="px-4 py-3">
                <input type="checkbox" checked={isSelected} onChange={onToggle} className="h-4 w-4 cursor-pointer accent-crimson" aria-label={`Sélectionner ${sponsor.name}`} />
            </td>
            <td className="px-4 py-3">
                <div className="flex h-12 w-16 shrink-0 items-center justify-center overflow-hidden rounded-md border border-border bg-white p-1">
                    {sponsor.logo ? (
                        <img
                            src={`/storage/${sponsor.logo}`}
                            alt=""
                            loading="lazy"
                            className="h-full w-full object-contain"
                        />
                    ) : (
                        <Building2 className="h-5 w-5 text-muted-foreground/40" />
                    )}
                </div>
            </td>
            <td className="px-4 py-3">
                <div className="font-semibold">{sponsor.name}</div>
                {sponsor.website && (
                    <a
                        href={sponsor.website}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="mt-0.5 inline-flex items-center gap-1 text-xs text-muted-foreground hover:text-crimson md:hidden"
                    >
                        <ExternalLink className="h-3 w-3" />
                        Visiter
                    </a>
                )}
            </td>
            <td className="hidden px-4 py-3 md:table-cell">
                {sponsor.website ? (
                    <a
                        href={sponsor.website}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex items-center gap-1.5 text-sm text-muted-foreground transition-colors hover:text-crimson"
                    >
                        <ExternalLink className="h-3.5 w-3.5" />
                        <span className="max-w-[280px] truncate">
                            {sponsor.website.replace(/^https?:\/\//, '')}
                        </span>
                    </a>
                ) : (
                    <span className="text-sm text-muted-foreground/60">Non renseigné</span>
                )}
            </td>
            <td className="px-4 py-3">
                <div className="flex justify-end gap-1">
                    <Link
                        href={`/sponsors/${sponsor.id}/edit`}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-crimson"
                        aria-label="Éditer"
                    >
                        <Pencil className="h-4 w-4" />
                    </Link>
                    <button
                        onClick={() => onDelete(sponsor)}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-plasma/10 hover:text-plasma"
                        aria-label="Supprimer"
                    >
                        <Trash2 className="h-4 w-4" />
                    </button>
                </div>
            </td>
        </motion.tr>
    );
}
