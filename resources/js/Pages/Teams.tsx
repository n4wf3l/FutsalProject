import { useMemo, useState } from 'react';
import { Head } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Trophy, User, Users, UserCog } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { SectionHeader } from '@/Components/site/Section';
import { EmptyState } from '@/Components/site/EmptyState';
import { PlayerCard } from '@/Components/site/PlayerCard';
import { Badge } from '@/Components/ui/Badge';
import { cn } from '@/lib/utils';
import type { Championship, Coach, Player, Staff } from '@/types/models';

interface TeamsProps {
    players: Player[];
    staff: Staff[];
    coach: Coach | null;
    championship: Championship | null;
}

const POSITION_GROUPS = [
    { key: 'all', label: 'Tous', match: () => true },
    { key: 'GK', label: 'Gardiens', match: (p: Player) => /gardien|goal|gk/i.test(p.position) },
    { key: 'DEF', label: 'Défenseurs', match: (p: Player) => /d[éeè]f/i.test(p.position) },
    { key: 'MID', label: 'Milieux', match: (p: Player) => /milieu|mid/i.test(p.position) },
    { key: 'FWD', label: 'Attaquants', match: (p: Player) => /attaqu|forward|piv|fwd|ail/i.test(p.position) },
];

export default function Teams({ players, staff, coach, championship }: TeamsProps) {
    const [filter, setFilter] = useState('all');

    const filteredPlayers = useMemo(() => {
        const group = POSITION_GROUPS.find((g) => g.key === filter) ?? POSITION_GROUPS[0];
        return players.filter(group.match);
    }, [players, filter]);

    return (
        <SiteLayout>
            <Head title="Équipe" />

            <PageHeader
                kicker="L'effectif"
                title="Notre équipe"
                subtitle="Découvrez les hommes qui portent les couleurs de Dina Kenitra FC — joueurs, staff et coachs qui font vivre le club."
            >
                {championship && (
                    <Badge variant="champagne" className="text-xs">
                        <Trophy className="h-3 w-3" />
                        {championship.name} · {championship.season}
                    </Badge>
                )}
            </PageHeader>

            {/* Filters */}
            <section className="mx-auto max-w-7xl px-4">
                <div className="flex flex-wrap items-center gap-2 border-b border-border pb-4">
                    {POSITION_GROUPS.map((g) => {
                        const count = players.filter(g.match).length;
                        return (
                            <button
                                key={g.key}
                                onClick={() => setFilter(g.key)}
                                className={cn(
                                    'group inline-flex items-center gap-2 rounded-full border px-4 py-1.5 text-sm font-medium transition-all',
                                    filter === g.key
                                        ? 'border-crimson bg-crimson/10 text-crimson'
                                        : 'border-border bg-transparent text-muted-foreground hover:border-crimson/40 hover:text-foreground'
                                )}
                            >
                                {g.label}
                                <span
                                    className={cn(
                                        'inline-flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 font-mono text-[10px] font-semibold',
                                        filter === g.key
                                            ? 'bg-crimson text-crimson-foreground'
                                            : 'bg-muted text-muted-foreground'
                                    )}
                                >
                                    {count}
                                </span>
                            </button>
                        );
                    })}
                </div>
            </section>

            {/* Players */}
            <section className="mx-auto max-w-7xl px-4 py-12">
                {filteredPlayers.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {filteredPlayers.map((p, i) => (
                            <PlayerCard key={p.id} player={p} index={i} />
                        ))}
                    </div>
                ) : (
                    <EmptyState
                        icon={Users}
                        title="Aucun joueur dans cette catégorie"
                        description="Change le filtre pour voir d'autres postes."
                    />
                )}
            </section>

            {/* Coach Spotlight */}
            {coach && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <SectionHeader kicker="Le coach" title="À la tête de l'équipe" />
                    <div className="mt-10 overflow-hidden rounded-3xl border border-champagne/20 bg-gradient-to-br from-card via-card to-champagne/10">
                        <div className="grid gap-8 lg:grid-cols-[1fr_1.4fr]">
                            <div className="relative aspect-[3/4] overflow-hidden lg:aspect-auto">
                                {coach.photo ? (
                                    <img
                                        src={`/storage/${coach.photo}`}
                                        alt={`${coach.first_name} ${coach.last_name}`}
                                        className="h-full w-full object-cover"
                                    />
                                ) : (
                                    <div className="flex h-full w-full items-center justify-center bg-muted">
                                        <UserCog className="h-32 w-32 text-muted-foreground/30" strokeWidth={1} />
                                    </div>
                                )}
                                <div className="absolute inset-0 bg-gradient-to-t from-card/80 via-transparent to-transparent" />
                            </div>
                            <div className="flex flex-col justify-center p-8 lg:p-12">
                                <Badge variant="champagne" className="mb-4 self-start">
                                    Head Coach
                                </Badge>
                                <h3 className="font-display text-display-lg">
                                    <span className="block text-muted-foreground">
                                        {coach.first_name}
                                    </span>
                                    <span className="block text-foreground">{coach.last_name}</span>
                                </h3>

                                <div className="mt-8 grid grid-cols-2 gap-4 border-y border-border py-6">
                                    {coach.nationality && (
                                        <CoachStat label="Nationalité" value={coach.nationality} />
                                    )}
                                    {coach.birth_city && (
                                        <CoachStat label="Origine" value={coach.birth_city} />
                                    )}
                                    {coach.coaching_since && (
                                        <CoachStat
                                            label="Coach depuis"
                                            value={new Date(coach.coaching_since).getFullYear().toString()}
                                        />
                                    )}
                                    {coach.birth_date && (
                                        <CoachStat
                                            label="Âge"
                                            value={String(
                                                new Date().getFullYear() -
                                                    new Date(coach.birth_date).getFullYear()
                                            )}
                                        />
                                    )}
                                </div>

                                {coach.description && (
                                    <p className="mt-6 leading-relaxed text-muted-foreground">
                                        {coach.description}
                                    </p>
                                )}
                            </div>
                        </div>
                    </div>
                </section>
            )}

            {/* Staff */}
            {staff.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <SectionHeader
                        kicker="Encadrement"
                        title="Le staff"
                        description="Les hommes de l'ombre qui rendent tout possible."
                    />
                    <div className="mt-10 grid gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {staff.map((member, i) => (
                            <StaffCard key={member.id} member={member} index={i} />
                        ))}
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}

function CoachStat({ label, value }: { label: string; value: string }) {
    return (
        <div>
            <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                {label}
            </div>
            <div className="mt-1 font-display text-lg font-semibold">{value}</div>
        </div>
    );
}

function StaffCard({ member, index }: { member: Staff; index: number }) {
    return (
        <motion.div
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.4, delay: (index % 8) * 0.04 }}
            className="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40"
        >
            <div className="relative aspect-square overflow-hidden bg-muted">
                {member.photo ? (
                    <img
                        src={`/storage/${member.photo}`}
                        alt={`${member.first_name} ${member.last_name}`}
                        loading="lazy"
                        className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center">
                        <User className="h-16 w-16 text-muted-foreground/30" strokeWidth={1} />
                    </div>
                )}
            </div>
            <div className="p-4">
                <div className="font-mono text-[10px] uppercase tracking-widest text-champagne">
                    {member.position}
                </div>
                <div className="mt-1 font-display font-semibold leading-tight">
                    {member.first_name} {member.last_name}
                </div>
            </div>
        </motion.div>
    );
}
