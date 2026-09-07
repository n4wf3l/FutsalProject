import { useState } from 'react';
import { Head, Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Pencil, Plus, Search, Shield, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import type { Team } from '@/types/models';

interface Props { teams: Team[]; }

export default function TeamsIndex({ teams }: Props) {
    const [search, setSearch] = useState('');
    const [toDelete, setToDelete] = useState<Team | null>(null);

    const filtered = teams.filter((t) => !search || t.name.toLowerCase().includes(search.toLowerCase()));

    return (
        <AdminLayout title="Équipes">
            <Head title="Équipes" />
            <div className="mb-6 flex flex-wrap items-center justify-between gap-4">
                <div>
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">Compétition</div>
                    <h1 className="mt-1 font-display text-3xl font-bold">Équipes <span className="text-muted-foreground">· {teams.length}</span></h1>
                </div>
                <Button asChild size="lg"><Link href="/manage-teams/create"><Plus className="h-4 w-4" />Nouvelle équipe</Link></Button>
            </div>
            <div className="mb-6 flex items-center gap-3">
                <div className="relative flex-1 max-w-sm">
                    <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                    <Input placeholder="Rechercher…" value={search} onChange={(e) => setSearch(e.target.value)} className="pl-10" />
                </div>
            </div>
            {filtered.length === 0 ? (
                <EmptyState icon={Shield} title="Aucune équipe" description="Ajoute les équipes du championnat." action={<Button asChild><Link href="/manage-teams/create"><Plus className="h-4 w-4" />Nouvelle équipe</Link></Button>} />
            ) : (
                <div className="overflow-hidden rounded-2xl border border-border bg-card">
                    <table className="w-full">
                        <thead>
                            <tr className="border-b border-border bg-muted/50 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                <th className="w-16 px-4 py-3"></th>
                                <th className="px-4 py-3 text-left">Équipe</th>
                                <th className="hidden px-4 py-3 text-right md:table-cell">MJ</th>
                                <th className="hidden px-4 py-3 text-right md:table-cell">Pts</th>
                                <th className="w-24 px-4 py-3 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            {filtered.map((t, i) => (
                                <motion.tr key={t.id} initial={{ opacity: 0, y: 4 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: (i % 20) * 0.02 }} className="border-b border-border last:border-0 hover:bg-muted/30">
                                    <td className="px-4 py-3">
                                        <div className="flex h-10 w-10 items-center justify-center overflow-hidden rounded-full border border-border bg-background">
                                            {t.logo_path ? <img src={`/storage/${t.logo_path}`} alt="" loading="lazy" className="h-full w-full object-contain p-1" /> : <Shield className="h-5 w-5 text-muted-foreground/40" />}
                                        </div>
                                    </td>
                                    <td className="px-4 py-3 font-semibold">{t.name}</td>
                                    <td className="hidden px-4 py-3 text-right font-mono text-xs md:table-cell">{t.games_played ?? 0}</td>
                                    <td className="hidden px-4 py-3 text-right font-mono text-sm font-bold md:table-cell">{t.points ?? 0}</td>
                                    <td className="px-4 py-3">
                                        <div className="flex justify-end gap-1">
                                            <Link href={`/manage-teams/${t.id}/edit`} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-muted hover:text-crimson"><Pencil className="h-4 w-4" /></Link>
                                            <button onClick={() => setToDelete(t)} className="inline-flex h-8 w-8 items-center justify-center rounded-lg text-muted-foreground hover:bg-plasma/10 hover:text-plasma"><Trash2 className="h-4 w-4" /></button>
                                        </div>
                                    </td>
                                </motion.tr>
                            ))}
                        </tbody>
                    </table>
                </div>
            )}
            <ConfirmDialog open={!!toDelete} title={toDelete ? `Supprimer ${toDelete.name} ?` : ''} description="L'équipe et son logo seront supprimés." confirmLabel="Supprimer" variant="destructive" onCancel={() => setToDelete(null)} onConfirm={() => { if (!toDelete) return; router.delete(`/manage-teams/${toDelete.id}`, { onSuccess: () => setToDelete(null) }); }} />
        </AdminLayout>
    );
}
