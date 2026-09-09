import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowLeft, Calendar, FileText, Share2 } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { Monogram, Ornament } from '@/Components/site/Ornament';
import { SmartImage } from '@/Components/site/SmartImage';
import { Badge } from '@/Components/ui/Badge';
import { Button } from '@/Components/ui/Button';
import { formatMatchDate } from '@/lib/utils';
import type { PressRelease } from '@/types/models';

interface Props {
    pressRelease: PressRelease;
    recent: PressRelease[];
}

function formatBody(raw: string): string {
    if (!raw) return '';
    const hasBlockHtml = /<(p|div|section|article|h[1-6]|blockquote|ul|ol|figure|table)\b/i.test(raw);
    if (hasBlockHtml) return raw;
    return raw
        .split(/\n\s*\n+/)
        .map((chunk) => chunk.trim())
        .filter((chunk) => chunk.length > 0)
        .map((chunk) => `<p>${chunk.replace(/\n/g, '<br>')}</p>`)
        .join('');
}

export default function PressReleaseShow({ pressRelease, recent }: Props) {
    const { t } = useTranslation('pages');
    const date = formatMatchDate(pressRelease.created_at);
    const plain = (pressRelease.content ?? '')
        .replace(/<[^>]+>/g, '')
        .replace(/\s+/g, ' ')
        .trim()
        .slice(0, 200);

    const bodyHtml = formatBody(pressRelease.content ?? '');

    const ld = {
        '@context': 'https://schema.org',
        '@type': 'NewsArticle',
        headline: pressRelease.title,
        description: plain,
        image: pressRelease.image
            ? [`${typeof window !== 'undefined' ? window.location.origin : ''}/storage/${pressRelease.image}`]
            : undefined,
        datePublished: pressRelease.created_at,
        dateModified: pressRelease.created_at,
        author: { '@type': 'Organization', name: 'Dina Kenitra FC' },
        publisher: {
            '@type': 'Organization',
            name: 'Dina Kenitra FC',
            logo: {
                '@type': 'ImageObject',
                url:
                    (typeof window !== 'undefined' ? window.location.origin : '') +
                    '/logo-dinakenitra.png',
            },
        },
        mainEntityOfPage: {
            '@type': 'WebPage',
            '@id': typeof window !== 'undefined' ? window.location.href : '',
        },
    };

    return (
        <SiteLayout>
            <SEO
                title={pressRelease.title}
                description={plain || t('press_releases.seo_description')}
                image={pressRelease.image}
                type="article"
                publishedAt={pressRelease.created_at}
                author="Dina Kenitra FC"
                section="Communiqués"
                jsonLd={ld}
            />

            <article className="mx-auto max-w-4xl px-4 py-8">
                <Link
                    href="/communiques"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-champagne"
                >
                    <ArrowLeft className="h-4 w-4" />
                    {t('press_releases.back_to_list')}
                </Link>

                <motion.header
                    initial={{ opacity: 0, y: 16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    className="mt-8"
                >
                    <div className="flex flex-wrap items-center gap-3">
                        <Badge variant="champagne">{t('press_releases.badge')}</Badge>
                        <div className="flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                            <Calendar className="h-3 w-3" />
                            {date.day} {date.month} {date.year}
                        </div>
                    </div>
                    <h1 className="mt-4 font-editorial text-3xl font-medium leading-[1.05] tracking-tight text-foreground sm:text-4xl md:text-5xl lg:text-6xl">
                        {pressRelease.title}
                    </h1>
                </motion.header>

                {pressRelease.image && (
                    <motion.figure
                        initial={{ opacity: 0, scale: 0.98 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.6, delay: 0.1 }}
                        className="relative my-10 aspect-video overflow-hidden rounded-3xl border border-border"
                    >
                        <SmartImage
                            src={`/storage/${pressRelease.image}`}
                            alt={pressRelease.title}
                            loading="eager"
                        />
                    </motion.figure>
                )}

                <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.5, delay: 0.15 }}
                    className="prose-content mt-8"
                >
                    <div
                        className="text-lg leading-relaxed text-foreground/90 [&>p]:mb-5 [&>h2]:mt-10 [&>h2]:mb-4 [&>h2]:font-editorial [&>h2]:text-3xl [&>h2]:font-medium [&>h2]:leading-tight [&>h3]:mt-8 [&>h3]:mb-3 [&>h3]:font-editorial [&>h3]:text-2xl [&>h3]:font-medium [&>ul]:my-4 [&>ul]:list-disc [&>ul]:pl-6 [&>ol]:my-4 [&>ol]:list-decimal [&>ol]:pl-6 [&>a]:text-champagne [&>a]:underline [&>blockquote]:my-10 [&>blockquote]:font-editorial [&>blockquote]:text-2xl [&>blockquote]:italic [&>blockquote]:text-champagne [&>blockquote]:leading-snug"
                        dangerouslySetInnerHTML={{ __html: bodyHtml }}
                    />
                </motion.div>

                <div className="mt-10 flex flex-col items-center gap-3">
                    <Ornament />
                    <Monogram />
                </div>

                <div className="mt-12 flex items-center justify-between border-t border-border pt-6">
                    <Button asChild variant="outline" size="sm">
                        <Link href="/communiques">
                            <ArrowLeft className="h-4 w-4" />
                            {t('press_releases.all')}
                        </Link>
                    </Button>
                    <button
                        onClick={() => {
                            if (navigator.share) {
                                navigator.share({ title: pressRelease.title, url: window.location.href });
                            } else {
                                navigator.clipboard.writeText(window.location.href);
                            }
                        }}
                        className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-champagne"
                    >
                        <Share2 className="h-4 w-4" />
                        {t('press_releases.share')}
                    </button>
                </div>
            </article>

            {recent.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <div className="mb-8">
                        <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                            {t('press_releases.related_kicker')}
                        </div>
                        <h2 className="mt-3 font-display text-display-lg">
                            {t('press_releases.related_title')}
                        </h2>
                    </div>
                    <ul className="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                        {recent.slice(0, 3).map((r) => (
                            <li key={r.id}>
                                <Link
                                    href={`/communiques/${r.slug}`}
                                    className="group flex items-start gap-4 rounded-2xl border border-border bg-card p-4 transition-all hover:border-champagne/40"
                                >
                                    <div className="h-16 w-20 shrink-0 overflow-hidden rounded-lg bg-muted">
                                        {r.image ? (
                                            <img
                                                src={`/storage/${r.image}`}
                                                alt={r.title}
                                                loading="lazy"
                                                className="h-full w-full object-cover"
                                            />
                                        ) : (
                                            <div className="flex h-full w-full items-center justify-center">
                                                <FileText className="h-5 w-5 text-champagne/40" />
                                            </div>
                                        )}
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                            {formatMatchDate(r.created_at).day} {formatMatchDate(r.created_at).month}
                                        </div>
                                        <div className="mt-1 line-clamp-2 text-sm font-semibold leading-snug transition-colors group-hover:text-champagne">
                                            {r.title}
                                        </div>
                                    </div>
                                </Link>
                            </li>
                        ))}
                    </ul>
                </section>
            )}
        </SiteLayout>
    );
}
