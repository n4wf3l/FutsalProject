import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Eye, FileText, Inbox, Search, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Input } from '@/Components/ui/Input';
import { Badge } from '@/Components/ui/Badge';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { formatMatchDate, cn } from '@/lib/utils';
import type { PlayerApplication } from '@/types/models';

interface Props {
    applications: PlayerApplication[];
    statuses: string[];
    categories: Record<string, string>;
    filters: { status: string; category: string };
    counts: {
        total: number;
        pending: number;
        contacted: number;
        accepted: number;
        rejected: number;
    };
}

const STATUS_LABELS: Record<string, string> = {
    pending: 'En attente',
    reviewed: 'Étudiée',
    contacted: 'Contactée',
    accepted: 'Acceptée',
    rejected: 'Refusée',
};

const STATUS_VARIANT: Record<string, 'soon' | 'muted' | 'default' | 'win' | 'live'> = {
    pending: 'soon',
    reviewed: 'muted',
    contacted: 'default',
    accepted: 'win',
    rejected: 'live',
};

export default function AdminApplicationsIndex({
    applications,
    statuses,
    categories,
    filters,
    counts,
}: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<PlayerApplication | null>(null);

    const setFilter = (key: 'status' | 'category', value: string) => {
        router.get(
            route('admin.applications.index'),
            { ...filters, [key]: value },
            { preserveScroll: true, preserveState: true, replace: true }
        );
    };

    const filtered = applications.filter((a) => {
        if (!search) return true;
        const q = search.toLowerCase();
        return (
            a.first_name.toLowerCase().includes(q) ||
            a.last_name.toLowerCase().includes(q) ||
            a.email.toLowerCase().includes(q) ||
            (a.current_club ?? '').toLowerCase().includes(q)
        );
    });

    return (
        <AdminLayout title="Candidatures">
            <Head title="Candidatures" />

            <div className="mb-6">
                <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                    Détections
                </div>
                <h1 className="mt-1 font-display text-3xl font-bold">
                    Candidatures <span className="text-muted-foreground">· {counts.total}</span>
                </h1>
            </div>

            {/* Stats */}
            <div className="mb-6 grid gap-3 sm:grid-cols-4">
                <StatCard label="En attente" value={counts.pending} tone="soon" />
                <StatCard label="Contactées" value={counts.contacted} tone="default" />
                <StatCard label="Acceptées" value={counts.accepted} tone="win" />
                <StatCard label="Refusées" value={counts.rejected} tone="live" />
            </div>

            {/* Filters */}
            <div className="mb-6 flex flex-wrap items-center gap-3 border-b border-border pb-4">
                <div className="relative max-w-xs flex-1">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        placeholder="Nom, email, club…"
                        value={search}
                        onChange={(e) => setSearch(e.target.value)}
                        className="pl-10"
                    />
                </div>
                <FilterGroup
                    label="Statut"
                    options={[{ key: '', label: 'Tous' }, ...statuses.map((s) => ({ key: s, label: STATUS_LABELS[s] ?? s }))]}
                    value={filters.status}
                    onChange={(v) => setFilter('status', v)}
                />
                <FilterGroup
                    label="Équipe"
                    options={[{ key: '', label: 'Toutes' }, ...Object.entries(categories).map(([k, l]) => ({ key: k, label: l }))]}
                    value={filters.category}
                    onChange={(v) => setFilter('category', v)}
                />
            </div>

            {filtered.length === 0 ? (
                <EmptyState
                    icon={Inbox}
                    title={search || filters.status || filters.category ? 'Aucune candidature ne correspond' : 'Aucune candidature reçue'}
                    description={
                        search || filters.status || filters.category
                            ? 'Change les filtres ou vide la recherche.'
                            : 'Les candidatures des joueurs apparaîtront ici dès qu\'elles seront envoyées.'
                    }
                />
            ) : (
                <div className="overflow-hidden rounded-2xl border border-border bg-card">
                    <table className="w-full">
                        <thead>
                            <tr className="border-b border-border bg-muted/50 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                <th className="px-4 py-3 text-left">Candidat</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Équipe</th>
                                <th className="hidden px-4 py-3 text-left lg:table-cell">Reçue</th>
                                <th className="px-4 py-3 text-left">Statut</th>
                                <th className="hidden px-4 py-3 text-center lg:table-cell">CV</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((app, i) => (
                                <ApplicationRow
                                    key={app.id}
                                    application={app}
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
                title={toDelete ? `Supprimer la candidature de ${toDelete.first_name} ${toDelete.last_name} ?` : ''}
                description="La candidature et le CV associé seront supprimés définitivement."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(route('admin.applications.destroy', toDelete.id), {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />
        </AdminLayout>
    );
}

function StatCard({
    label,
    value,
    tone,
}: {
    label: string;
    value: number;
    tone: 'soon' | 'default' | 'win' | 'live';
}) {
    const toneMap = {
        soon: 'text-amber border-amber/30',
        default: 'text-crimson border-crimson/30',
        win: 'text-mint border-mint/30',
        live: 'text-plasma border-plasma/30',
    };
    return (
        <div className={cn('rounded-xl border bg-card p-4', toneMap[tone])}>
            <div className="font-mono text-[10px] font-semibold uppercase tracking-widest opacity-80">
                {label}
            </div>
            <div className="mt-2 font-display text-3xl font-bold tabular-nums">{value}</div>
        </div>
    );
}

function FilterGroup({
    label,
    options,
    value,
    onChange,
}: {
    label: string;
    options: { key: string; label: string }[];
    value: string;
    onChange: (v: string) => void;
}) {
    return (
        <div className="flex items-center gap-2">
            <span className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                {label}
            </span>
            <select
                value={value}
                onChange={(e) => onChange(e.target.value)}
                className="h-9 rounded-lg border border-border bg-card px-3 text-sm focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20"
            >
                {options.map((o) => (
                    <option key={o.key} value={o.key}>
                        {o.label}
                    </option>
                ))}
            </select>
        </div>
    );
}

function ApplicationRow({
    application,
    index,
    onDelete,
}: {
    application: PlayerApplication;
    index: number;
    onDelete: (a: PlayerApplication) => void;
}) {
    const date = formatMatchDate(application.created_at);
    const status = application.status;

    return (
        <motion.tr
            initial={{ opacity: 0, y: 4 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: (index % 20) * 0.02 }}
            className="border-b border-border last:border-0 hover:bg-muted/30"
        >
            <td className="px-4 py-3">
                <Link href={route('admin.applications.show', application.id)} className="group">
                    <div className="font-semibold transition-colors group-hover:text-crimson">
                        {application.first_name} {application.last_name}
                    </div>
                    <div className="text-xs text-muted-foreground">{application.email}</div>
                </Link>
            </td>
            <td className="hidden px-4 py-3 md:table-cell">
                <Badge variant="champagne">
                    {application.category === 'junior'
                        ? 'Junior'
                        : application.category === 'feminine'
                          ? 'Féminine'
                          : 'Senior M.'}
                </Badge>
            </td>
            <td className="hidden px-4 py-3 font-mono text-xs text-muted-foreground lg:table-cell">
                {date.day} {date.month} {date.year}
            </td>
            <td className="px-4 py-3">
                <Badge variant={STATUS_VARIANT[status] ?? 'muted'}>
                    {STATUS_LABELS[status] ?? status}
                </Badge>
            </td>
            <td className="hidden px-4 py-3 text-center lg:table-cell">
                {application.cv_path ? (
                    <a
                        href={`/storage/${application.cv_path}`}
                        target="_blank"
                        rel="noopener noreferrer"
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-champagne hover:bg-champagne/10"
                        title="Ouvrir le CV"
                    >
                        <FileText className="h-4 w-4" />
                    </a>
                ) : (
                    <span className="text-xs text-muted-foreground/40">—</span>
                )}
            </td>
            <td className="px-4 py-3">
                <div className="flex justify-end gap-1">
                    <Link
                        href={route('admin.applications.show', application.id)}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-muted hover:text-crimson"
                        aria-label="Voir"
                    >
                        <Eye className="h-4 w-4" />
                    </Link>
                    <button
                        onClick={() => onDelete(application)}
                        className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-plasma/10 hover:text-plasma"
                        aria-label="Supprimer"
                    >
                        <Trash2 className="h-4 w-4" />
                    </button>
                </div>
            </td>
        </motion.tr>
    );
}
