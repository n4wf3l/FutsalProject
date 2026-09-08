import { FormEvent, useState } from 'react';
import { Link, router } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { breadcrumbLd } from '@/lib/seo';
import { ArrowRight, FileText, Search } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Pagination } from '@/Components/site/Pagination';
import { SmartImage } from '@/Components/site/SmartImage';
import { Badge } from '@/Components/ui/Badge';
import { Input } from '@/Components/ui/Input';
import { Button } from '@/Components/ui/Button';
import { formatMatchDate, cn } from '@/lib/utils';
import type { Paginated, PressRelease } from '@/types/models';

interface Props {
    pressReleases: Paginated<PressRelease>;
    search: string;
}

export default function PressReleasesIndex({ pressReleases, search }: Props) {
    const { t, i18n } = useTranslation(['pages', 'nav']);
    const [query, setQuery] = useState(search ?? '');

    const submit = (e: FormEvent) => {
        e.preventDefault();
        router.get('/communiques', query ? { search: query } : {}, {
            preserveState: true,
            replace: true,
        });
    };

    const crumbs = [
        { label: t('nav:items.home'), href: '/' },
        { label: t('pages:press_releases.breadcrumb') },
    ];

    return (
        <SiteLayout>
            <SEO
                title={t('pages:press_releases.seo_title')}
                description={t('pages:press_releases.seo_description')}
                jsonLd={breadcrumbLd(crumbs)}
            />

            <PageHeader
                kicker={t('pages:press_releases.kicker')}
                kickerRight={new Date().toLocaleDateString(i18n.language, {
                    day: '2-digit',
                    month: 'long',
                    year: 'numeric',
                })}
                title={t('pages:press_releases.title')}
                subtitle={t('pages:press_releases.subtitle')}
                breadcrumb={crumbs}
                variant="editorial"
            >
                <form onSubmit={submit} className="mt-2 flex w-full max-w-md gap-2">
                    <div className="relative flex-1">
                        <Search className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            value={query}
                            onChange={(e) => setQuery(e.target.value)}
                            placeholder={t('pages:press_releases.search_placeholder')}
                            className="pl-10"
                        />
                    </div>
                    <Button type="submit" size="default">
                        {t('pages:press_releases.search_button')}
                    </Button>
                </form>
            </PageHeader>

            <section className="mx-auto max-w-7xl px-4 pb-16">
                {pressReleases.total === 0 ? (
                    <EmptyState
                        icon={FileText}
                        title={t('pages:press_releases.empty_title')}
                        description={
                            search
                                ? t('pages:press_releases.empty_search_description', { query: search })
                                : t('pages:press_releases.empty_description')
                        }
                    />
                ) : (
                    <>
                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {pressReleases.data.map((pr, i) => (
                                <PressReleaseCard key={pr.id} pressRelease={pr} index={i} />
                            ))}
                        </div>

                        <div className="mt-12 flex items-center justify-between gap-4">
                            <div className="text-sm text-muted-foreground">
                                {t('pages:press_releases.pagination_summary', {
                                    from: pressReleases.from ?? 0,
                                    to: pressReleases.to ?? 0,
                                    total: pressReleases.total,
                                })}
                            </div>
                            <Pagination links={pressReleases.links} />
                        </div>
                    </>
                )}
            </section>
        </SiteLayout>
    );
}

function PressReleaseCard({ pressRelease, index }: { pressRelease: PressRelease; index: number }) {
    const { t } = useTranslation('pages');
    const date = formatMatchDate(pressRelease.created_at);

    return (
        <motion.article
            initial={{ opacity: 0, y: 24 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-60px' }}
            transition={{ duration: 0.5, delay: (index % 8) * 0.06 }}
            className={cn(
                'group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-champagne/50'
            )}
        >
            <Link href={`/communiques/${pressRelease.slug}`} className="block">
                <div className="relative aspect-[16/10] overflow-hidden bg-muted">
                    {pressRelease.image ? (
                        <SmartImage
                            src={`/storage/${pressRelease.image}`}
                            alt={pressRelease.title}
                            className="group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <FileText className="h-16 w-16 text-champagne/30" />
                        </div>
                    )}
                    <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-card via-card/70 to-transparent" />
                    <div className="absolute left-4 top-4">
                        <Badge variant="champagne">{t('press_releases.badge')}</Badge>
                    </div>
                </div>
            </Link>
            <div className="p-6">
                <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    {date.day} {date.month} {date.year}
                </div>
                <h3 className="mt-2 font-display text-xl font-semibold leading-snug transition-colors group-hover:text-champagne">
                    <Link href={`/communiques/${pressRelease.slug}`}>{pressRelease.title}</Link>
                </h3>
                <Link
                    href={`/communiques/${pressRelease.slug}`}
                    className="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-champagne"
                >
                    {t('press_releases.read')}
                    <ArrowRight className="h-3 w-3 transition-transform group-hover:translate-x-1" />
                </Link>
            </div>
        </motion.article>
    );
}
