import { Head, Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowRight, Image, ImagePlus } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Pagination } from '@/Components/site/Pagination';
import { Badge } from '@/Components/ui/Badge';
import { formatMatchDate } from '@/lib/utils';
import type { Gallery, Paginated } from '@/types/models';

interface Props {
    galleries: Paginated<Gallery & { photos_count?: number }>;
}

export default function Galleries({ galleries }: Props) {
    return (
        <SiteLayout>
            <Head title="Galerie photos" />

            <PageHeader
                kicker="En images"
                title="Galerie photos"
                subtitle="Les meilleurs instants du club — matchs, coulisses, entraînements et événements."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Galerie' }]}
            />

            <section className="mx-auto max-w-7xl px-4 pb-16">
                {galleries.data.length === 0 ? (
                    <EmptyState
                        icon={ImagePlus}
                        title="Aucune galerie disponible"
                        description="Les premières galeries photos du club arriveront bientôt."
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
                                {galleries.from ?? 0}–{galleries.to ?? 0} sur {galleries.total}
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
    const created = gallery.created_at ? formatMatchDate(gallery.created_at) : null;
    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: (index % 6) * 0.06 }}
            className="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40 hover:shadow-glow-crimson"
        >
            <Link href={`/galleries/${gallery.id}`} className="block">
                <div className="relative aspect-[4/3] overflow-hidden bg-muted">
                    {gallery.cover_image ? (
                        <img
                            src={`/storage/${gallery.cover_image}`}
                            alt={gallery.name}
                            loading="lazy"
                            className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
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
                                {gallery.photos_count} photo{gallery.photos_count > 1 ? 's' : ''}
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
                        Voir la galerie
                        <ArrowRight className="h-3 w-3 transition-transform group-hover:translate-x-1" />
                    </div>
                </div>
            </Link>
        </motion.article>
    );
}
