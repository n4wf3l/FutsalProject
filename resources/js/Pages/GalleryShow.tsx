import { useEffect, useState } from 'react';
import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { AnimatePresence, motion } from 'framer-motion';
import { ArrowLeft, ChevronLeft, ChevronRight, X } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { SmartImage } from '@/Components/site/SmartImage';
import { Image as ImageIcon } from 'lucide-react';
import type { Gallery, Photo } from '@/types/models';

interface Props {
    gallery: Gallery;
    photos: Photo[];
}

export default function GalleryShow({ gallery, photos }: Props) {
    const { t } = useTranslation(['pages', 'nav']);
    const [openIndex, setOpenIndex] = useState<number | null>(null);

    useEffect(() => {
        if (openIndex === null) return;
        const onKey = (e: KeyboardEvent) => {
            if (e.key === 'Escape') setOpenIndex(null);
            if (e.key === 'ArrowRight')
                setOpenIndex((i) => (i === null ? 0 : (i + 1) % photos.length));
            if (e.key === 'ArrowLeft')
                setOpenIndex((i) => (i === null ? 0 : (i - 1 + photos.length) % photos.length));
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [openIndex, photos.length]);

    return (
        <SiteLayout>
            <SEO
                title={gallery.name}
                description={gallery.description ?? t('pages:gallery_show.seo_description_fallback', { name: gallery.name })}
                image={gallery.cover_image}
            />

            <PageHeader
                kicker={t('pages:gallery_show.breadcrumb_root')}
                title={gallery.name}
                subtitle={gallery.description ?? undefined}
                breadcrumb={[
                    { label: t('nav:items.home'), href: '/' },
                    { label: t('pages:gallery_show.breadcrumb_root'), href: '/galleries' },
                    { label: gallery.name },
                ]}
            >
                <Link
                    href="/galleries"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    {t('pages:gallery_show.all_galleries')}
                </Link>
            </PageHeader>

            <section className="mx-auto max-w-7xl px-4 pb-16">
                {photos.length === 0 ? (
                    <EmptyState
                        icon={ImageIcon}
                        title={t('pages:gallery_show.empty_title')}
                        description={t('pages:gallery_show.empty_description')}
                    />
                ) : (
                    <div className="grid grid-cols-2 gap-3 md:grid-cols-3 lg:grid-cols-4">
                        {photos.map((photo, i) => (
                            <motion.button
                                key={photo.id}
                                onClick={() => setOpenIndex(i)}
                                initial={{ opacity: 0, scale: 0.96 }}
                                whileInView={{ opacity: 1, scale: 1 }}
                                viewport={{ once: true, margin: '-40px' }}
                                transition={{ duration: 0.4, delay: (i % 12) * 0.03 }}
                                className="group relative aspect-square overflow-hidden rounded-xl border border-border bg-card focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-crimson"
                            >
                                <SmartImage
                                    src={`/storage/${photo.image}`}
                                    alt={photo.caption ?? ''}
                                    className="group-hover:scale-110"
                                />
                                <div className="absolute inset-0 bg-gradient-to-t from-obsidian/60 via-transparent to-transparent opacity-0 transition-opacity group-hover:opacity-100" />
                            </motion.button>
                        ))}
                    </div>
                )}
            </section>

            <AnimatePresence>
                {openIndex !== null && photos[openIndex] && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        onClick={() => setOpenIndex(null)}
                        className="fixed inset-0 z-[100] flex items-center justify-center bg-obsidian/95 backdrop-blur-lg"
                    >
                        <button
                            onClick={() => setOpenIndex(null)}
                            className="absolute right-6 top-6 rounded-full border border-border/40 bg-card/40 p-3 text-foreground hover:bg-card"
                        >
                            <X className="h-5 w-5" />
                        </button>

                        <button
                            onClick={(e) => {
                                e.stopPropagation();
                                setOpenIndex((i) =>
                                    i === null ? 0 : (i - 1 + photos.length) % photos.length
                                );
                            }}
                            className="absolute left-6 top-1/2 -translate-y-1/2 rounded-full border border-border/40 bg-card/40 p-3 text-foreground hover:bg-card"
                        >
                            <ChevronLeft className="h-5 w-5" />
                        </button>
                        <button
                            onClick={(e) => {
                                e.stopPropagation();
                                setOpenIndex((i) =>
                                    i === null ? 0 : (i + 1) % photos.length
                                );
                            }}
                            className="absolute right-6 top-1/2 -translate-y-1/2 rounded-full border border-border/40 bg-card/40 p-3 text-foreground hover:bg-card"
                        >
                            <ChevronRight className="h-5 w-5" />
                        </button>

                        <motion.figure
                            key={photos[openIndex].id}
                            initial={{ scale: 0.95, opacity: 0 }}
                            animate={{ scale: 1, opacity: 1 }}
                            exit={{ scale: 0.95, opacity: 0 }}
                            transition={{ duration: 0.2 }}
                            onClick={(e) => e.stopPropagation()}
                            className="relative max-h-[85vh] max-w-[90vw]"
                        >
                            <img
                                src={`/storage/${photos[openIndex].image}`}
                                alt={photos[openIndex].caption ?? ''}
                                className="max-h-[85vh] max-w-[90vw] rounded-2xl object-contain shadow-2xl"
                            />
                            {photos[openIndex].caption && (
                                <figcaption className="mt-3 text-center text-sm text-bone">
                                    {photos[openIndex].caption}
                                </figcaption>
                            )}
                            <div className="mt-2 text-center font-mono text-xs uppercase tracking-widest text-muted-foreground">
                                {openIndex + 1} / {photos.length}
                            </div>
                        </motion.figure>
                    </motion.div>
                )}
            </AnimatePresence>
        </SiteLayout>
    );
}
