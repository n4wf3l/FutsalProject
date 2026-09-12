import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import { motion } from 'framer-motion';
import { SEO } from '@/Components/SEO';
import { breadcrumbLd } from '@/lib/seo';
import { Award, ChevronRight, Trophy } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Badge } from '@/Components/ui/Badge';
import { cn } from '@/lib/utils';
import type { Season } from '@/types/models';

interface Props {
    seasons: Season[];
}

// Badges are stored as free text but we color-code a known set consistently.
// Maps to Badge variants defined in Components/ui/Badge.tsx.
function badgeVariant(badge: string | null): 'champagne' | 'default' | 'live' | 'win' | 'soon' | null {
    if (!badge) return null;
    const low = badge.toLowerCase();
    if (low.includes('champion') && !low.includes('vice')) return 'champagne';
    if (low.includes('promu')) return 'win';
    if (low.includes('relégué') || low.includes('relegue')) return 'live';
    if (low.includes('vice')) return 'default';
    if (low.includes('en cours')) return 'soon';
    return 'default';
}

export default function Historique({ seasons }: Props) {
    const { t } = useTranslation(['pages', 'nav']);

    const crumbs = [
        { label: t('nav:items.home'), href: '/' },
        { label: t('pages:historique.breadcrumb') },
    ];

    const totalYears = useMemo(() => {
        if (seasons.length === 0) return 0;
        const years = seasons.map((s) => s.season_start_year);
        return Math.max(...years) - Math.min(...years) + 1;
    }, [seasons]);

    const trophies = useMemo(() => seasons.filter((s) => badgeVariant(s.badge) === 'champagne'), [seasons]);
    const bestD1 = useMemo(() => {
        const d1 = seasons.filter((s) => /d1/i.test(s.division) && s.position !== null);
        if (d1.length === 0) return null;
        return d1.reduce((best, s) => (s.position! < best ? s.position! : best), Infinity);
    }, [seasons]);

    return (
        <SiteLayout>
            <SEO
                title={t('pages:historique.seo_title')}
                description={t('pages:historique.seo_description')}
                jsonLd={breadcrumbLd(crumbs)}
            />

            <PageHeader
                kicker={t('pages:historique.kicker')}
                title={t('pages:historique.title')}
                subtitle={t('pages:historique.subtitle')}
                breadcrumb={crumbs}
                variant="editorial"
            />

            {/* Stats band */}
            <section className="mx-auto max-w-7xl px-4 pb-8">
                <div className="grid gap-3 sm:grid-cols-3">
                    <StatCard
                        icon={Trophy}
                        label={t('pages:historique.stat_years')}
                        value={String(totalYears)}
                    />
                    <StatCard
                        icon={Award}
                        label={t('pages:historique.stat_titles')}
                        value={String(trophies.length)}
                    />
                    <StatCard
                        icon={ChevronRight}
                        label={t('pages:historique.stat_best_d1')}
                        value={bestD1 !== null && bestD1 !== Infinity ? `${bestD1}${bestD1 === 1 ? 'er' : 'e'}` : '—'}
                    />
                </div>
            </section>

            {/* Palmarès */}
            {trophies.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 pb-10">
                    <div className="rounded-2xl border border-champagne/30 bg-card p-6 sm:p-8">
                        <div className="mb-4 flex items-center gap-2 font-mono text-[10px] font-semibold uppercase tracking-[0.28em] text-champagne">
                            <Trophy className="h-3.5 w-3.5" />
                            {t('pages:historique.palmares_kicker')}
                        </div>
                        <ul className="space-y-2">
                            {trophies.map((s) => (
                                <li
                                    key={s.id}
                                    className="flex items-center justify-between border-b border-border pb-2 last:border-0 last:pb-0"
                                >
                                    <span className="font-display text-base font-semibold sm:text-lg">
                                        {s.division}
                                    </span>
                                    <span className="font-mono text-xs uppercase tracking-widest text-muted-foreground">
                                        {s.season_label}
                                    </span>
                                </li>
                            ))}
                        </ul>
                    </div>
                </section>
            )}

            {/* Seasons table */}
            <section className="mx-auto max-w-7xl px-4 pb-16">
                {seasons.length === 0 ? (
                    <EmptyState
                        icon={Trophy}
                        title={t('pages:historique.empty_title')}
                        description={t('pages:historique.empty_description')}
                    />
                ) : (
                    <>
                        {/* Mobile: list of cards */}
                        <div className="space-y-3 md:hidden">
                            {seasons.map((s, i) => (
                                <SeasonCard key={s.id} season={s} index={i} />
                            ))}
                        </div>

                        {/* Desktop: table */}
                        <div className="hidden overflow-hidden rounded-2xl border border-border bg-card md:block">
                            <table className="w-full">
                                <thead>
                                    <tr className="border-b border-border bg-muted/40 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        <th className="px-4 py-3 text-left">{t('pages:historique.col_season')}</th>
                                        <th className="px-4 py-3 text-left">{t('pages:historique.col_division')}</th>
                                        <th className="px-4 py-3 text-left">{t('pages:historique.col_position')}</th>
                                        <th className="px-4 py-3 text-left">{t('pages:historique.col_cup')}</th>
                                        <th className="px-4 py-3 text-left">{t('pages:historique.col_coach')}</th>
                                        <th className="px-4 py-3 text-left">{t('pages:historique.col_notes')}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {seasons.map((s, i) => (
                                        <motion.tr
                                            key={s.id}
                                            initial={{ opacity: 0, y: 4 }}
                                            whileInView={{ opacity: 1, y: 0 }}
                                            viewport={{ once: true }}
                                            transition={{ delay: (i % 12) * 0.02 }}
                                            className="border-b border-border last:border-0 hover:bg-muted/20"
                                        >
                                            <td className="px-4 py-3 align-top">
                                                <div className="flex flex-col gap-1">
                                                    <span className="font-mono text-sm font-semibold text-foreground">
                                                        {s.season_label}
                                                    </span>
                                                    {s.badge && (
                                                        <span>
                                                            <Badge variant={badgeVariant(s.badge) ?? 'default'} className="text-[10px]">
                                                                {s.badge}
                                                            </Badge>
                                                        </span>
                                                    )}
                                                </div>
                                            </td>
                                            <td className="px-4 py-3 align-top font-display text-sm font-semibold">
                                                {s.division}
                                            </td>
                                            <td className="px-4 py-3 align-top text-sm">
                                                {s.position_label ?? (s.position !== null ? `${s.position}${s.position === 1 ? 'er' : 'e'}` : '—')}
                                            </td>
                                            <td className="px-4 py-3 align-top text-sm text-muted-foreground">
                                                {s.cup_result ?? '—'}
                                            </td>
                                            <td className="px-4 py-3 align-top text-sm text-muted-foreground">
                                                {s.coach ?? '—'}
                                            </td>
                                            <td className="px-4 py-3 align-top text-sm text-muted-foreground">
                                                {s.notes ?? '—'}
                                            </td>
                                        </motion.tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </>
                )}
            </section>
        </SiteLayout>
    );
}

function StatCard({
    icon: Icon,
    label,
    value,
}: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string;
}) {
    return (
        <div className="rounded-2xl border border-border bg-card p-5">
            <div className="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-champagne">
                <Icon className="h-3 w-3" />
                {label}
            </div>
            <div className="mt-2 font-display text-3xl font-semibold text-foreground sm:text-4xl">
                {value}
            </div>
        </div>
    );
}

function SeasonCard({ season, index }: { season: Season; index: number }) {
    const { t } = useTranslation('pages');
    return (
        <motion.article
            initial={{ opacity: 0, y: 8 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true }}
            transition={{ delay: (index % 8) * 0.03 }}
            className="rounded-2xl border border-border bg-card p-5"
        >
            <div className="flex items-baseline justify-between gap-3">
                <span className="font-mono text-sm font-semibold text-foreground">
                    {season.season_label}
                </span>
                <span className={cn('font-display text-sm font-semibold')}>
                    {season.division}
                </span>
            </div>
            {season.badge && (
                <div className="mt-2">
                    <Badge variant={badgeVariant(season.badge) ?? 'default'} className="text-[10px]">
                        {season.badge}
                    </Badge>
                </div>
            )}
            <dl className="mt-4 grid grid-cols-2 gap-3 border-t border-border pt-4 text-xs">
                <div>
                    <dt className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {t('historique.col_position')}
                    </dt>
                    <dd className="mt-1 font-display text-sm font-semibold">
                        {season.position_label ?? (season.position !== null ? `${season.position}${season.position === 1 ? 'er' : 'e'}` : '—')}
                    </dd>
                </div>
                <div>
                    <dt className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {t('historique.col_cup')}
                    </dt>
                    <dd className="mt-1 font-display text-sm font-semibold">
                        {season.cup_result ?? '—'}
                    </dd>
                </div>
                {season.coach && (
                    <div className="col-span-2">
                        <dt className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                            {t('historique.col_coach')}
                        </dt>
                        <dd className="mt-1 text-sm">{season.coach}</dd>
                    </div>
                )}
                {season.notes && (
                    <div className="col-span-2">
                        <dt className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                            {t('historique.col_notes')}
                        </dt>
                        <dd className="mt-1 text-sm text-muted-foreground">{season.notes}</dd>
                    </div>
                )}
            </dl>
        </motion.article>
    );
}
