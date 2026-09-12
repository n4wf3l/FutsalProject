import { FormEvent, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowRight, Search as SearchIcon } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Pagination } from '@/Components/site/Pagination';
import { SmartImage } from '@/Components/site/SmartImage';
import { breadcrumbLd } from '@/lib/seo';
import type { Paginated } from '@/types/models';

interface SearchHit {
    type: 'article' | 'press_release' | 'interview' | 'player' | 'coach' | 'staff' | 'gallery' | 'video';
    type_label: string;
    title: string;
    excerpt: string | null;
    image: string | null;
    url: string;
    date: string | null;
}

interface Props {
    query: string;
    results: Paginated<SearchHit>;
}

export default function Search({ query, results }: Props) {
    const { t, i18n } = useTranslation(['common', 'nav']);
    const [value, setValue] = useState(query);

    const crumbs = [
        { label: t('nav:items.home'), href: '/' },
        { label: t('common:search.breadcrumb') },
    ];

    const submit = (e: FormEvent) => {
        e.preventDefault();
        const q = value.trim();
        if (q.length < 2) return;
        router.get('/search', { q }, { preserveState: true, replace: true });
    };

    const formatDate = (iso: string | null) => {
        if (!iso) return null;
        try {
            return new Date(iso).toLocaleDateString(i18n.language, {
                day: '2-digit',
                month: 'long',
                year: 'numeric',
            });
        } catch {
            return null;
        }
    };

    return (
        <SiteLayout>
            <SEO
                title={query ? t('common:search.seo_title_with_query', { query }) : t('common:search.seo_title')}
                description={t('common:search.seo_description')}
                jsonLd={breadcrumbLd(crumbs)}
                noindex
            />

            <PageHeader
                kicker={t('common:search.kicker')}
                title={query ? t('common:search.title_with_query', { query }) : t('common:search.title_default')}
                subtitle={
                    query && results.total > 0
                        ? t('common:search.subtitle_with_results', { count: results.total })
                        : query
                          ? t('common:search.subtitle_no_results')
                          : t('common:search.subtitle_default')
                }
                breadcrumb={crumbs}
                variant="editorial"
            >
                <form onSubmit={submit} className="mt-4 w-full max-w-2xl">
                    <div className="relative border-b border-border transition-colors focus-within:border-crimson">
                        <SearchIcon className="pointer-events-none absolute left-0 top-1/2 h-5 w-5 -translate-y-1/2 text-muted-foreground" />
                        <input
                            type="search"
                            value={value}
                            onChange={(e) => setValue(e.target.value)}
                            placeholder={t('common:search.placeholder')}
                            className="w-full bg-transparent py-3 pl-8 pr-4 font-display text-lg text-foreground placeholder:font-editorial placeholder:italic placeholder:text-muted-foreground/60 focus:outline-none sm:text-xl"
                            aria-label={t('common:search.placeholder')}
                        />
                    </div>
                </form>
            </PageHeader>

            <section className="mx-auto max-w-4xl px-4 pb-16">
                {results.data.length === 0 ? (
                    <EmptyState
                        icon={SearchIcon}
                        title={
                            query
                                ? t('common:search.empty_title_query', { query })
                                : t('common:search.empty_title_no_query')
                        }
                        description={
                            query
                                ? t('common:search.empty_body_query')
                                : t('common:search.empty_body_no_query')
                        }
                    />
                ) : (
                    <ul className="space-y-6">
                        {results.data.map((hit, i) => (
                            <motion.li
                                key={`${hit.type}_${hit.url}_${i}`}
                                initial={{ opacity: 0, y: 12 }}
                                animate={{ opacity: 1, y: 0 }}
                                transition={{ duration: 0.35, delay: i * 0.04 }}
                            >
                                <Link
                                    href={hit.url}
                                    className="group grid gap-5 border-b border-border pb-6 transition-colors sm:grid-cols-[160px_1fr]"
                                >
                                    {hit.image ? (
                                        <div className="relative aspect-[4/3] overflow-hidden rounded-lg border border-border bg-muted sm:aspect-[4/3]">
                                            <SmartImage
                                                src={hit.image}
                                                alt=""
                                                className="transition-transform duration-500 group-hover:scale-105"
                                            />
                                        </div>
                                    ) : (
                                        <div className="flex aspect-[4/3] items-center justify-center rounded-lg border border-dashed border-border bg-card/40 text-muted-foreground/40">
                                            <SearchIcon className="h-8 w-8" />
                                        </div>
                                    )}
                                    <div>
                                        <div className="flex flex-wrap items-center gap-3 font-mono text-[10px] uppercase tracking-widest">
                                            <span className="rounded-full border border-champagne/40 px-2.5 py-0.5 text-champagne">
                                                {hit.type_label}
                                            </span>
                                            {formatDate(hit.date) && (
                                                <span className="text-muted-foreground">
                                                    {formatDate(hit.date)}
                                                </span>
                                            )}
                                        </div>
                                        <h2 className="mt-3 font-display text-xl font-semibold leading-tight transition-colors group-hover:text-crimson sm:text-2xl">
                                            {hit.title}
                                        </h2>
                                        {hit.excerpt && (
                                            <p className="mt-3 line-clamp-2 text-sm leading-relaxed text-muted-foreground">
                                                {hit.excerpt}
                                            </p>
                                        )}
                                        <div className="mt-4 inline-flex items-center gap-1.5 text-xs font-semibold text-crimson">
                                            {t('common:search.open')}
                                            <ArrowRight className="h-3.5 w-3.5 transition-transform group-hover:translate-x-1" />
                                        </div>
                                    </div>
                                </Link>
                            </motion.li>
                        ))}
                    </ul>
                )}

                {results.data.length > 0 && (
                    <Pagination className="mt-12" links={results.links} />
                )}
            </section>
        </SiteLayout>
    );
}
