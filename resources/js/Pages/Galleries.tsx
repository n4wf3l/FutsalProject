import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowRight, Image, ImagePlus } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Pagination } from '@/Components/site/Pagination';
import { SmartImage } from '@/Components/site/SmartImage';
import { Badge } from '@/Components/ui/Badge';
import { formatMatchDate } from '@/lib/utils';
import type { Gallery, Paginated } from '@/types/models';

interface Props {
    galleries: Paginated<Gallery & { photos_count?: number }>;
}

export default function Galleries({ galleries }: Props) {
    const { t } = useTranslation(['pages', 'nav']);
    return (
        <SiteLayout>
            <SEO
                title={t('pages:galleries.seo_title')}
                description={t('pages:galleries.seo_description')}
            />

            <PageHeader
                kicker={t('pages:galleries.kicker')}
                title={t('pages:galleries.title')}
                subtitle={t('pages:galleries.subtitle')}
                breadcrumb={[{ label: t('nav:items.home'), href: '/' }, { label: t('pages:galleries.breadcrumb') }]}
            />

            <section className="mx-auto max-w-7xl px-4 pb-16">
                {galleries.data.length === 0 ? (
                    <EmptyState
                        icon={ImagePlus}
                        title={t('pages:galleries.empty_title')}
                        description={t('pages:galleries.empty_description')}
                    />
                ) : (
                    <>
                        <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                            {galleries.data.map((gallery, i) => (
                                <GalleryCard key={gallery.id} gallery={gallery} index={i} />
                            ))}
                        </div>

                        <div className="mt-12 flex items-center justify-between gap-4">
                            <div className="text-sm text-muted-foreground">
                                {t('pages:galleries.pagination_summary', {
                                    from: galleries.from ?? 0,
                                    to: galleries.to ?? 0,
                                    total: galleries.total,
                                })}
                            </div>
                            <Pagination links={galleries.links} />
                        </div>
                    </>
                )}
            </section>
        </SiteLayout>
    );
}

function GalleryCard({
    gallery,
    index,
}: {
    gallery: Gallery & { photos_count?: number };
    index: number;
}) {
    const { t } = useTranslation('pages');
    const created = gallery.created_at ? formatMatchDate(gallery.created_at) : null;
    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: (index % 6) * 0.06 }}
            className="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40"
        >
            <Link href={`/galleries/${gallery.id}`} className="block">
                <div className="relative aspect-[4/3] overflow-hidden bg-muted">
                    {gallery.cover_image ? (
                        <SmartImage
                            src={`/storage/${gallery.cover_image}`}
                            alt={gallery.name}
                            className="group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <Image className="h-16 w-16 text-muted-foreground/30" strokeWidth={1} />
                        </div>
                    )}
                    <div className="absolute inset-x-0 bottom-0 h-32 bg-gradient-to-t from-card via-card/70 to-transparent" />
                    {typeof gallery.photos_count === 'number' && (
                        <div className="absolute right-4 top-4">
                            <Badge variant="champagne">
                                <Image className="h-3 w-3" />
                                {t('galleries.photos_count', { count: gallery.photos_count })}
                            </Badge>
                        </div>
                    )}
                </div>
                <div className="p-5">
                    {created && (
                        <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                            {created.day} {created.month} {created.year}
                        </div>
                    )}
                    <h3 className="mt-1 font-display text-lg font-semibold leading-tight transition-colors group-hover:text-crimson">
                        {gallery.name}
                    </h3>
                    {gallery.description && (
                        <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">
                            {gallery.description}
                        </p>
                    )}
                    <div className="mt-4 inline-flex items-center gap-1 text-xs font-semibold text-crimson">
                        {t('galleries.view_gallery')}
                        <ArrowRight className="h-3 w-3 transition-transform group-hover:translate-x-1" />
                    </div>
                </div>
            </Link>
        </motion.article>
    );
}
