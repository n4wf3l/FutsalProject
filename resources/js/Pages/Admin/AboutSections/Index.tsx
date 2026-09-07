import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Info, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import type { AboutSection } from '@/types/models';

interface Props {
    sections: AboutSection[];
}

function excerpt(html: string, max = 100): string {
    const text = html.replace(/<[^>]*>/g, '').replace(/\s+/g, ' ').trim();
    return text.length > max ? text.slice(0, max) + '…' : text;
}

export default function AboutSectionsIndex({ sections }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<AboutSection | null>(null);

    const filtered = sections.filter((s) => {
        if (!search) return true;
        return s.title.toLowerCase().includes(search.toLowerCase());
    });

    return (
        <AdminLayout title="Sections À propos">
            <Head title="Sections À propos" />

            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        Contenu
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        Sections À propos <span className="text-muted-foreground">· {sections.length}</span>
                    </h1>
                </div>
                <Button asChild size="lg">
                    <Link href="/about/create">
                        <Plus className="h-4 w-4" />
                        Nouvelle section
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
                    {filtered.length} affichée{filtered.length > 1 ? 's' : ''}
                </div>
            </div>

            {filtered.length === 0 ? (
                <EmptyState
                    icon={Info}
                    title={search ? 'Aucune section trouvée' : 'Aucune section'}
                    description={
                        search
                            ? 'Essaie une autre recherche ou vide le filtre.'
                            : 'Ajoute une première section pour la page À propos.'
                    }
                    action={
                        !search ? (
                            <Button asChild>
                                <Link href="/about/create">
                                    <Plus className="h-4 w-4" />
                                    Nouvelle section
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
                                <th className="px-4 py-3 text-left">Titre</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Extrait</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((section, i) => (
                                <motion.tr
                                    key={section.id}
                                    initial={{ opacity: 0, y: 4 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ delay: (i % 20) * 0.02 }}
                                    className="border-b border-border last:border-0 hover:bg-muted/30"
                                >
                                    <td className="px-4 py-3">
                                        <div className="font-semibold">{section.title}</div>
                                    </td>
                                    <td className="hidden px-4 py-3 text-sm text-muted-foreground md:table-cell">
                                        {excerpt(section.content)}
                                    </td>
                                    <td className="px-4 py-3">
                                        <div className="flex justify-end gap-1">
                                            <Link
                                                href={`/about/${section.id}/edit`}
                                                className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-crimson"
                                                aria-label="Éditer"
                                            >
                                                <Pencil className="h-4 w-4" />
                                            </Link>
                                            <button
                                                onClick={() => setToDelete(section)}
                                                className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-plasma/10 hover:text-plasma"
                                                aria-label="Supprimer"
                                            >
                                                <Trash2 className="h-4 w-4" />
                                            </button>
                                        </div>
                                    </td>
                                </motion.tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

            <ConfirmDialog
                open={!!toDelete}
                title={toDelete ? `Supprimer "${toDelete.title}" ?` : ''}
                description="Cette section sera supprimée définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(`/about/${toDelete.id}`, {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />
        </AdminLayout>
    );
}
