import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { FileText, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import type { PressRelease } from '@/types/models';

interface Props {
    pressReleases: PressRelease[];
}

export default function PressReleasesIndex({ pressReleases }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<PressRelease | null>(null);

    const filtered = pressReleases.filter((p) => {
        if (!search) return true;
        const q = search.toLowerCase();
        return p.title.toLowerCase().includes(q) || p.slug.toLowerCase().includes(q);
    });

    return (
        <AdminLayout title="Communiqués de presse">
            <Head title="Communiqués de presse" />

            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        Contenu
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        Communiqués <span className="text-muted-foreground">· {pressReleases.length}</span>
                    </h1>
                </div>
                <Button asChild size="lg">
                    <Link href="/press_releases/create">
                        <Plus className="h-4 w-4" />
                        Nouveau communiqué
                    </Link>
                </Button>
            </div>

            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        placeholder="Rechercher par titre…"
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
                    icon={FileText}
                    title={search ? 'Aucun communiqué trouvé' : 'Aucun communiqué publié'}
                    description={
                        search
                            ? 'Essaie une autre recherche ou vide le filtre.'
                            : 'Publie un premier communiqué pour informer la presse.'
                    }
                    action={
                        !search ? (
                            <Button asChild>
                                <Link href="/press_releases/create">
                                    <Plus className="h-4 w-4" />
                                    Nouveau communiqué
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
                                <th className="w-16 px-4 py-3 text-left"></th>
                                <th className="px-4 py-3 text-left">Titre</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Slug</th>
                                <th className="hidden px-4 py-3 text-left lg:table-cell">Date</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((pressRelease, i) => (
                                <PressReleaseRow
                                    key={pressRelease.id}
                                    pressRelease={pressRelease}
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
                title={toDelete ? `Supprimer « ${toDelete.title} » ?` : ''}
                description="Le communiqué et son image seront supprimés définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(`/press_releases/${toDelete.id}`, {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />
        </AdminLayout>
    );
}

function PressReleaseRow({
    pressRelease,
    index,
    onDelete,
}: {
    pressRelease: PressRelease;
    index: number;
    onDelete: (p: PressRelease) => void;
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
                    {pressRelease.image ? (
                        <img
                            src={`/storage/${pressRelease.image}`}
                            alt=""
                            loading="lazy"
                            className="h-full w-full object-cover"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <FileText className="h-5 w-5 text-muted-foreground/40" />
                        </div>
                    )}
                </div>
            </td>
            <td className="px-4 py-3">
                <div className="font-semibold">{pressRelease.title}</div>
            </td>
            <td className="hidden px-4 py-3 font-mono text-[10px] text-muted-foreground md:table-cell">
                {pressRelease.slug}
            </td>
            <td className="hidden px-4 py-3 font-mono text-xs text-muted-foreground lg:table-cell">
                {new Date(pressRelease.created_at).toLocaleDateString('fr-FR')}
            </td>
            <td className="px-4 py-3">
                <div className="flex justify-end gap-1">
                    <Link
                        href={`/press_releases/${pressRelease.id}/edit`}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-crimson"
                        aria-label="Éditer"
                    >
                        <Pencil className="h-4 w-4" />
                    </Link>
                    <button
                        onClick={() => onDelete(pressRelease)}
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
