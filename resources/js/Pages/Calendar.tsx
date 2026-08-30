import { router } from '@inertiajs/react';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { CalendarDays, Trophy } from 'lucide-react';
import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { MatchCard } from '@/Components/site/MatchCard';
import { EmptyState } from '@/Components/site/EmptyState';
import { Badge } from '@/Components/ui/Badge';
import { cn } from '@/lib/utils';
import type { Championship, Game, Team } from '@/types/models';

interface Props {
    championship: Championship | null;
    games: Game[];
    teams: Team[];
    clubPrefix: string;
    filters: { team: string; date: string };
}

const DATE_FILTER_KEYS = ['all', 'upcoming', 'results'] as const;
const TEAM_FILTER_KEYS = ['club', 'all'] as const;

export default function Calendar({ championship, games, teams, clubPrefix, filters }: Props) {
    const { t, i18n } = useTranslation(['pages', 'nav']);
    const dateFilters = useMemo(
        () =>
            DATE_FILTER_KEYS.map((k) => ({
                key: k,
                label: t(`pages:calendar.filter_period_${k === 'results' ? 'results' : k}`),
            })),
        [t]
    );
    const teamFilters = useMemo(
        () =>
            TEAM_FILTER_KEYS.map((k) => ({
                key: k,
                label: t(`pages:calendar.filter_team_${k}`),
            })),
        [t]
    );
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
            <SEO
                title={t('pages:calendar.seo_title')}
                description={t('pages:calendar.seo_description')}
                jsonLd={{
                    '@context': 'https://schema.org',
                    '@type': 'ItemList',
                    itemListElement: games.slice(0, 20).map((g, i) => ({
                        '@type': 'ListItem',
                        position: i + 1,
                        item: {
                            '@type': 'SportsEvent',
                            name: `${g.homeTeam?.name ?? 'TBD'} vs ${g.awayTeam?.name ?? 'TBD'}`,
                            startDate: g.match_date,
                            sport: 'Futsal',
                            eventStatus: new Date(g.match_date) < today
                                ? 'https://schema.org/EventCompleted'
                                : 'https://schema.org/EventScheduled',
                            location: {
                                '@type': 'Place',
                                name: 'Complexe Sportif Municipal',
                                address: {
                                    '@type': 'PostalAddress',
                                    addressLocality: 'Kénitra',
                                    addressCountry: 'MA',
                                },
                            },
                            homeTeam: {
                                '@type': 'SportsTeam',
                                name: g.homeTeam?.name ?? 'TBD',
                                sport: 'Futsal',
                            },
                            awayTeam: {
                                '@type': 'SportsTeam',
                                name: g.awayTeam?.name ?? 'TBD',
                                sport: 'Futsal',
                            },
                            ...(g.home_score !== null && g.away_score !== null
                                ? {
                                      homeTeamScore: g.home_score,
                                      awayTeamScore: g.away_score,
                                  }
                                : {}),
                        },
                    })),
                }}
            />

            <PageHeader
                kicker={t('pages:calendar.kicker')}
                title={t('pages:calendar.title')}
                subtitle={t('pages:calendar.subtitle')}
                breadcrumb={[{ label: t('nav:items.home'), href: '/' }, { label: t('pages:calendar.breadcrumb') }]}
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
                                label={t('pages:calendar.filter_team_label')}
                                options={teamFilters}
                                value={filters.team}
                                onChange={(v) => applyFilter('team', v)}
                            />
                            <div className="hidden h-6 w-px bg-border sm:block" />
                            <FilterGroup
                                label={t('pages:calendar.filter_period_label')}
                                options={dateFilters}
                                value={filters.date}
                                onChange={(v) => applyFilter('date', v)}
                            />
                        </div>

                        {games.length === 0 ? (
                            <EmptyState
                                icon={CalendarDays}
                                title={t('pages:calendar.empty_title')}
                                description={t('pages:calendar.empty_description')}
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
                                        locale={i18n.language}
                                        countLabel={t('pages:calendar.match_count', { count: monthGames.length })}
                                    />
                                ))}
                            </div>
                        )}
                    </div>

                    <aside className="lg:sticky lg:top-24 lg:self-start">
                        <div className="mb-4 font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                            {t('pages:calendar.side_kicker')}
                        </div>
                        <div className="rounded-2xl border border-champagne/20 bg-card p-6">
                            <div className="inline-flex items-center gap-2 rounded-full border border-champagne/30 bg-champagne/10 px-3 py-1 font-mono text-[10px] font-semibold uppercase tracking-widest text-champagne">
                                <Trophy className="h-3 w-3" />
                                {t('pages:calendar.side_badge')}
                            </div>
                            <h3 className="mt-4 font-editorial text-2xl font-medium leading-snug">
                                {t('pages:calendar.side_title')}
                            </h3>
                            <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                                {t('pages:calendar.side_body')}
                            </p>
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
    locale,
    countLabel,
}: {
    monthKey: string;
    games: Game[];
    clubPrefix: string;
    today: Date;
    locale: string;
    countLabel: string;
}) {
    const [year, month] = monthKey.split('-');
    const monthName = new Date(Number(year), Number(month) - 1, 1).toLocaleDateString(locale, {
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
                    {countLabel}
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
