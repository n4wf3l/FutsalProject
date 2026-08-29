import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Eye, EyeOff, Mic, Pencil, Plus, Search, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Badge } from '@/Components/ui/Badge';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { formatMatchDate } from '@/lib/utils';
import type { Interview } from '@/types/models';

interface Props {
    interviews: Interview[];
}

export default function AdminInterviewsIndex({ interviews }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Interview | null>(null);

    const filtered = interviews.filter((i) => {
        if (!search) return true;
        const q = search.toLowerCase();
        return (
            i.title.toLowerCase().includes(q) ||
            i.interviewee_name.toLowerCase().includes(q) ||
            i.interviewee_role.toLowerCase().includes(q) ||
            (i.interviewee_affiliation ?? '').toLowerCase().includes(q)
        );
    });

    return (
        <AdminLayout title="Interviews">
            <Head title="Interviews" />

            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        La Voix du Futsal
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        Interviews <span className="text-muted-foreground">· {interviews.length}</span>
                    </h1>
                </div>
                <Button asChild size="lg">
                    <Link href={route('admin.interviews.create')}>
                        <Plus className="h-4 w-4" />
                        Nouvelle interview
                    </Link>
                </Button>
            </div>

            <div className="mb-6 flex items-center gap-3">
                <div className="relative max-w-sm flex-1">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        placeholder="Rechercher par titre, nom, rôle…"
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
                    icon={Mic}
                    title={search ? 'Aucune interview trouvée' : 'Aucune interview'}
                    description={
                        search
                            ? "Essaie une autre recherche."
                            : 'Publie ta première interview pour lancer la série.'
                    }
                    action={
                        !search ? (
                            <Button asChild>
                                <Link href={route('admin.interviews.create')}>
                                    <Plus className="h-4 w-4" />
                                    Nouvelle interview
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
                                <th className="px-4 py-3 text-left">Interview</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Rôle</th>
                                <th className="hidden px-4 py-3 text-left lg:table-cell">Publiée</th>
                                <th className="px-4 py-3 text-left">Statut</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((interview, i) => (
                                <InterviewRow
                                    key={interview.id}
                                    interview={interview}
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
                description="Cette action est irréversible. L'interview et ses images seront supprimées."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(route('admin.interviews.destroy', toDelete.id), {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />
        </AdminLayout>
    );
}

function InterviewRow({
    interview,
    index,
    onDelete,
}: {
    interview: Interview;
    index: number;
    onDelete: (i: Interview) => void;
}) {
    const isPublished = !!interview.published_at && new Date(interview.published_at) <= new Date();
    const date = interview.published_at ? formatMatchDate(interview.published_at) : null;

    return (
        <motion.tr
            initial={{ opacity: 0, y: 4 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: (index % 20) * 0.02 }}
            className="border-b border-border last:border-0 hover:bg-muted/30"
        >
            <td className="px-4 py-3">
                <div className="flex items-center gap-3">
                    <div className="h-10 w-14 shrink-0 overflow-hidden rounded-md border border-border bg-muted">
                        {interview.hero_image ? (
                            <img
                                src={`/storage/${interview.hero_image}`}
                                alt=""
                                loading="lazy"
                                className="h-full w-full object-cover"
                            />
                        ) : (
                            <div className="flex h-full w-full items-center justify-center">
                                <Mic className="h-4 w-4 text-champagne/40" />
                            </div>
                        )}
                    </div>
                    <div>
                        <div className="line-clamp-1 font-semibold">{interview.title}</div>
                        <div className="text-xs text-muted-foreground">
                            avec {interview.interviewee_name}
                        </div>
                    </div>
                </div>
            </td>
            <td className="hidden px-4 py-3 md:table-cell">
                <Badge variant="champagne">{interview.interviewee_role}</Badge>
            </td>
            <td className="hidden px-4 py-3 font-mono text-xs text-muted-foreground lg:table-cell">
                {date ? `${date.day} ${date.month} ${date.year}` : '—'}
            </td>
            <td className="px-4 py-3">
                {isPublished ? (
                    <Badge variant="win">
                        <Eye className="h-3 w-3" />
                        Publiée
                    </Badge>
                ) : (
                    <Badge variant="muted">
                        <EyeOff className="h-3 w-3" />
                        Brouillon
                    </Badge>
                )}
            </td>
            <td className="px-4 py-3">
                <div className="flex justify-end gap-1">
                    <Link
                        href={route('admin.interviews.edit', interview.id)}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-champagne"
                        aria-label="Éditer"
                    >
                        <Pencil className="h-4 w-4" />
                    </Link>
                    <button
                        onClick={() => onDelete(interview)}
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
