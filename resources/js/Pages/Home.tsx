import { useEffect, useState } from 'react';
import { Link } from '@inertiajs/react';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowRight, Calendar, CalendarOff, ChevronRight, Newspaper, Timer, Trophy, Users } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import SiteLayout from '@/Layouts/SiteLayout';
import { Button } from '@/Components/ui/Button';
import { Badge } from '@/Components/ui/Badge';
import { EmptyState } from '@/Components/site/EmptyState';
import { MatchCard } from '@/Components/site/MatchCard';
import { SmartImage } from '@/Components/site/SmartImage';
import { TeamBadge } from '@/Components/site/TeamBadge';
import { formatMatchDate } from '@/lib/utils';
import type { Article, FlashMessage, Game, Photo, Video, WelcomeImage } from '@/types/models';

interface HomeProps {
    clubName: string;
    city: string;
    clubLocation: string;
    clubPrefix: string;
    lastGame: Game | null;
    nextGames: Game[];
    articles: Article[];
    videos: Video[];
    latestPhotos: Photo[];
    flashMessage: FlashMessage | null;
    welcomeImage: WelcomeImage | null;
    weatherData?: {
        main?: { temp?: number };
        weather?: { main?: string; description?: string }[];
    } | null;
}

export default function Home({
    clubName,
    city,
    clubLocation,
    clubPrefix,
    lastGame,
    nextGames,
    articles,
    latestPhotos,
    weatherData,
}: HomeProps) {
    const { t } = useTranslation('home');
    const nextMatch = nextGames?.[0];
    const upcomingRest = nextGames?.slice(1, 4) ?? [];
    const temp = weatherData?.main?.temp ? Math.round(weatherData.main.temp) : null;
    const weatherLabel = weatherData?.weather?.[0]?.main ?? null;

    return (
        <SiteLayout>
            <SEO
                title="Dina Kenitra Futsal Club"
                description={`Club de futsal de Kénitra depuis 2011. Prochains matchs, résultats, effectif, actualités et billetterie du ${clubName}.`}
            />

            {/* ————————————————— HERO ————————————————— */}
            <section className="relative isolate overflow-hidden border-b border-border">
                <div className="absolute inset-0 -z-10">
                    <div className="absolute inset-0 bg-grid-fade opacity-40" />
                </div>

                <div className="mx-auto max-w-7xl px-4 py-20 lg:py-32">
                    <div className="grid gap-16 lg:grid-cols-[1.4fr_1fr] lg:items-center">
                        <div>
                            <motion.h1
                                initial={{ opacity: 0, y: 24 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.7, delay: 0.1, ease: [0.22, 1, 0.36, 1] }}
                                className="text-foreground"
                            >
                                <span className="block font-display text-display-2xl leading-[0.9]">
                                    {t('hero.title_line1')}
                                </span>
                                <span className="mt-1 block font-editorial text-4xl italic text-champagne sm:text-5xl lg:text-6xl">
                                    {t('hero.title_line2')}
                                </span>
                            </motion.h1>

                            <motion.p
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.25 }}
                                className="mt-8 max-w-lg text-lg leading-relaxed text-muted-foreground"
                            >
                                {t('hero.subtitle')}
                            </motion.p>

                            <motion.div
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.4 }}
                                className="mt-10 flex flex-wrap items-center gap-3"
                            >
                                <Button asChild size="lg">
                                    <Link href="/calendar">
                                        {t('hero.cta_calendar')}
                                        <ArrowRight className="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button asChild size="lg" variant="outline">
                                    <Link href="/about">{t('hero.cta_history')}</Link>
                                </Button>
                            </motion.div>
                        </div>

                        {/* HERO SIDE : fallback chain, next match then featured article then club crest */}
                        {nextMatch ? (
                            <motion.div
                                initial={{ opacity: 0, y: 24 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.7, delay: 0.3, ease: [0.22, 1, 0.36, 1] }}
                                className="relative"
                            >
                                <div className="relative overflow-hidden rounded-2xl border border-border bg-card p-8">
                                    <div className="relative flex items-center justify-between">
                                        <Badge variant="live">
                                            <span className="live-dot" />
                                            {t('next_match.badge')}
                                        </Badge>
                                        {temp !== null && (
                                            <div className="text-right">
                                                <div className="font-mono text-xs uppercase tracking-widest text-muted-foreground">
                                                    {city}
                                                </div>
                                                <div className="font-display text-sm font-bold">
                                                    {temp}° {weatherLabel}
                                                </div>
                                            </div>
                                        )}
                                    </div>

                                    <div className="relative mt-8">
                                        <Countdown targetDate={nextMatch.match_date} />

                                        <div className="mt-6 grid grid-cols-3 items-center gap-4">
                                            <div className="flex flex-col items-center gap-3 text-center">
                                                <TeamBadge
                                                    team={nextMatch.homeTeam}
                                                    size="lg"
                                                    isClub={nextMatch.homeTeam?.name?.startsWith(clubPrefix) ?? false}
                                                />
                                                <div className="text-sm font-semibold">
                                                    {nextMatch.homeTeam?.name}
                                                </div>
                                            </div>

                                            <div className="flex flex-col items-center gap-2">
                                                <div className="font-mono text-xs uppercase tracking-widest text-champagne">
                                                    {t('next_match.vs')}
                                                </div>
                                                <div className="font-display text-3xl font-bold tabular-nums">
                                                    {formatMatchDate(nextMatch.match_date).time}
                                                </div>
                                                <div className="rounded-full bg-muted px-3 py-1 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                                    {formatMatchDate(nextMatch.match_date).day}{' '}
                                                    {formatMatchDate(nextMatch.match_date).month}
                                                </div>
                                            </div>

                                            <div className="flex flex-col items-center gap-3 text-center">
                                                <TeamBadge
                                                    team={nextMatch.awayTeam}
                                                    size="lg"
                                                    isClub={nextMatch.awayTeam?.name?.startsWith(clubPrefix) ?? false}
                                                />
                                                <div className="text-sm font-semibold">
                                                    {nextMatch.awayTeam?.name}
                                                </div>
                                            </div>
                                        </div>

                                        <div className="mt-8 rounded-xl border border-border bg-background/40 p-4 text-sm">
                                            <div className="flex items-center justify-between">
                                                <span className="text-muted-foreground">{t('next_match.venue')}</span>
                                                <span className="font-medium">{clubLocation}, {city}</span>
                                            </div>
                                        </div>

                                        <Button asChild variant="outline" className="mt-6 w-full">
                                            <Link href="/calendar">
                                                {t('next_match.all_upcoming')}
                                                <ChevronRight className="h-4 w-4" />
                                            </Link>
                                        </Button>
                                    </div>
                                </div>
                            </motion.div>
                        ) : articles?.[0] ? (
                            <FeaturedArticleCard article={articles[0]} />
                        ) : (
                            <ClubCrest />
                        )}
                    </div>
                </div>
            </section>

            {/* ————————————————— STATS ————————————————— */}
            <section className="mx-auto max-w-7xl px-4 py-16">
                <SectionHeader
                    kicker={t('stats.section_kicker')}
                    title={t('stats.section_title')}
                />

                <motion.div
                    initial={{ opacity: 0 }}
                    whileInView={{ opacity: 1 }}
                    viewport={{ once: true, margin: '-40px' }}
                    transition={{ duration: 0.7 }}
                    className="mt-10 grid gap-3 sm:grid-cols-6"
                >
                    <StatCard
                        icon={Calendar}
                        label={t('stats.since')}
                        value={t('stats.since_value')}
                        note={t('stats.since_note')}
                        className="sm:col-span-3 sm:row-span-2"
                        emphasized
                    />
                    <StatCard
                        icon={Users}
                        label={t('stats.players')}
                        value="48+"
                        className="sm:col-span-3"
                    />
                    <StatCard
                        icon={Trophy}
                        label={t('stats.trophies')}
                        value="12"
                        className="sm:col-span-2"
                    />
                    <StatCard
                        icon={Timer}
                        label={t('stats.matches')}
                        value="350"
                        className="sm:col-span-1"
                    />
                </motion.div>
            </section>

            {/* ————————————————— LAST GAME + UPCOMING ————————————————— */}
            <section className="mx-auto max-w-7xl px-4 py-16">
                <SectionHeader
                    kicker={t('results.kicker')}
                    title={t('results.title')}
                    action={{ label: t('results.action'), href: '/calendar' }}
                />

                {lastGame || upcomingRest.length > 0 ? (
                    <div className="mt-10 grid gap-5 lg:grid-cols-2">
                        {lastGame && (
                            <MatchCard
                                game={lastGame}
                                variant="result"
                                clubPrefix={clubPrefix}
                                venue={`${clubLocation}, ${city}`}
                            />
                        )}
                        {upcomingRest.slice(0, lastGame ? 1 : 2).map((g) => (
                            <MatchCard
                                key={g.id}
                                game={g}
                                variant="upcoming"
                                clubPrefix={clubPrefix}
                                venue={`${clubLocation}, ${city}`}
                            />
                        ))}
                    </div>
                ) : (
                    <EmptyState
                        className="mt-10"
                        icon={CalendarOff}
                        title={t('results.empty_title')}
                        description={t('results.empty_body')}
                        action={
                            <Button asChild variant="outline" size="sm">
                                <Link href="/calendar">
                                    {t('results.empty_action')}
                                    <ChevronRight className="h-4 w-4" />
                                </Link>
                            </Button>
                        }
                    />
                )}
            </section>

            {/* ————————————————— NEWS ————————————————— */}
            {articles?.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <SectionHeader
                        kicker={t('news.kicker')}
                        title={t('news.title')}
                        action={{ label: t('news.action'), href: '/news' }}
                    />

                    <div className="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {articles.slice(0, 3).map((article, i) => (
                            <NewsCard key={article.id} article={article} index={i} />
                        ))}
                    </div>
                </section>
            )}

            {/* ————————————————— PHOTOS ————————————————— */}
            {latestPhotos?.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <SectionHeader
                        kicker={t('photos.kicker')}
                        title={t('photos.title')}
                        action={{ label: t('photos.action'), href: '/galleries' }}
                    />

                    <div className="mt-10 grid grid-cols-2 gap-3 md:grid-cols-4">
                        {latestPhotos.slice(0, 8).map((photo, i) => (
                            <motion.div
                                key={photo.id}
                                initial={{ opacity: 0, scale: 0.95 }}
                                whileInView={{ opacity: 1, scale: 1 }}
                                viewport={{ once: true, margin: '-40px' }}
                                transition={{ duration: 0.4, delay: i * 0.04 }}
                                className="group relative aspect-square overflow-hidden rounded-xl border border-border bg-card"
                            >
                                <SmartImage
                                    src={`/storage/${photo.image}`}
                                    alt=""
                                    className="group-hover:scale-110"
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-obsidian/60 via-transparent to-transparent opacity-0 transition-opacity group-hover:opacity-100" />
                            </motion.div>
                        ))}
                    </div>
                </section>
            )}

            {/* ————————————————— CTA STRIP ————————————————— */}
            <section className="mx-auto max-w-7xl px-4 pb-16 pt-8">
                <motion.div
                    initial={{ opacity: 0, y: 24 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6 }}
                    className="relative overflow-hidden rounded-3xl border border-crimson/30 bg-card p-12"
                >
                    <div className="absolute -right-20 -top-20 h-96 w-96 rounded-full bg-crimson/30 blur-3xl" aria-hidden />
                    <div className="absolute inset-0 bg-noise opacity-[0.06]" aria-hidden />

                    <div className="relative grid gap-8 lg:grid-cols-[1fr_auto] lg:items-center">
                        <div>
                            <Badge variant="champagne">{t('cta.kicker')}</Badge>
                            <h3 className="mt-4 font-display text-display-lg">
                                {t('cta.title')}
                            </h3>
                            <p className="mt-3 max-w-xl text-muted-foreground">
                                {t('cta.subtitle')}
                            </p>
                        </div>
                        <div className="flex flex-wrap gap-3">
                            <Button asChild size="lg" variant="champagne">
                                <Link href="/rejoindre">
                                    {t('cta.apply')}
                                    <ArrowRight className="h-4 w-4" />
                                </Link>
                            </Button>
                            <Button asChild size="lg" variant="outline">
                                <Link href="/contact">{t('cta.contact')}</Link>
                            </Button>
                        </div>
                    </div>
                </motion.div>
            </section>
        </SiteLayout>
    );
}

