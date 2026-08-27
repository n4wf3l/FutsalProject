import { Head, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { CalendarDays, Trophy } from 'lucide-react';
import { useMemo } from 'react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { MatchCard } from '@/Components/site/MatchCard';
import { StandingsTable } from '@/Components/site/StandingsTable';
import { EmptyState } from '@/Components/site/EmptyState';
import { Badge } from '@/Components/ui/Badge';
import { cn, formatMatchDate } from '@/lib/utils';
import type { Championship, Game, Team } from '@/types/models';

interface Props {
    championship: Championship | null;
    games: Game[];
    teams: Team[];
    clubPrefix: string;
    filters: { team: string; date: string };
}

const DATE_FILTERS = [
    { key: 'all', label: 'Tous' },
    { key: 'upcoming', label: 'À venir' },
    { key: 'results', label: 'Résultats' },
];

const TEAM_FILTERS = [
    { key: 'club', label: 'Dina Kenitra' },
    { key: 'all', label: 'Toutes équipes' },
];

export default function Calendar({ championship, games, teams, clubPrefix, filters }: Props) {
    const groupedByMonth = useMemo(() => {
        const map = new Map<string, Game[]>();
        for (const g of games) {
            const d = new Date(g.match_date);
            const key = `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}`;
            if (!map.has(key)) map.set(key, []);
            map.get(key)!.push(g);
        }
        return Array.from(map.entries()).sort((a, b) => b[0].localeCompare(a[0]));
    }, [games]);

    const applyFilter = (key: 'team' | 'date', value: string) => {
        router.get(
            '/calendar',
            { ...filters, [key === 'team' ? 'team_filter' : 'date_filter']: value },
            { preserveScroll: true, preserveState: true, replace: true }
        );
    };

    const today = new Date();
    today.setHours(0, 0, 0, 0);

    return (
        <SiteLayout>
            <Head title="Calendrier" />

            <PageHeader
                kicker="Compétition"
                title="Calendrier & classement"
                subtitle="Tous les matchs de la saison — résultats, prochains coups d'envoi et classement du championnat."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Calendrier' }]}
            >
                {championship && (
                    <Badge variant="champagne">
                        <Trophy className="h-3 w-3" />
                        {championship.name} · {championship.season}
                    </Badge>
                )}
            </PageHeader>

            <section className="mx-auto max-w-7xl px-4 pb-16">
                <div className="grid gap-8 lg:grid-cols-[1.6fr_1fr]">
                    {/* Matches */}
                    <div>
                        {/* Filters */}
                        <div className="mb-8 flex flex-wrap items-center gap-3 border-b border-border pb-4">
                            <FilterGroup
                                label="Équipes"
                                options={TEAM_FILTERS}
                                value={filters.team}
                                onChange={(v) => applyFilter('team', v)}
                            />
                            <div className="hidden h-6 w-px bg-border sm:block" />
                            <FilterGroup
                                label="Période"
                                options={DATE_FILTERS}
                                value={filters.date}
                                onChange={(v) => applyFilter('date', v)}
                            />
                        </div>

                        {games.length === 0 ? (
                            <EmptyState
                                icon={CalendarDays}
                                title="Aucun match ne correspond à ce filtre"
                                description="Change de filtre pour explorer d'autres résultats."
                            />
                        ) : (
                            <div className="space-y-10">
                                {groupedByMonth.map(([monthKey, monthGames]) => (
                                    <MonthGroup
                                        key={monthKey}
                                        monthKey={monthKey}
                                        games={monthGames}
                                        clubPrefix={clubPrefix}
                                        today={today}
                                    />
                                ))}
                            </div>
                        )}
                    </div>

                    {/* Standings */}
                    <aside className="lg:sticky lg:top-24 lg:self-start">
                        <div className="mb-4 flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                            <span className="h-px w-8 bg-champagne" />
                            Classement
                        </div>
                        <StandingsTable teams={teams} clubPrefix={clubPrefix} />
                        <div className="mt-4 flex gap-4 rounded-lg border border-border bg-card p-3 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                            <span><span className="text-champagne">■</span> Podium</span>
                            <span><span className="text-crimson">■</span> Notre club</span>
                        </div>
                    </aside>
                </div>
            </section>
        </SiteLayout>
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
            <div className="flex gap-1 rounded-full border border-border bg-card p-1">
                {options.map((opt) => (
                    <button
                        key={opt.key}
                        onClick={() => onChange(opt.key)}
                        className={cn(
                            'rounded-full px-3 py-1 text-xs font-medium transition-colors',
                            value === opt.key
                                ? 'bg-crimson text-crimson-foreground shadow-glow-crimson'
                                : 'text-muted-foreground hover:text-foreground'
                        )}
                    >
                        {opt.label}
                    </button>
                ))}
            </div>
        </div>
    );
}

function MonthGroup({
    monthKey,
    games,
    clubPrefix,
    today,
}: {
    monthKey: string;
    games: Game[];
    clubPrefix: string;
    today: Date;
}) {
    const [year, month] = monthKey.split('-');
    const monthName = new Date(Number(year), Number(month) - 1, 1).toLocaleDateString('fr-FR', {
        month: 'long',
        year: 'numeric',
    });

    return (
        <motion.div
            initial={{ opacity: 0, y: 16 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5 }}
        >
            <div className="mb-4 flex items-center gap-3">
                <span className="font-display text-lg font-semibold capitalize text-foreground">
                    {monthName}
                </span>
                <span className="h-px flex-1 bg-border" />
                <span className="font-mono text-xs uppercase tracking-widest text-muted-foreground">
                    {games.length} match{games.length > 1 ? 's' : ''}
                </span>
            </div>
            <div className="grid gap-4 md:grid-cols-2">
                {games.map((g) => {
                    const played = new Date(g.match_date) < today;
                    return (
                        <MatchCard
                            key={g.id}
                            game={g}
                            variant={played ? 'result' : 'upcoming'}
                            clubPrefix={clubPrefix}
                        />
                    );
                })}
            </div>
        </motion.div>
    );
}
