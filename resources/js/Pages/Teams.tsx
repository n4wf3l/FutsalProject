import { useMemo, useState } from 'react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { Trophy, User, Users, UserCog } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { SectionHeader } from '@/Components/site/Section';
import { EmptyState } from '@/Components/site/EmptyState';
import { PlayerCard } from '@/Components/site/PlayerCard';
import { SmartImage } from '@/Components/site/SmartImage';
import { Badge } from '@/Components/ui/Badge';
import { cn } from '@/lib/utils';
import type { Championship, Coach, Player, Staff } from '@/types/models';

interface TeamsProps {
    players: Player[];
    staff: Staff[];
    coach: Coach | null;
    championship: Championship | null;
}

const POSITION_MATCHERS = [
    { key: 'all', match: () => true },
    { key: 'gk', match: (p: Player) => /gardien|goal|gk/i.test(p.position) },
    { key: 'def', match: (p: Player) => /d[éeè]f/i.test(p.position) },
    { key: 'mid', match: (p: Player) => /milieu|mid/i.test(p.position) },
    { key: 'fwd', match: (p: Player) => /attaqu|forward|piv|fwd|ail/i.test(p.position) },
];

export default function Teams({ players, staff, coach, championship }: TeamsProps) {
    const { t, i18n } = useTranslation('pages');
    const [filter, setFilter] = useState('all');

    const filteredPlayers = useMemo(() => {
        const group = POSITION_MATCHERS.find((g) => g.key === filter) ?? POSITION_MATCHERS[0];
        return players.filter(group.match);
    }, [players, filter]);

    return (
        <SiteLayout>
            <SEO
                title={t('teams.seo_title')}
                description={t('teams.seo_description')}
            />

            <PageHeader
                kicker={t('teams.kicker')}
                kickerRight={new Date().toLocaleDateString(i18n.language, { day: '2-digit', month: 'long', year: 'numeric' })}
                title={t('teams.title')}
                subtitle={t('teams.subtitle')}
                variant="editorial"
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
                    {POSITION_MATCHERS.map((g) => {
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
                                {t(`teams.positions.${g.key}`)}
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
                        title={t('teams.empty_title')}
                        description={t('teams.empty_description')}
                    />
                )}
            </section>

            {/* Coach Spotlight */}
            {coach && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <SectionHeader kicker={t('teams.coach_kicker')} title={t('teams.coach_title')} />
                    <div className="mt-10 overflow-hidden rounded-3xl border border-champagne/20 bg-card">
                        <div className="grid gap-8 lg:grid-cols-[1fr_1.4fr]">
                            <div className="relative aspect-[3/4] overflow-hidden lg:aspect-auto">
                                {coach.photo ? (
                                    <SmartImage
                                        src={`/storage/${coach.photo}`}
                                        alt={`${coach.first_name} ${coach.last_name}`}
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
                                    {t('teams.coach_badge')}
                                </Badge>
                                <h3 className="font-display text-display-lg">
                                    <span className="block text-muted-foreground">
                                        {coach.first_name}
                                    </span>
                                    <span className="block text-foreground">{coach.last_name}</span>
                                </h3>

                                <div className="mt-8 grid grid-cols-2 gap-4 border-y border-border py-6">
                                    {coach.nationality && (
                                        <CoachStat label={t('teams.coach_stat_nationality')} value={coach.nationality} />
                                    )}
                                    {coach.birth_city && (
                                        <CoachStat label={t('teams.coach_stat_origin')} value={coach.birth_city} />
                                    )}
                                    {coach.coaching_since && (
                                        <CoachStat
                                            label={t('teams.coach_stat_since')}
                                            value={new Date(coach.coaching_since).getFullYear().toString()}
                                        />
                                    )}
                                    {coach.birth_date && (
                                        <CoachStat
                                            label={t('teams.coach_stat_age')}
                                            value={String(
                                                new Date().getFullYear() -
                                                    new Date(coach.birth_date).getFullYear()
                                            )}
                                        />
                                    )}
                                </div>

                                {coach.description && (
                                    <div
                                        className="mt-6 leading-relaxed text-muted-foreground [&>p]:mb-4 [&>p:last-child]:mb-0 [&>a]:text-crimson [&>a]:underline [&>ul]:my-4 [&>ul]:list-disc [&>ul]:pl-6"
                                        dangerouslySetInnerHTML={{ __html: coach.description }}
                                    />
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
                        kicker={t('teams.staff_kicker')}
                        title={t('teams.staff_title')}
                        description={t('teams.staff_description')}
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
                    <SmartImage
                        src={`/storage/${member.photo}`}
                        alt={`${member.first_name} ${member.last_name}`}
                        className="group-hover:scale-105"
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
