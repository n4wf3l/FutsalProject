import { useState } from 'react';
import { SEO } from '@/Components/SEO';
import { AnimatePresence, motion } from 'framer-motion';
import { Play, Video as VideoIcon, X } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Pagination } from '@/Components/site/Pagination';
import { formatMatchDate } from '@/lib/utils';
import type { Paginated, Video } from '@/types/models';

interface Props {
    videos: Paginated<Video>;
}

function getYoutubeId(url: string): string | null {
    const m = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/|youtube\.com\/shorts\/)([\w-]{11})/);
    return m ? m[1] : null;
}

function getVimeoId(url: string): string | null {
    const m = url.match(/vimeo\.com\/(\d+)/);
    return m ? m[1] : null;
}

export default function Videos({ videos }: Props) {
    const [playing, setPlaying] = useState<Video | null>(null);

    const embedUrl = playing
        ? (() => {
              const yt = getYoutubeId(playing.url);
              if (yt) return `https://www.youtube.com/embed/${yt}?autoplay=1`;
              const vm = getVimeoId(playing.url);
              if (vm) return `https://player.vimeo.com/video/${vm}?autoplay=1`;
              return null;
          })()
        : null;

    return (
        <SiteLayout>
            <SEO
                title="Vidéos"
                description="Résumés de matchs, buts et moments forts de Dina Kenitra FC en vidéo."
            />

            <PageHeader
                kicker="Vidéo"
                title="En mouvement"
                subtitle="Résumés, buts, coulisses. À regarder au calme ou entre deux entraînements."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Vidéos' }]}
            />

            <section className="mx-auto max-w-7xl px-4 pb-16">
                {videos.data.length === 0 ? (
                    <EmptyState
                        icon={VideoIcon}
                        title="Aucune vidéo disponible"
                        description="Les prochaines vidéos du club seront publiées ici."
                    />
                ) : (
                    <>
                        <div className="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {videos.data.map((video, i) => (
                                <VideoCard
                                    key={video.id}
                                    video={video}
                                    index={i}
                                    onPlay={() => setPlaying(video)}
                                />
                            ))}
                        </div>

                        <div className="mt-12 flex items-center justify-between gap-4">
                            <div className="text-sm text-muted-foreground">
                                {videos.from ?? 0}–{videos.to ?? 0} sur {videos.total}
                            </div>
                            <Pagination links={videos.links} />
                        </div>
                    </>
                )}
            </section>

            <AnimatePresence>
                {playing && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        onClick={() => setPlaying(null)}
                        className="fixed inset-0 z-[100] flex items-center justify-center bg-obsidian/95 p-6 backdrop-blur-lg"
                    >
                        <button
                            onClick={() => setPlaying(null)}
                            className="absolute right-6 top-6 rounded-full border border-border/40 bg-card/40 p-3 text-foreground hover:bg-card"
                        >
                            <X className="h-5 w-5" />
                        </button>
                        <motion.div
                            initial={{ scale: 0.9, opacity: 0 }}
                            animate={{ scale: 1, opacity: 1 }}
                            exit={{ scale: 0.9, opacity: 0 }}
                            transition={{ duration: 0.2 }}
                            onClick={(e) => e.stopPropagation()}
                            className="w-full max-w-5xl overflow-hidden rounded-2xl border border-border bg-card shadow-2xl"
                        >
                            {embedUrl ? (
                                <iframe
                                    src={embedUrl}
                                    className="aspect-video w-full"
                                    allow="autoplay; encrypted-media; picture-in-picture"
                                    allowFullScreen
                                    title={playing.title}
                                />
                            ) : (
                                <video
                                    src={playing.url}
                                    controls
                                    autoPlay
                                    className="aspect-video w-full bg-black"
                                />
                            )}
                            <div className="p-5">
                                <h3 className="font-display text-xl font-semibold">
                                    {playing.title}
                                </h3>
                                {playing.description && (
                                    <p className="mt-2 text-sm text-muted-foreground">
                                        {playing.description}
                                    </p>
                                )}
                            </div>
                        </motion.div>
                    </motion.div>
                )}
            </AnimatePresence>
        </SiteLayout>
    );
}

function VideoCard({
    video,
    index,
    onPlay,
}: {
    video: Video;
    index: number;
    onPlay: () => void;
}) {
    const date = formatMatchDate(video.created_at);
    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: (index % 6) * 0.06 }}
            className="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40"
        >
            <button onClick={onPlay} className="block w-full text-left">
                <div className="relative aspect-video overflow-hidden bg-muted">
                    {video.image ? (
                        <img
                            src={`/storage/${video.image}`}
                            alt={video.title}
                            loading="lazy"
                            className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center">
                            <VideoIcon className="h-12 w-12 text-muted-foreground/30" />
                        </div>
                    )}
                    <div className="absolute inset-0 bg-obsidian/30 transition-opacity group-hover:bg-obsidian/50" />
                    <div className="absolute inset-0 flex items-center justify-center">
                        <div className="flex h-16 w-16 items-center justify-center rounded-full bg-crimson text-crimson-foreground shadow-glow-crimson transition-transform group-hover:scale-110">
                            <Play className="h-6 w-6 translate-x-0.5 fill-current" />
                        </div>
                    </div>
                </div>
                <div className="p-5">
                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {date.day} {date.month} {date.year}
                    </div>
                    <h3 className="mt-1 font-display text-lg font-semibold leading-tight transition-colors group-hover:text-crimson">
                        {video.title}
                    </h3>
                    {video.description && (
                        <p className="mt-2 line-clamp-2 text-sm text-muted-foreground">
                            {video.description}
                        </p>
                    )}
                </div>
            </button>
        </motion.article>
    );
}
