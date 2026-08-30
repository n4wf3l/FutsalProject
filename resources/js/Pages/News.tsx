import { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { Newspaper, Search } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { ArticleCard } from '@/Components/site/ArticleCard';
import { EmptyState } from '@/Components/site/EmptyState';
import { Pagination } from '@/Components/site/Pagination';
import { Input } from '@/Components/ui/Input';
import { Button } from '@/Components/ui/Button';
import type { Article, Paginated } from '@/types/models';

interface NewsProps {
    articles: Paginated<Article>;
    search: string;
}

export default function News({ articles, search }: NewsProps) {
    const { t, i18n } = useTranslation(['pages', 'nav']);
    const [query, setQuery] = useState(search ?? '');

    const submit = (e: FormEvent) => {
        e.preventDefault();
        router.get('/news', query ? { search: query } : {}, { preserveState: true, replace: true });
    };

    const featured = articles.data[0];
    const rest = articles.data.slice(1);

    return (
        <SiteLayout>
            <SEO
                title={t('pages:news.seo_title')}
                description={t('pages:news.seo_description')}
            />

            <PageHeader
                kicker={t('pages:news.kicker')}
                kickerRight={new Date().toLocaleDateString(i18n.language, { day: '2-digit', month: 'long', year: 'numeric' })}
                title={t('pages:news.title')}
                subtitle={t('pages:news.subtitle')}
                breadcrumb={[{ label: t('nav:items.home'), href: '/' }, { label: t('pages:news.breadcrumb') }]}
                variant="editorial"
            >
                <form onSubmit={submit} className="mt-2 flex w-full max-w-md gap-2">
                    <div className="relative flex-1">
                        <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            placeholder={t('pages:news.search_placeholder')}
                            className="pl-10"
                        />
                    </div>
                    <Button type="submit" size="default">
                        {t('pages:news.search_button')}
                    </Button>
                </form>
            </PageHeader>

            <section className="mx-auto max-w-7xl px-4 pb-16">
                {articles.total === 0 ? (
                    <EmptyState
                        icon={Newspaper}
                        title={t('pages:news.empty_title')}
                        description={
                            search
                                ? t('pages:news.empty_search_description', { query: search })
                                : t('pages:news.empty_description')
                        }
                    />
                ) : (
                    <>
                        {featured && !search && articles.current_page === 1 && (
                            <div className="mb-10">
                                <ArticleCard article={featured} variant="featured" index={0} />
                            </div>
                        )}

                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {(featured && !search && articles.current_page === 1 ? rest : articles.data).map(
                                (a, i) => (
                                    <ArticleCard key={a.id} article={a} index={i + 1} />
                                )
                            )}
                        </div>

                        <div className="mt-12 flex items-center justify-between gap-4">
                            <div className="text-sm text-muted-foreground">
                                {t('pages:news.pagination_summary', {
                                    from: articles.from ?? 0,
                                    to: articles.to ?? 0,
                                    total: articles.total,
                                })}
                            </div>
                            <Pagination links={articles.links} />
                        </div>
                    </>
                )}
            </section>
        </SiteLayout>
    );
}
