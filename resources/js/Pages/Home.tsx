import { Link } from '@inertiajs/react';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowRight, CalendarOff, ChevronRight, Newspaper } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { Button } from '@/Components/ui/Button';
import { Badge } from '@/Components/ui/Badge';
import { EmptyState } from '@/Components/site/EmptyState';
import { MatchCard } from '@/Components/site/MatchCard';
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
    flashMessage,
    weatherData,
}: HomeProps) {
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
            <section className="relative overflow-hidden">
                <div className="absolute inset-0 -z-10">
                    <div className="absolute inset-0 bg-grid-fade" />
                    <div className="absolute -top-40 left-1/2 h-[600px] w-[900px] -translate-x-1/2 rounded-full bg-crimson/20 blur-[120px]" />
                    <div className="absolute bottom-0 right-1/4 h-[300px] w-[500px] rounded-full bg-champagne/10 blur-[100px]" />
                </div>

                {flashMessage?.homemessage && (
                    <div className="pointer-events-none absolute inset-0 -z-[5] flex items-center justify-center overflow-hidden select-none">
                        <div className="flex flex-wrap justify-center gap-x-[0.35em] gap-y-[0.05em] px-4 text-center font-display font-black uppercase leading-[0.85] tracking-tighter text-crimson/[0.07] dark:text-crimson/[0.10]" style={{ fontSize: 'clamp(3.5rem, 14vw, 13rem)' }}>
                            {flashMessage.homemessage.split(/\s+/).filter(Boolean).map((word, i) => (
                                <motion.span
                                    key={`${word}_${i}`}
                                    initial={{ opacity: 0, y: 40, filter: 'blur(12px)' }}
                                    animate={{ opacity: 1, y: 0, filter: 'blur(0px)' }}
                                    transition={{ duration: 0.9, delay: 0.25 + i * 0.18, ease: [0.22, 1, 0.36, 1] }}
                                >
                                    {word}
                                </motion.span>
                            ))}
                        </div>
                    </div>
                )}

                <div className="mx-auto max-w-7xl px-4 py-16 lg:py-24">
                    <div className="grid gap-16 lg:grid-cols-[1.4fr_1fr] lg:items-center">
                        <div>
                            <motion.div
                                initial={{ opacity: 0, y: 8 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6 }}
                                className="border-b border-border pb-3 font-mono text-[11px] uppercase tracking-[0.18em] text-champagne"
                            >
                                Club de futsal · {city} · Est. 2011
                            </motion.div>

                            <motion.h1
                                initial={{ opacity: 0, y: 24 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.7, delay: 0.1 }}
                                className="mt-6 text-foreground"
                            >
                                <span className="block font-display text-display-2xl leading-[0.9]">
                                    Dina Kenitra
                                </span>
                                <span className="mt-1 block font-editorial text-4xl italic text-champagne sm:text-5xl lg:text-6xl">
                                    Futsal Club
                                </span>
                            </motion.h1>

                            <motion.p
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.25 }}
                                className="mt-8 max-w-lg text-lg leading-relaxed text-muted-foreground"
                            >
                                Depuis 2011, on porte les couleurs de Kénitra sur les parquets du
                                Maroc. Championnat, formation des jeunes, et une équipe qui vise
                                toujours plus haut.
                            </motion.p>

                            <motion.div
                                initial={{ opacity: 0, y: 16 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.6, delay: 0.4 }}
                                className="mt-10 flex flex-wrap items-center gap-3"
                            >
                                <Button asChild size="lg">
                                    <Link href="/calendar">
                                        Voir le calendrier
                                        <ArrowRight className="h-4 w-4" />
                                    </Link>
                                </Button>
                                <Button asChild size="lg" variant="outline">
                                    <Link href="/about">Notre histoire</Link>
                                </Button>
                            </motion.div>

                            {/* Bento asymmetric stats */}
                            <motion.div
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                transition={{ duration: 0.8, delay: 0.6 }}
                                className="mt-14 grid gap-3 sm:grid-cols-6"
                            >
                                <div className="rounded-2xl border border-champagne/20 bg-card p-5 sm:col-span-3 sm:row-span-2">
                                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        Depuis
                                    </div>
                                    <div className="mt-1 font-editorial text-6xl italic leading-none text-champagne sm:text-7xl">
                                        2011
                                    </div>
                                    <div className="mt-4 text-sm text-muted-foreground">
                                        Quinze ans de futsal à Kénitra, sans discontinuer.
                                    </div>
                                </div>
                                <div className="rounded-2xl border border-border bg-card p-4 sm:col-span-3">
                                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        Joueurs actifs
                                    </div>
                                    <div className="mt-2 font-display text-3xl font-bold tabular-nums">
                                        48+
                                    </div>
                                </div>
                                <div className="rounded-2xl border border-border bg-card p-4 sm:col-span-2">
                                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        Trophées
                                    </div>
                                    <div className="mt-2 font-display text-3xl font-bold tabular-nums">
                                        12
                                    </div>
                                </div>
                                <div className="rounded-2xl border border-border bg-card p-4 sm:col-span-1">
                                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        Matchs
                                    </div>
                                    <div className="mt-2 font-display text-2xl font-bold tabular-nums">
                                        350
                                    </div>
                                </div>
                            </motion.div>
                        </div>

                        {/* HERO SIDE : Next match spotlight */}
                        <motion.div
                            initial={{ opacity: 0, scale: 0.96 }}
                            animate={{ opacity: 1, scale: 1 }}
                            transition={{ duration: 0.7, delay: 0.3, ease: [0.22, 1, 0.36, 1] }}
                            className="relative"
                        >
                            <div className="glass-strong relative overflow-hidden rounded-3xl border border-champagne/20 p-8 shadow-2xl shadow-black/30">
                                <div className="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-crimson/20 blur-3xl" aria-hidden />
                                <div className="absolute -bottom-20 -left-16 h-56 w-56 rounded-full bg-champagne/15 blur-3xl" aria-hidden />

                                <div className="relative flex items-center justify-between">
                                    <Badge variant="live">
                                        <span className="live-dot" />
                                        Prochain match
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

                                {nextMatch ? (
                                    <div className="relative mt-8">
                                        <div className="grid grid-cols-3 items-center gap-4">
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
                                                    VS
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
                                                <span className="text-muted-foreground">Lieu</span>
                                                <span className="font-medium">{clubLocation}, {city}</span>
                                            </div>
                                        </div>

                                        <Button asChild variant="outline" className="mt-6 w-full">
                                            <Link href="/calendar">
                                                Tous les matchs à venir
                                                <ChevronRight className="h-4 w-4" />
                                            </Link>
                                        </Button>
                                    </div>
                                ) : (
                                    <div className="relative mt-8 flex flex-col items-center gap-4 py-12 text-center">
                                        <img
                                            src="/logo-dinakenitra.png"
                                            alt=""
                                            className="h-24 w-24 opacity-40"
                                        />
                                        <p className="text-sm text-muted-foreground">
                                            Aucun match programmé pour le moment.
                                        </p>
                                    </div>
                                )}
                            </div>
                        </motion.div>
                    </div>
                </div>
            </section>

            {/* ————————————————— LAST GAME + UPCOMING ————————————————— */}
            <section className="mx-auto max-w-7xl px-4 py-16">
                <SectionHeader
                    kicker="Le parquet"
                    title="Résultats & prochains rendez-vous"
                    action={{ label: 'Le calendrier complet', href: '/calendar' }}
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
                        title="Aucun match à afficher"
                        description="Aucun résultat récent ni rencontre programmée pour l'instant. Le calendrier sera mis à jour dès la reprise."
                        action={
                            <Button asChild variant="outline" size="sm">
                                <Link href="/calendar">
                                    Ouvrir le calendrier
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
                        kicker="Nouvelles du vestiaire"
                        title="Ce qui se passe au club"
                        action={{ label: 'Toutes les nouvelles', href: '/news' }}
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
                        kicker="En images"
                        title="Instants du club"
                        action={{ label: 'Toute la galerie', href: '/galleries' }}
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
                                <img
                                    src={`/storage/${photo.image}`}
                                    alt=""
                                    loading="lazy"
                                    className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-110"
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
                            <Badge variant="champagne">Rejoindre le club</Badge>
                            <h3 className="mt-4 font-display text-display-lg">
                                Envie de jouer avec nous ?
                            </h3>
                            <p className="mt-3 max-w-xl text-muted-foreground">
                                Détections ouvertes pour les équipes junior, féminine et senior masculine.
                                Remplis le formulaire de candidature avec ton CV. Notre staff te
                                recontacte pour un essai.
                            </p>
                        </div>
                        <div className="flex flex-wrap gap-3">
                            <Button asChild size="lg" variant="champagne">
                                <Link href="/rejoindre">
                                    Postuler pour rejoindre
                                    <ArrowRight className="h-4 w-4" />
                                </Link>
                            </Button>
                            <Button asChild size="lg" variant="outline">
                                <Link href="/contact">Nous contacter</Link>
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

function NewsCard({ article, index }: { article: Article; index: number }) {
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
                        <img
                            src={`/storage/${article.image}`}
                            alt=""
                            className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <Newspaper className="h-12 w-12 text-muted-foreground/30" />
                        </div>
                    )}
                    <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-card via-card/70 to-transparent" />
                    <div className="absolute left-4 top-4">
                        <Badge variant="default">Article</Badge>
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
                        Lire l'article
                        <ArrowRight className="h-3 w-3 transition-transform group-hover:translate-x-1" />
                    </div>
                </div>
            </Link>
        </motion.article>
    );
}
