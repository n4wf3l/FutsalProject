import { useMemo } from 'react';
import { usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { breadcrumbLd } from '@/lib/seo';
import { motion } from 'framer-motion';
import { Download, FileText, MapPin, Trophy, Users } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Badge } from '@/Components/ui/Badge';
import type { AboutSection, ClubInfoShared, Regulation } from '@/types/models';

interface Props {
    regulations: Regulation[];
    sections: AboutSection[];
}

function sanitizeContent(html: string): string {
    if (!html) return '';
    let out = html;
    out = out.replace(/[\p{Extended_Pictographic}‍️]/gu, '');
    out = out.replace(/[✔✓●○►▪◆■□]/g, '');
    out = out.replace(/<p[^>]*>(?:\s|&nbsp;|&#160;| |<br\s*\/?>)*<\/p>/gi, '');
    out = out.replace(/(<br\s*\/?>\s*){2,}/gi, '<br>');
    out = out.replace(/<p([^>]*)>\s+/gi, '<p$1>');
    out = out.replace(/\s+<\/p>/gi, '</p>');
    out = out.replace(/(<li[^>]*>)\s*[-•·]\s*/gi, '$1');
    return out.trim();
}

function slugify(input: string): string {
    return input
        .toLowerCase()
        .normalize('NFD')
        .replace(/[̀-ͯ]/g, '')
        .replace(/[^a-z0-9]+/g, '-')
        .replace(/^-+|-+$/g, '');
}

export default function About({ regulations, sections }: Props) {
    const { t } = useTranslation(['pages', 'nav']);
    const { props } = usePage<{ club: ClubInfoShared }>();
    const club = props.club;

    const crumbs = [
        { label: t('nav:items.home'), href: '/' },
        { label: t('pages:about.breadcrumb') },
    ];

    const chapters = useMemo(
        () =>
            sections.map((section, i) => ({
                ...section,
                index: i + 1,
                number: String(i + 1).padStart(2, '0'),
                slug: `chapitre-${i + 1}-${slugify(section.title)}`,
                html: sanitizeContent(section.content),
            })),
        [sections]
    );

    return (
        <SiteLayout>
            <SEO
                title={t('pages:about.seo_title')}
                description={t('pages:about.seo_description')}
                jsonLd={breadcrumbLd(crumbs)}
            />

            <PageHeader
                kicker={t('pages:about.kicker')}
                title={t('pages:about.title')}
                subtitle={t('pages:about.subtitle')}
                breadcrumb={crumbs}
                variant="editorial"
            />

            {/* Identity block */}
            <section className="mx-auto max-w-7xl px-4 pb-10">
                <div className="rounded-2xl border border-border bg-card">
                    <div className="grid gap-6 p-6 sm:p-8 lg:grid-cols-[auto_1fr] lg:items-center lg:gap-12 lg:p-12">
                        <img
                            src="/logo-dinakenitra.png"
                            alt="Dina Kenitra FC"
                            className="h-24 w-24 sm:h-32 sm:w-32 lg:h-40 lg:w-40"
                        />
                        <div>
                            <Badge variant="champagne" className="mb-4">
                                <Trophy className="h-3 w-3" />
                                {t('pages:about.identity_badge')}
                            </Badge>
                            <h2 className="text-foreground">
                                <span className="block font-display text-3xl leading-none sm:text-4xl lg:text-display-lg">Dina Kenitra</span>
                                <span className="mt-1 block font-editorial text-2xl italic text-champagne sm:text-4xl lg:text-5xl">
                                    Futsal Club
                                </span>
                            </h2>
                            <p className="mt-5 max-w-2xl text-sm leading-relaxed text-muted-foreground sm:text-base">
                                {t('pages:about.identity_body', { city: club?.city ?? 'Kénitra' })}
                            </p>
                        </div>
                    </div>
                    <div className="grid divide-y divide-border border-t border-border sm:grid-cols-3 sm:divide-x sm:divide-y-0">
                        {club?.president && (
                            <InfoStrip icon={Users} label={t('pages:about.identity_president')} value={club.president} />
                        )}
                        <InfoStrip
                            icon={MapPin}
                            label={t('pages:about.identity_city')}
                            value={club?.city ?? 'Kénitra, Maroc'}
                        />
                        <InfoStrip icon={Trophy} label={t('pages:about.identity_since')} value="2011" />
                    </div>
                </div>
            </section>

            {/* Chapter navigation */}
            {chapters.length > 1 && (
                <section className="mx-auto max-w-4xl px-4 pb-4">
                    <div className="rounded-2xl border border-border bg-card p-5">
                        <div className="mb-3 font-mono text-[10px] uppercase tracking-[0.28em] text-champagne">
                            {t('pages:about.chapters_kicker')}
                        </div>
                        <ol className="flex flex-col gap-1">
                            {chapters.map((c) => (
                                <li key={c.id}>
                                    <a
                                        href={`#${c.slug}`}
                                        className="group flex items-baseline gap-4 py-1.5 text-sm transition-colors hover:text-crimson"
                                    >
                                        <span className="w-8 shrink-0 font-mono text-[11px] text-muted-foreground group-hover:text-crimson">
                                            {c.number}
                                        </span>
                                        <span className="font-display font-medium text-foreground group-hover:text-crimson">
                                            {c.title}
                                        </span>
                                    </a>
                                </li>
                            ))}
                        </ol>
                    </div>
                </section>
            )}

            {/* Chapters */}
            {chapters.length > 0 && (
                <section className="mx-auto max-w-4xl px-4 py-10">
                    <div className="divide-y divide-border">
                        {chapters.map((chapter, i) => (
                            <motion.article
                                id={chapter.slug}
                                key={chapter.id}
                                initial={{ opacity: 0, y: 16 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true, margin: '-40px' }}
                                transition={{ duration: 0.4, delay: (i % 4) * 0.05 }}
                                className="scroll-mt-24 py-12 first:pt-0 last:pb-0"
                            >
                                <header className="mb-6 flex items-baseline gap-4">
                                    <span className="font-editorial text-3xl italic text-champagne/70 sm:text-5xl lg:text-6xl">
                                        {chapter.number}
                                    </span>
                                    <div>
                                        <div className="font-mono text-[10px] uppercase tracking-[0.28em] text-champagne">
                                            {t('pages:about.chapter_kicker')}
                                        </div>
                                        <h3 className="mt-1 font-display text-xl font-semibold leading-tight text-foreground sm:text-3xl lg:text-4xl">
                                            {chapter.title}
                                        </h3>
                                    </div>
                                </header>
                                <div
                                    className="prose-content text-base leading-relaxed text-foreground/85 sm:text-lg [&_p]:mb-3 [&_p:last-child]:mb-0 [&_h2]:mt-8 [&_h2]:mb-3 [&_h2]:font-display [&_h2]:text-2xl [&_h2]:font-semibold [&_h2]:text-foreground [&_h3]:mt-6 [&_h3]:mb-2 [&_h3]:font-display [&_h3]:text-xl [&_h3]:font-semibold [&_h3]:text-foreground [&_ul]:my-3 [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:my-3 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:my-1 [&_a]:text-crimson [&_a]:underline [&_a]:underline-offset-2 [&_blockquote]:my-6 [&_blockquote]:border-l-2 [&_blockquote]:border-champagne/60 [&_blockquote]:pl-4 [&_blockquote]:italic [&_blockquote]:text-champagne [&_strong]:text-foreground"
                                    dangerouslySetInnerHTML={{ __html: chapter.html }}
                                />
                            </motion.article>
                        ))}
                    </div>
                </section>
            )}

            {/* Regulations */}
            <section className="mx-auto max-w-4xl px-4 pb-16">
                <div className="mb-8">
                    <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                        {t('pages:about.regulations_kicker')}
                    </div>
                    <h2 className="mt-3 font-display text-display-lg">{t('pages:about.regulations_title')}</h2>
                </div>

                {regulations.length === 0 ? (
                    <EmptyState
                        icon={FileText}
                        title={t('pages:about.regulations_empty_title')}
                        description={t('pages:about.regulations_empty_description')}
                    />
                ) : (
                    <ul className="grid gap-3 sm:grid-cols-2">
                        {regulations.map((reg) => (
                            <li key={reg.id}>
                                <a
                                    href={`/storage/${reg.pdf_path}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex items-center gap-4 rounded-2xl border border-border bg-card p-5 transition-all hover:border-crimson/40"
                                >
                                    <div className="rounded-full border border-border bg-background p-3">
                                        <FileText className="h-4 w-4 text-crimson" />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <div className="font-mono text-[10px] uppercase tracking-widest text-champagne">
                                            PDF
                                        </div>
                                        <div className="truncate font-display font-semibold transition-colors group-hover:text-crimson">
                                            {reg.title}
                                        </div>
                                    </div>
                                    <Download className="h-4 w-4 text-muted-foreground transition-colors group-hover:text-crimson" />
                                </a>
                            </li>
                        ))}
                    </ul>
                )}
            </section>
        </SiteLayout>
    );
}

function InfoStrip({
    icon: Icon,
    label,
    value,
}: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string;
}) {
    return (
        <div className="p-5">
            <div className="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                <Icon className="h-3 w-3 text-champagne" />
                {label}
            </div>
            <div className="mt-1 font-display text-base font-semibold">{value}</div>
        </div>
    );
}
