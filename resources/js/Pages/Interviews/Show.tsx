import { Link } from '@inertiajs/react';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowLeft, Calendar, Mic, Play, Quote, Share2, User } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { Badge } from '@/Components/ui/Badge';
import { Button } from '@/Components/ui/Button';
import { Monogram, Ornament } from '@/Components/site/Ornament';
import { formatMatchDate } from '@/lib/utils';
import type { Interview } from '@/types/models';

interface Props {
    interview: Interview;
    related: Interview[];
}

function extractEmbed(url: string | null): string | null {
    if (!url) return null;
    const yt = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([\w-]{11})/);
    if (yt) return `https://www.youtube.com/embed/${yt[1]}`;
    const vm = url.match(/vimeo\.com\/(\d+)/);
    if (vm) return `https://player.vimeo.com/video/${vm[1]}`;
    return null;
}

export default function InterviewShow({ interview, related }: Props) {
    const date = interview.published_at ? formatMatchDate(interview.published_at) : null;
    const embed = extractEmbed(interview.video_url);

    const plainDescription = (interview.excerpt ?? interview.content ?? '')
        .replace(/<[^>]+>/g, '')
        .replace(/\s+/g, ' ')
        .trim()
        .slice(0, 220);

    const origin = typeof window !== 'undefined' ? window.location.origin : '';

    const interviewLd = {
        '@context': 'https://schema.org',
        '@type': 'NewsArticle',
        headline: interview.title,
        description: plainDescription,
        image: interview.hero_image ? [`${origin}/storage/${interview.hero_image}`] : undefined,
        datePublished: interview.published_at ?? interview.created_at,
        dateModified: interview.updated_at,
        author: {
            '@type': 'Person',
            name: interview.interviewee_name,
            jobTitle: interview.interviewee_role,
            ...(interview.interviewee_affiliation
                ? { affiliation: { '@type': 'Organization', name: interview.interviewee_affiliation } }
                : {}),
        },
        publisher: {
            '@type': 'Organization',
            name: 'Dina Kenitra FC',
            logo: { '@type': 'ImageObject', url: `${origin}/logo-dinakenitra.png` },
        },
        articleSection: 'Interviews · La Voix du Futsal',
        mainEntityOfPage: {
            '@type': 'WebPage',
            '@id': typeof window !== 'undefined' ? window.location.href : '',
        },
    };

    return (
        <SiteLayout>
            <SEO
                title={interview.title}
                description={plainDescription || `Interview de ${interview.interviewee_name} — La Voix du Futsal.`}
                image={interview.hero_image}
                type="article"
                publishedAt={interview.published_at ?? interview.created_at}
                modifiedAt={interview.updated_at}
                author={interview.interviewee_name}
                section={`Interviews · ${interview.interviewee_role}`}
                jsonLd={interviewLd}
            />

            {/* Hero */}
            <section className="relative overflow-hidden">
                {interview.hero_image && (
                    <div className="absolute inset-0 -z-10">
                        <img
                            src={`/storage/${interview.hero_image}`}
                            alt=""
                            className="h-full w-full object-cover opacity-30 blur-2xl"
                        />
                        <div className="absolute inset-0 bg-gradient-to-b from-background/60 via-background/80 to-background" />
                    </div>
                )}

                <div className="mx-auto max-w-5xl px-4 pb-12 pt-8">
                    <Link
                        href="/interviews"
                        className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-champagne"
                    >
                        <ArrowLeft className="h-4 w-4" />
                        Toutes les interviews
                    </Link>

                    <motion.header
                        initial={{ opacity: 0, y: 16 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.6 }}
                        className="mt-8"
                    >
                        <div className="flex flex-wrap items-center gap-3">
                            <Badge variant="champagne">
                                <Mic className="h-3 w-3" />
                                La Voix du Futsal
                            </Badge>
                            <Badge variant="muted">{interview.interviewee_role}</Badge>
                            {date && (
                                <div className="flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                                    <Calendar className="h-3 w-3" />
                                    {date.day} {date.month} {date.year}
                                </div>
                            )}
                        </div>

                        <h1 className="mt-6 font-editorial text-4xl font-medium leading-[1.05] tracking-tight text-foreground sm:text-5xl lg:text-6xl">
                            {interview.title}
                        </h1>

                        <div className="mt-8 flex items-center gap-4 border-t border-border pt-6">
                            <div className="relative">
                                {interview.interviewee_photo ? (
                                    <img
                                        src={`/storage/${interview.interviewee_photo}`}
                                        alt={interview.interviewee_name}
                                        className="h-16 w-16 rounded-full border-2 border-champagne object-cover"
                                    />
                                ) : (
                                    <div className="flex h-16 w-16 items-center justify-center rounded-full border-2 border-champagne bg-card">
                                        <User className="h-8 w-8 text-muted-foreground" />
                                    </div>
                                )}
                            </div>
                            <div>
                                <div className="font-mono text-[10px] uppercase tracking-widest text-champagne">
                                    Notre invité
                                </div>
                                <div className="font-display text-lg font-bold">
                                    {interview.interviewee_name}
                                </div>
                                {interview.interviewee_affiliation && (
                                    <div className="text-sm text-muted-foreground">
                                        {interview.interviewee_affiliation}
                                    </div>
                                )}
                            </div>
                        </div>
                    </motion.header>
                </div>
            </section>

            {/* Hero image */}
            {interview.hero_image && (
                <motion.figure
                    initial={{ opacity: 0, scale: 0.98 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ duration: 0.6, delay: 0.1 }}
                    className="mx-auto mb-12 max-w-5xl px-4"
                >
                    <img
                        src={`/storage/${interview.hero_image}`}
                        alt={interview.title}
                        className="aspect-[16/9] w-full rounded-3xl border border-border object-cover"
                    />
                </motion.figure>
            )}

            {/* Quote highlight */}
            {interview.quote_highlight && (
                <motion.section
                    initial={{ opacity: 0, y: 20 }}
                    whileInView={{ opacity: 1, y: 0 }}
                    viewport={{ once: true }}
                    transition={{ duration: 0.6 }}
                    className="mx-auto mb-12 max-w-4xl px-4"
                >
                    <blockquote className="relative rounded-3xl border border-champagne/30 bg-card p-10 text-center lg:p-14">
                        <Quote className="mx-auto h-8 w-8 text-champagne" />
                        <p className="mt-6 font-editorial text-2xl italic leading-relaxed text-foreground lg:text-3xl">
                            « {interview.quote_highlight} »
                        </p>
                        <div className="mt-6 font-mono text-xs uppercase tracking-widest text-champagne">
                            — {interview.interviewee_name}
                        </div>
                    </blockquote>
                </motion.section>
            )}

            {/* Content */}
            <section className="mx-auto max-w-3xl px-4 pb-12">
                {interview.excerpt && (
                    <motion.p
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ duration: 0.5, delay: 0.15 }}
                        className="mb-10 font-editorial text-xl leading-relaxed text-muted-foreground"
                    >
                        {interview.excerpt}
                    </motion.p>
                )}

                <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.5, delay: 0.2 }}
                    className="text-lg leading-relaxed text-foreground/90 [&>p]:mb-5 [&>h2]:mt-10 [&>h2]:mb-4 [&>h2]:font-editorial [&>h2]:text-3xl [&>h2]:font-medium [&>h2]:leading-tight [&>h3]:mt-8 [&>h3]:mb-3 [&>h3]:font-editorial [&>h3]:text-2xl [&>h3]:font-medium [&>ul]:my-4 [&>ul]:list-disc [&>ul]:pl-6 [&>a]:text-champagne [&>a]:underline [&>blockquote]:my-10 [&>blockquote]:font-editorial [&>blockquote]:text-2xl [&>blockquote]:italic [&>blockquote]:text-champagne [&>blockquote]:leading-snug [&>strong]:text-champagne [&>strong]:font-semibold"
                    dangerouslySetInnerHTML={{ __html: interview.content }}
                />
            </section>

            {/* Video */}
            {embed && (
                <section className="mx-auto max-w-5xl px-4 pb-16">
                    <div className="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        <Play className="h-3 w-3" />
                        Regarder l'interview
                    </div>
                    <div className="mt-4 overflow-hidden rounded-2xl border border-border bg-card">
                        <iframe
                            src={embed}
                            className="aspect-video w-full"
                            allow="autoplay; encrypted-media; picture-in-picture"
                            allowFullScreen
                            title={interview.title}
                        />
                    </div>
                </section>
            )}

            {/* Actions */}
            <section className="mx-auto max-w-3xl px-4 pb-16">
                <div className="flex flex-wrap items-center justify-between gap-4 rounded-2xl border border-border bg-card p-6">
                    <Button asChild variant="outline">
                        <Link href="/interviews">
                            <ArrowLeft className="h-4 w-4" />
                            Toutes les interviews
                        </Link>
                    </Button>
                    <button
                        onClick={() => {
                            if (navigator.share) {
                                navigator.share({ title: interview.title, url: window.location.href });
                            } else {
                                navigator.clipboard.writeText(window.location.href);
                            }
                        }}
                        className="inline-flex items-center gap-2 text-sm font-medium text-muted-foreground transition-colors hover:text-champagne"
                    >
                        <Share2 className="h-4 w-4" />
                        Partager
                    </button>
                </div>

                <div className="mt-10 flex flex-col items-center gap-4">
                    <Ornament />
                    <Monogram />
                    <p className="text-center text-sm italic text-muted-foreground">
                        Propos recueillis pour <span className="font-semibold not-italic text-foreground">Dina Kenitra FC</span> · La Voix du Futsal
                    </p>
                </div>
            </section>

            {/* Related */}
            {related.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 pb-16">
                    <div className="mb-8">
                        <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                            Aussi à lire
                        </div>
                        <h2 className="mt-3 font-display text-display-lg">Autres voix du futsal</h2>
                    </div>
                    <div className="grid gap-6 md:grid-cols-3">
                        {related.map((r) => (
                            <Link
                                key={r.id}
                                href={`/interviews/${r.slug}`}
                                className="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-champagne/40"
                            >
                                <div className="relative aspect-[16/10] overflow-hidden bg-muted">
                                    {r.hero_image ? (
                                        <img
                                            src={`/storage/${r.hero_image}`}
                                            alt=""
                                            loading="lazy"
                                            className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                                        />
                                    ) : (
                                        <div className="flex h-full w-full items-center justify-center">
                                            <Mic className="h-10 w-10 text-champagne/30" />
                                        </div>
                                    )}
                                </div>
                                <div className="p-5">
                                    <Badge variant="champagne" className="mb-2">
                                        {r.interviewee_role}
                                    </Badge>
                                    <h3 className="font-display text-base font-semibold leading-tight transition-colors group-hover:text-champagne">
                                        {r.title}
                                    </h3>
                                    <div className="mt-1 text-sm text-muted-foreground">
                                        {r.interviewee_name}
                                    </div>
                                </div>
                            </Link>
                        ))}
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}
