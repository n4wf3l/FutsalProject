import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Pencil, Plus, Search, Trash2, User, Users } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Badge } from '@/Components/ui/Badge';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { cn } from '@/lib/utils';
import type { Player } from '@/types/models';

interface Props {
    players: Player[];
}

export default function PlayersIndex({ players }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Player | null>(null);

    const filtered = players.filter((p) => {
        if (!search) return true;
        const q = search.toLowerCase();
        return (
            p.first_name.toLowerCase().includes(q) ||
            p.last_name.toLowerCase().includes(q) ||
            p.position.toLowerCase().includes(q) ||
            String(p.number).includes(q)
        );
    });

    return (
        <AdminLayout title="Joueurs">
            <Head title="Joueurs" />

            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        Effectif
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        Joueurs <span className="text-muted-foreground">· {players.length}</span>
                    </h1>
                </div>
                <Button asChild size="lg">
                    <Link href="/players/create">
                        <Plus className="h-4 w-4" />
                        Nouveau joueur
                    </Link>
                </Button>
            </div>

            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input
                        placeholder="Rechercher par nom, poste, numéro…"
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
                    icon={Users}
                    title={search ? 'Aucun joueur trouvé' : 'Aucun joueur enregistré'}
                    description={
                        search
                            ? 'Essaie une autre recherche ou vide le filtre.'
                            : 'Ajoute ton premier joueur pour démarrer.'
                    }
                    action={
                        !search ? (
                            <Button asChild>
                                <Link href="/players/create">
                                    <Plus className="h-4 w-4" />
                                    Ajouter un joueur
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
                                <th className="w-16 px-4 py-3 text-center">#</th>
                                <th className="px-4 py-3 text-left">Joueur</th>
                                <th className="hidden px-4 py-3 text-left sm:table-cell">Poste</th>
                                <th className="hidden px-4 py-3 text-left md:table-cell">Nationalité</th>
                                <th className="hidden px-4 py-3 text-left lg:table-cell">Contrat jusqu'à</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((player, i) => (
                                <PlayerRow key={player.id} player={player} index={i} onDelete={setToDelete} />
                            ))}
                        </tbody>
                    </table>
                </div>
            )}

            <ConfirmDialog
                open={!!toDelete}
                title={
                    toDelete
                        ? `Supprimer ${toDelete.first_name} ${toDelete.last_name} ?`
                        : ''
                }
                description="Cette action est irréversible. Le joueur et sa photo seront supprimés."
                confirmLabel="Supprimer"
                variant="destructive"
                onCancel={() => setToDelete(null)}
                onConfirm={() => {
                    if (!toDelete) return;
                    router.delete(`/players/${toDelete.id}`, {
                        onSuccess: () => setToDelete(null),
                    });
                }}
            />
        </AdminLayout>
    );
}

function PlayerRow({
    player,
    index,
    onDelete,
}: {
    player: Player;
    index: number;
    onDelete: (p: Player) => void;
}) {
    return (
        <motion.tr
            initial={{ opacity: 0, y: 4 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: (index % 20) * 0.02 }}
            className="border-b border-border last:border-0 hover:bg-muted/30"
        >
            <td className="px-4 py-3 text-center">
                <div className="inline-flex h-8 min-w-8 items-center justify-center rounded-lg bg-crimson/10 px-2 font-mono text-sm font-bold text-crimson">
                    {player.number}
                </div>
            </td>
            <td className="px-4 py-3">
                <div className="flex items-center gap-3">
                    <div className="h-10 w-10 shrink-0 overflow-hidden rounded-full border border-border bg-muted">
                        {player.photo ? (
                            <img
                                src={`/storage/${player.photo}`}
                                alt=""
                                loading="lazy"
                                className="h-full w-full object-cover"
                            />
                        ) : (
                            <div className="flex h-full w-full items-center justify-center">
                                <User className="h-5 w-5 text-muted-foreground/40" />
                            </div>
                        )}
                    </div>
                    <div>
                        <div className="font-semibold">
                            {player.first_name} {player.last_name}
                        </div>
                        <div className="sm:hidden text-xs text-muted-foreground">{player.position}</div>
                    </div>
                </div>
            </td>
            <td className="hidden px-4 py-3 sm:table-cell">
                <Badge variant="muted">{player.position}</Badge>
            </td>
            <td className="hidden px-4 py-3 text-sm text-muted-foreground md:table-cell">
                {player.nationality}
            </td>
            <td className="hidden px-4 py-3 font-mono text-xs text-muted-foreground lg:table-cell">
                {new Date(player.contract_until).toLocaleDateString('fr-FR')}
            </td>
            <td className="px-4 py-3">
                <div className="flex justify-end gap-1">
                    <Link
                        href={`/players/${player.id}/edit`}
                        className={cn(
                            'inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground transition-colors hover:bg-muted hover:text-crimson'
                        )}
                        aria-label="Éditer"
                    >
                        <Pencil className="h-4 w-4" />
                    </Link>
                    <button
                        onClick={() => onDelete(player)}
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
