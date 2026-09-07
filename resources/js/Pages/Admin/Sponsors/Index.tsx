import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Building2, ExternalLink, Handshake, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import type { Sponsor } from '@/types/models';

interface Props {
    sponsors: Sponsor[];
}

export default function SponsorsIndex({ sponsors }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Sponsor | null>(null);

    const filtered = sponsors.filter((s) => {
        if (!search) return true;
        return s.name.toLowerCase().includes(search.toLowerCase());
    });

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
                                    onDelete={setToDelete}
                                />
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

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
        </AdminLayout>
    );
}

function SponsorRow({
    sponsor,
    index,
    onDelete,
}: {
    sponsor: Sponsor;
    index: number;
    onDelete: (s: Sponsor) => void;
}) {
    return (
        <motion.tr
            initial={{ opacity: 0, y: 4 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: (index % 20) * 0.02 }}
            className="border-b border-border last:border-0 hover:bg-muted/30"
        >
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
