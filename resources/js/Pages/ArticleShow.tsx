import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowLeft, Calendar, Share2 } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { ArticleCard } from '@/Components/site/ArticleCard';
import { Monogram, Ornament } from '@/Components/site/Ornament';
import { SmartImage } from '@/Components/site/SmartImage';
import { Badge } from '@/Components/ui/Badge';
import { Button } from '@/Components/ui/Button';
import { formatMatchDate } from '@/lib/utils';
import type { Article } from '@/types/models';

interface Props {
    article: Article;
    recentArticles: Article[];
}

export default function ArticleShow({ article, recentArticles }: Props) {
    const { t } = useTranslation('pages');
    const date = formatMatchDate(article.created_at);
    const plainDescription = (article.description ?? '')
        .replace(/<[^>]+>/g, '')
        .replace(/\s+/g, ' ')
        .trim()
        .slice(0, 200);

    const articleLd = {
        '@context': 'https://schema.org',
        '@type': 'NewsArticle',
        headline: article.title,
        description: plainDescription,
        image: article.image
            ? [`${typeof window !== 'undefined' ? window.location.origin : ''}/storage/${article.image}`]
            : undefined,
        datePublished: article.created_at,
        dateModified: article.created_at,
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
                title={article.title}
                description={plainDescription || t('article.seo_description_fallback', { title: article.title })}
                image={article.image}
                type="article"
                publishedAt={article.created_at}
                author="Dina Kenitra FC"
                section="Actualités"
                jsonLd={articleLd}
            />

            <article className="mx-auto max-w-4xl px-4 py-8">
                <Link
                    href="/news"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    {t('article.back_to_news')}
                </Link>

                <motion.header
                    initial={{ opacity: 0, y: 16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    className="mt-8"
                >
                    <div className="flex flex-wrap items-center gap-3">
                        <Badge variant="default">{t('article.badge')}</Badge>
                        <div className="flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                            <Calendar className="h-3 w-3" />
                            {date.day} {date.month} {date.year}
                        </div>
                    </div>
                    <h1 className="mt-4 font-editorial text-4xl font-medium leading-[1.05] tracking-tight text-foreground sm:text-5xl lg:text-6xl">
                        {article.title}
                    </h1>
                </motion.header>

                {article.image && (
                    <motion.figure
                        initial={{ opacity: 0, scale: 0.98 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.6, delay: 0.1 }}
                        className="relative my-10 aspect-video overflow-hidden rounded-3xl border border-border"
                    >
                        <SmartImage
                            src={`/storage/${article.image}`}
                            alt={article.title}
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
                        className="text-lg leading-relaxed text-foreground/90 [&>p]:mb-5 [&>h2]:mt-10 [&>h2]:mb-4 [&>h2]:font-editorial [&>h2]:text-3xl [&>h2]:font-medium [&>h2]:leading-tight [&>h3]:mt-8 [&>h3]:mb-3 [&>h3]:font-editorial [&>h3]:text-2xl [&>h3]:font-medium [&>ul]:my-4 [&>ul]:list-disc [&>ul]:pl-6 [&>a]:text-crimson [&>a]:underline [&>blockquote]:my-10 [&>blockquote]:font-editorial [&>blockquote]:text-2xl [&>blockquote]:italic [&>blockquote]:text-champagne [&>blockquote]:leading-snug"
                        dangerouslySetInnerHTML={{ __html: article.description ?? '' }}
                    />
                </motion.div>

                <div className="mt-10 flex flex-col items-center gap-3">
                    <Ornament />
                    <Monogram />
                </div>

                <div className="mt-12 flex items-center justify-between border-t border-border pt-6">
                    <Button asChild variant="outline" size="sm">
                        <Link href="/news">
                            <ArrowLeft className="h-4 w-4" />
                            {t('article.all_news')}
                        </Link>
                    </Button>
                    <button
                        onClick={() => {
                            if (navigator.share) {
                                navigator.share({ title: article.title, url: window.location.href });
                            } else {
                                navigator.clipboard.writeText(window.location.href);
                            }
                        }}
                        className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                    >
                        <Share2 className="h-4 w-4" />
                        {t('article.share')}
                    </button>
                </div>
            </article>

            {recentArticles.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <div className="mb-8 flex items-end justify-between">
                        <div>
                            <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                                {t('article.related_kicker')}
                            </div>
                            <h2 className="mt-3 font-display text-display-lg">{t('article.related_title')}</h2>
                        </div>
                    </div>
                    <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        {recentArticles.slice(0, 3).map((a, i) => (
                            <ArticleCard key={a.id} article={a} index={i} />
                        ))}
                    </div>
                </section>
            )}
        </SiteLayout>
    );
}
