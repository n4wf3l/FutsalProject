import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Calendar, Share2 } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { ArticleCard } from '@/Components/site/ArticleCard';
import { Badge } from '@/Components/ui/Badge';
import { Button } from '@/Components/ui/Button';
import { formatMatchDate } from '@/lib/utils';
import type { Article } from '@/types/models';

interface Props {
    article: Article;
    recentArticles: Article[];
}

export default function ArticleShow({ article, recentArticles }: Props) {
    const date = formatMatchDate(article.created_at);

    return (
        <SiteLayout>
            <Head title={article.title} />

            <article className="mx-auto max-w-4xl px-4 py-8">
                <Link
                    href="/news"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux actualités
                </Link>

                <motion.header
                    initial={{ opacity: 0, y: 16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    className="mt-8"
                >
                    <div className="flex flex-wrap items-center gap-3">
                        <Badge variant="default">Article</Badge>
                        <div className="flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                            <Calendar className="h-3 w-3" />
                            {date.day} {date.month} {date.year}
                        </div>
                    </div>
                    <h1 className="mt-4 font-display text-display-xl leading-tight">
                        {article.title}
                    </h1>
                </motion.header>

                {article.image && (
                    <motion.figure
                        initial={{ opacity: 0, scale: 0.98 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.6, delay: 0.1 }}
                        className="my-10 overflow-hidden rounded-3xl border border-border"
                    >
                        <img
                            src={`/storage/${article.image}`}
                            alt={article.title}
                            className="aspect-video w-full object-cover"
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
                        className="text-lg leading-relaxed text-foreground/90 [&>p]:mb-5 [&>h2]:mt-10 [&>h2]:mb-4 [&>h2]:font-display [&>h2]:text-2xl [&>h2]:font-semibold [&>h3]:mt-8 [&>h3]:mb-3 [&>h3]:font-display [&>h3]:text-xl [&>ul]:my-4 [&>ul]:list-disc [&>ul]:pl-6 [&>a]:text-crimson [&>a]:underline [&>blockquote]:my-6 [&>blockquote]:border-l-4 [&>blockquote]:border-champagne [&>blockquote]:pl-4 [&>blockquote]:italic"
                        dangerouslySetInnerHTML={{ __html: article.description ?? '' }}
                    />
                </motion.div>

                <div className="mt-12 flex items-center justify-between border-t border-border pt-6">
                    <Button asChild variant="outline" size="sm">
                        <Link href="/news">
                            <ArrowLeft className="h-4 w-4" />
                            Toutes les actualités
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
                        Partager
                    </button>
                </div>
            </article>

            {recentArticles.length > 0 && (
                <section className="mx-auto max-w-7xl px-4 py-16">
                    <div className="mb-8 flex items-end justify-between">
                        <div>
                            <div className="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                                <span className="h-px w-8 bg-champagne" />
                                Aussi à lire
                            </div>
                            <h2 className="mt-3 font-display text-display-lg">Articles récents</h2>
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