function SectionHeader({
    kicker,
    title,
    action,
}: {
    kicker: string;
    title: string;
    action?: { label: string; href: string };
}) {
    return (
        <div className="flex flex-wrap items-end justify-between gap-4 border-b border-border pb-4">
            <div>
                <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                    {kicker}
                </div>
                <h2 className="mt-2 font-display text-display-lg text-foreground">{title}</h2>
            </div>
            {action && (
                <Link
                    href={action.href}
                    className="hidden shrink-0 items-center gap-1.5 border-b border-champagne/40 pb-1 text-sm font-medium text-champagne transition-colors hover:border-champagne sm:inline-flex"
                >
                    {action.label}
                    <ArrowRight className="h-3.5 w-3.5" />
                </Link>
            )}
        </div>
    );
}

function StatCard({
    icon: Icon,
    label,
    value,
    note,
    className,
    emphasized,
}: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string;
    note?: string;
    className?: string;
    emphasized?: boolean;
}) {
    return (
        <div
            className={`group relative overflow-hidden rounded-2xl border bg-card p-5 transition-colors ${
                emphasized
                    ? 'border-champagne/20 hover:border-champagne/50'
                    : 'border-border hover:border-crimson/40'
            } ${className ?? ''}`}
        >
            <div className="flex items-start gap-3">
                <div className="rounded-lg border border-border bg-background p-2">
                    <Icon className={`h-4 w-4 ${emphasized ? 'text-champagne' : 'text-crimson'}`} />
                </div>
                <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    {label}
                </div>
            </div>
            <div
                className={
                    emphasized
                        ? 'mt-4 font-editorial text-6xl italic leading-none text-champagne sm:text-7xl'
                        : 'mt-3 font-display text-3xl font-bold tabular-nums'
                }
            >
                {value}
            </div>
            {note && (
                <div className="mt-4 text-sm text-muted-foreground">{note}</div>
            )}
        </div>
    );
}

