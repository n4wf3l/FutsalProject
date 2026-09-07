import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Pencil, Plus, Search, Ticket, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import type { Tribune } from '@/types/models';

interface Props {
    tribunes: Tribune[];
}

export default function TribunesIndex({ tribunes }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Tribune | null>(null);

    const filtered = tribunes.filter((t) => {
        if (!search) return true;
        const q = search.toLowerCase();
        return t.name.toLowerCase().includes(q);
    });

    return (
        <AdminLayout title="Tribunes">
            <Head title="Tribunes" />

            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        Billetterie
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        Tribunes <span className="text-muted-foreground">· {tribunes.length}</span>
                    </h1>
                </div>
                <Button asChild size="lg">
                    <Link href="/tribunes/create">
                        <Plus className="h-4 w-4" />
                        Nouvelle tribune
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
                    {filtered.length} affichée{filtered.length > 1 ? 's' : ''}
                </div>
            </div>

            {filtered.length === 0 ? (
                <EmptyState
                    icon={Ticket}
                    title={search ? 'Aucune tribune trouvée' : 'Aucune tribune enregistrée'}
                    description={
                        search
                            ? 'Essaie une autre recherche ou vide le filtre.'
                            : 'Crée ta première tribune pour ouvrir la billetterie.'
                    }
                    action={
                        !search ? (
                            <Button asChild>
                                <Link href="/tribunes/create">
                                    <Plus className="h-4 w-4" />
                                    Nouvelle tribune
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
                                <th className="w-20 px-4 py-3 text-left"></th>
                                <th className="px-4 py-3 text-left">Nom</th>
                                <th className="hidden px-4 py-3 text-left sm:table-cell">Prix</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Places dispo</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((tribune, i) => (
                                <TribuneRow
                                    key={tribune.id}
                                    tribune={tribune}
                                    index={i}
                                    onDelete={setToDelete}
                                />
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

            <ConfirmDialog
                open={!!toDelete}
                title={toDelete ? `Supprimer "${toDelete.name}" ?` : ''}
                description="La tribune et sa photo seront supprimées définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(`/tribunes/${toDelete.id}`, {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />
        </AdminLayout>
    );
}

function TribuneRow({
    tribune,
    index,
    onDelete,
}: {
    tribune: Tribune;
    index: number;
    onDelete: (t: Tribune) => void;
}) {
    return (
        <motion.tr
            initial={{ opacity: 0, y: 4 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: (index % 20) * 0.02 }}
            className="border-b border-border last:border-0 hover:bg-muted/30"
        >
            <td className="px-4 py-3">
                <div className="h-12 w-16 shrink-0 overflow-hidden rounded-md border border-border bg-muted">
                    {tribune.photo ? (
                        <img
                            src={`/storage/${tribune.photo}`}
                            alt=""
                            loading="lazy"
                            className="h-full w-full object-cover"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <Ticket className="h-5 w-5 text-muted-foreground/40" />
                        </div>
                    )}
                </div>
            </td>
            <td className="px-4 py-3">
                <div className="font-semibold">{tribune.name}</div>
                {tribune.description && (
                    <div className="line-clamp-1 text-xs text-muted-foreground">
                        {tribune.description}
                    </div>
                )}
            </td>
            <td className="hidden px-4 py-3 font-mono text-sm sm:table-cell">
                {Number(tribune.price).toFixed(2)} {tribune.currency}
            </td>
            <td className="hidden px-4 py-3 font-mono text-sm text-muted-foreground md:table-cell">
                {tribune.available_seats}
            </td>
            <td className="px-4 py-3">
                <div className="flex justify-end gap-1">
                    <Link
                        href={`/tribunes/${tribune.id}/edit`}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-crimson"
                        aria-label="Éditer"
                    >
                        <Pencil className="h-4 w-4" />
                    </Link>
                    <button
                        onClick={() => onDelete(tribune)}
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
