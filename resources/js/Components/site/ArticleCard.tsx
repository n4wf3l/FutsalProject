import { motion } from 'framer-motion';
import { Link } from '@inertiajs/react';
import { ArrowRight, Newspaper } from 'lucide-react';
import type { Article } from '@/types/models';
import { Badge } from '@/Components/ui/Badge';
import { formatMatchDate, cn } from '@/lib/utils';

interface Props {
    article: Article;
    index?: number;
    variant?: 'default' | 'compact' | 'featured';
    className?: string;
}

export function ArticleCard({ article, index = 0, variant = 'default', className }: Props) {
    const date = formatMatchDate(article.created_at);

    if (variant === 'compact') {
        return (
            <Link
                href={`/articles/${article.slug}`}
                className={cn(
                    'group flex items-start gap-4 rounded-xl border border-border bg-card p-3 transition-all hover:border-crimson/40',
                    className
                )}
            >
                <div className="relative h-20 w-20 shrink-0 overflow-hidden rounded-lg bg-muted">
                    {article.image ? (
                        <img
                            src={`/storage/${article.image}`}
                            alt=""
                            loading="lazy"
                            className="h-full w-full object-cover transition-transform duration-500 group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <Newspaper className="h-6 w-6 text-muted-foreground/40" />
                        </div>
                    )}
                </div>
                <div className="flex min-w-0 flex-col justify-center">
                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {date.day} {date.month}
                    </div>
                    <div className="mt-1 line-clamp-2 text-sm font-semibold leading-snug transition-colors group-hover:text-crimson">
                        {article.title}
                    </div>
                </div>
            </Link>
        );
    }

    const isFeatured = variant === 'featured';

    return (
        <motion.article
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-60px' }}
            transition={{ duration: 0.5, delay: (index % 8) * 0.06 }}
            className={cn(
                'group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40',
                isFeatured && 'lg:grid lg:grid-cols-2',
                className
            )}
        >
            <Link href={`/articles/${article.slug}`} className="block">
                <div
                    className={cn(
                        'relative overflow-hidden bg-muted',
                        isFeatured ? 'aspect-video lg:aspect-auto lg:h-full' : 'aspect-[16/10]'
                    )}
                >
                    {article.image ? (
                        <img
                            src={`/storage/${article.image}`}
                            alt=""
                            loading="lazy"
                            className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <Newspaper className="h-16 w-16 text-muted-foreground/30" />
                        </div>
                    )}
                    <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-card via-card/70 to-transparent" />
                    <div className="absolute left-4 top-4">
                        <Badge variant="default">Article</Badge>
                    </div>
                </div>
            </Link>

            <div className={cn('p-6', isFeatured && 'lg:p-10')}>
                <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    {date.day} {date.month} {date.year}
                </div>
                <h3
                    className={cn(
                        'mt-2 font-display font-semibold leading-snug transition-colors group-hover:text-crimson',
                        isFeatured ? 'text-3xl' : 'text-xl'
                    )}
                >
                    <Link href={`/articles/${article.slug}`}>{article.title}</Link>
                </h3>
                {article.description && (
                    <p
                        className={cn(
                            'mt-3 text-sm text-muted-foreground',
                            isFeatured ? 'line-clamp-4' : 'line-clamp-2'
                        )}
                    >
                        {article.description.replace(/<[^>]+>/g, '')}
                    </p>
                )}
                <Link
                    href={`/articles/${article.slug}`}
                    className="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-crimson"
                >
                    Lire l'article
                    <ArrowRight className="h-3 w-3 transition-transform group-hover:translate-x-1" />
                </Link>
            </div>
        </motion.article>
    );
}