function Countdown({ targetDate }: { targetDate: string }) {
    const { t } = useTranslation('home');
    const target = new Date(targetDate).getTime();

    const compute = () => Math.max(0, target - Date.now());
    const [remaining, setRemaining] = useState<number>(compute);

    useEffect(() => {
        const id = window.setInterval(() => setRemaining(compute()), 1000);
        return () => window.clearInterval(id);
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [targetDate]);

    if (remaining <= 0) {
        return (
            <div className="rounded-xl border border-crimson/40 bg-crimson/10 px-4 py-3 text-center font-mono text-xs uppercase tracking-widest text-crimson">
                <span className="live-dot" /> {t('countdown.live')}
            </div>
        );
    }

    const days = Math.floor(remaining / 86_400_000);
    const hours = Math.floor((remaining % 86_400_000) / 3_600_000);
    const minutes = Math.floor((remaining % 3_600_000) / 60_000);
    const seconds = Math.floor((remaining % 60_000) / 1000);

    const parts: Array<{ value: number; label: string }> = [
        { value: days, label: t('countdown.days') },
        { value: hours, label: t('countdown.hours') },
        { value: minutes, label: t('countdown.minutes') },
        { value: seconds, label: t('countdown.seconds') },
    ];

    return (
        <div>
            <div className="mb-3 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                {t('countdown.kicker')}
            </div>
            <div className="grid grid-cols-4 gap-2">
                {parts.map((p) => (
                    <div
                        key={p.label}
                        className="rounded-lg border border-border bg-background/60 px-2 py-3 text-center"
                    >
                        <div className="font-display text-2xl font-bold tabular-nums leading-none">
                            {String(p.value).padStart(2, '0')}
                        </div>
                        <div className="mt-1 font-mono text-[9px] uppercase tracking-widest text-muted-foreground">
                            {p.label}
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}

function FeaturedArticleCard({ article }: { article: Article }) {
    const { t } = useTranslation('home');
    const date = formatMatchDate(article.created_at);
    return (
        <motion.article
            initial={{ opacity: 0, y: 24 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.7, delay: 0.3, ease: [0.22, 1, 0.36, 1] }}
            className="relative"
        >
            <div className="mb-4 flex items-center gap-3 font-mono text-[11px] uppercase tracking-[0.2em] text-champagne">
                <span className="h-px w-8 bg-champagne" />
                {t('featured.badge')}
            </div>

            <Link href={`/articles/${article.slug}`} className="group block">
                <div className="relative aspect-[16/10] overflow-hidden border border-border bg-muted">
                    {article.image ? (
                        <SmartImage
                            src={`/storage/${article.image}`}
                            alt=""
                            className="transition-transform duration-700 group-hover:scale-[1.03]"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <Newspaper className="h-16 w-16 text-muted-foreground/30" />
                        </div>
                    )}
                </div>

                <div className="mt-5">
                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {date.day} {date.month} {date.year}
                    </div>
                    <h3 className="mt-2 font-display text-2xl font-semibold leading-tight text-foreground transition-colors group-hover:text-crimson lg:text-3xl">
                        {article.title}
                    </h3>
                    <div className="mt-5 inline-flex items-center gap-1.5 border-b border-crimson pb-1 text-sm font-semibold text-crimson">
                        {t('featured.read')}
                        <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                    </div>
                </div>
            </Link>
        </motion.article>
    );
}

function ClubCrest() {
    return (
        <motion.div
            initial={{ opacity: 0, scale: 0.95 }}
            animate={{ opacity: 1, scale: 1 }}
            transition={{ duration: 0.9, delay: 0.3, ease: [0.22, 1, 0.36, 1] }}
            className="flex items-center justify-center py-10"
        >
            <img
                src="/logo-dinakenitra.png"
                alt="Dina Kenitra Futsal Club"
                className="h-64 w-64 lg:h-80 lg:w-80"
            />
        </motion.div>
    );
}

function NewsCard({ article, index }: { article: Article; index: number }) {
    const { t } = useTranslation('home');
    const date = formatMatchDate(article.created_at);
    return (
        <motion.article
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-60px' }}
            transition={{ duration: 0.5, delay: index * 0.1 }}
            className="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40"
        >
            <Link href={`/articles/${article.slug}`} className="block">
                <div className="relative aspect-[16/10] overflow-hidden bg-muted">
                    {article.image ? (
                        <SmartImage
                            src={`/storage/${article.image}`}
                            alt=""
                            className="group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <Newspaper className="h-12 w-12 text-muted-foreground/30" />
                        </div>
                    )}
                    <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-card via-card/70 to-transparent" />
                    <div className="absolute left-4 top-4">
                        <Badge variant="default">{t('article_badge')}</Badge>
                    </div>
                </div>
                <div className="p-6">
                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {date.day} {date.month} {date.year}
                    </div>
                    <h3 className="mt-2 font-display text-xl font-semibold leading-snug transition-colors group-hover:text-crimson">
                        {article.title}
                    </h3>
                    {article.description && (
                        <p className="mt-3 line-clamp-2 text-sm text-muted-foreground">
                            {article.description}
                        </p>
                    )}
                    <div className="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-crimson">
                        {t('read_article')}
                        <ArrowRight className="h-3 w-3 transition-transform group-hover:translate-x-1" />
                    </div>
                </div>
            </Link>
        </motion.article>
    );
}
