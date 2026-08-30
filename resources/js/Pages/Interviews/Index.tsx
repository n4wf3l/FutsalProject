import { Link, router } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { ArrowRight, MessageSquareQuote, Mic } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Pagination } from '@/Components/site/Pagination';
import { Ornament } from '@/Components/site/Ornament';
import { SmartImage } from '@/Components/site/SmartImage';
import { Badge } from '@/Components/ui/Badge';
import { formatMatchDate, cn } from '@/lib/utils';
import type { Interview, Paginated } from '@/types/models';

interface Props {
    interviews: Paginated<Interview>;
    roles: string[];
    filter: { role: string };
}

export default function InterviewsIndex({ interviews, roles, filter }: Props) {
    const { t, i18n } = useTranslation(['pages', 'nav']);
    const setRole = (role: string) => {
        router.get(
            '/interviews',
            role ? { role } : {},
            { preserveScroll: true, preserveState: true, replace: true }
        );
    };

    const featured = interviews.data[0];
    const rest = interviews.data.slice(1);

    return (
        <SiteLayout>
            <SEO
                title={t('pages:interviews.seo_title')}
                description={t('pages:interviews.seo_description')}
            />

            <PageHeader
                kicker={t('pages:interviews.kicker')}
                kickerRight={new Date().toLocaleDateString(i18n.language, { month: 'long', year: 'numeric' })}
                title={t('pages:interviews.title')}
                subtitle={t('pages:interviews.subtitle')}
                breadcrumb={[{ label: t('nav:items.home'), href: '/' }, { label: t('pages:interviews.breadcrumb') }]}
                variant="editorial"
            />

            {/* Role filters */}
            <section className="mx-auto max-w-7xl px-4">
                <div className="flex flex-wrap items-center gap-2 border-b border-border pb-4">
                    <button
                        onClick={() => setRole('')}
                        className={cn(
                            'inline-flex items-center rounded-full border px-4 py-1.5 text-sm font-medium transition-all',
                            !filter.role
                                ? 'border-champagne bg-champagne/10 text-champagne'
                                : 'border-border text-muted-foreground hover:border-champagne/50 hover:text-foreground'
                        )}
                    >
                        {t('pages:interviews.filter_all')}
                    </button>
                    {roles.map((role) => (
                        <button
                            key={role}
                            onClick={() => setRole(role)}
                            className={cn(
                                'inline-flex items-center rounded-full border px-4 py-1.5 text-sm font-medium transition-all',
                                filter.role === role
                                    ? 'border-champagne bg-champagne/10 text-champagne'
                                    : 'border-border text-muted-foreground hover:border-champagne/50 hover:text-foreground'
                            )}
                        >
                            {role}
                        </button>
                    ))}
                </div>
            </section>

            <section className="mx-auto max-w-7xl px-4 py-12">
                {interviews.total === 0 ? (
                    <EmptyState
                        icon={Mic}
                        title={filter.role ? t('pages:interviews.empty_role_title', { role: filter.role }) : t('pages:interviews.empty_title')}
                        description={
                            filter.role
                                ? t('pages:interviews.empty_role_description')
                                : t('pages:interviews.empty_description')
                        }
                    />
                ) : (
                    <>
                        {featured && interviews.current_page === 1 && !filter.role && (
                            <FeaturedInterview interview={featured} />
                        )}

                        <div className="mt-10 grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                            {(featured && interviews.current_page === 1 && !filter.role ? rest : interviews.data).map(
                                (interview, i) => (
                                    <InterviewCard key={interview.id} interview={interview} index={i} />
                                )
                            )}
                        </div>

                        <div className="mt-12 flex items-center justify-between gap-4">
                            <div className="text-sm text-muted-foreground">
                                {t('pages:interviews.pagination_summary', {
                                    from: interviews.from ?? 0,
                                    to: interviews.to ?? 0,
                                    total: interviews.total,
                                })}
                            </div>
                            <Pagination links={interviews.links} />
                        </div>
                    </>
                )}
            </section>

            {/* Attribution strip */}
            <section className="mx-auto max-w-3xl px-4 pb-16 text-center">
                <Ornament className="mb-4" />
                <p className="font-editorial text-lg italic leading-relaxed text-muted-foreground">
                    {t('pages:interviews.attribution')}{' '}
                    <span className="font-semibold not-italic text-foreground">
                        {t('pages:interviews.attribution_bold')}
                    </span>{' '}
                    {t('pages:interviews.attribution_end')}
                </p>
            </section>
        </SiteLayout>
    );
}

function FeaturedInterview({ interview }: { interview: Interview }) {
    const { t } = useTranslation('pages');
    const date = interview.published_at ? formatMatchDate(interview.published_at) : null;

    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ duration: 0.6 }}
            className="group relative overflow-hidden rounded-3xl border border-champagne/30 bg-card"
        >
            <Link href={`/interviews/${interview.slug}`} className="grid gap-0 lg:grid-cols-[1.2fr_1fr]">
                <div className="relative aspect-[16/10] overflow-hidden bg-muted lg:aspect-auto">
                    {interview.hero_image ? (
                        <SmartImage
                            src={`/storage/${interview.hero_image}`}
                            alt=""
                            className="group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center bg-muted">
                            <Mic className="h-24 w-24 text-champagne/40" />
                        </div>
                    )}
                    <div className="absolute left-6 top-6">
                        <Badge variant="champagne">
                            <MessageSquareQuote className="h-3 w-3" />
                            {t('interviews.featured_badge')}
                        </Badge>
                    </div>
                </div>

                <div className="flex flex-col justify-center gap-6 p-8 lg:p-12">
                    <div>
                        <div className="font-mono text-[10px] uppercase tracking-[0.3em] text-champagne">
                            {interview.interviewee_role}
                            {interview.interviewee_affiliation && (
                                <> · <span className="text-muted-foreground">{interview.interviewee_affiliation}</span></>
                            )}
                        </div>
                        <h2 className="mt-3 font-editorial text-3xl font-medium leading-tight tracking-tight text-foreground lg:text-4xl">
                            {interview.title}
                        </h2>
                        <div className="mt-3 font-mono text-xs uppercase tracking-widest text-champagne">
                            {t('interviews.with_person', { name: interview.interviewee_name })}
                        </div>
                    </div>

                    {interview.quote_highlight && (
                        <blockquote className="relative">
                            <span
                                aria-hidden
                                className="absolute -left-1 -top-6 font-editorial text-6xl leading-none text-champagne/30"
                            >
                                “
                            </span>
                            <p className="font-editorial text-xl italic leading-snug text-foreground/85">
                                {interview.quote_highlight}
                            </p>
                        </blockquote>
                    )}

                    {interview.excerpt && !interview.quote_highlight && (
                        <p className="line-clamp-3 text-muted-foreground">{interview.excerpt}</p>
                    )}

                    <div className="flex items-center justify-between gap-4">
                        {date && (
                            <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                {date.day} {date.month} {date.year}
                            </div>
                        )}
                        <span className="inline-flex items-center gap-1.5 text-sm font-semibold text-champagne">
                            {t('interviews.read_interview')}
                            <ArrowRight className="h-4 w-4 transition-transform group-hover:translate-x-1" />
                        </span>
                    </div>
                </div>
            </Link>
        </motion.article>
    );
}

function InterviewCard({ interview, index }: { interview: Interview; index: number }) {
    const date = interview.published_at ? formatMatchDate(interview.published_at) : null;

    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: (index % 6) * 0.06 }}
            className="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-champagne/40"
        >
            <Link href={`/interviews/${interview.slug}`} className="block">
                <div className="relative aspect-[16/10] overflow-hidden bg-muted">
                    {interview.hero_image ? (
                        <SmartImage
                            src={`/storage/${interview.hero_image}`}
                            alt=""
                            className="group-hover:scale-105"
                        />
                    ) : (
                        <div className="flex h-full w-full items-center justify-center bg-muted">
                            <Mic className="h-16 w-16 text-champagne/30" />
                        </div>
                    )}
                    <div className="absolute inset-x-0 bottom-0 h-24 bg-gradient-to-t from-card via-card/70 to-transparent" />
                    <div className="absolute left-4 top-4">
                        <Badge variant="champagne">{interview.interviewee_role}</Badge>
                    </div>
                </div>
                <div className="p-6">
                    {date && (
                        <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                            {date.day} {date.month} {date.year}
                        </div>
                    )}
                    <h3 className="mt-2 font-editorial text-xl leading-snug transition-colors group-hover:text-champagne">
                        {interview.title}
                    </h3>
                    <div className="mt-2 flex items-center gap-2 text-sm font-semibold text-champagne">
                        {interview.interviewee_photo && (
                            <img
                                src={`/storage/${interview.interviewee_photo}`}
                                alt=""
                                className="h-6 w-6 rounded-full border border-border object-cover"
                            />
                        )}
                        {interview.interviewee_name}
                    </div>
                    {interview.excerpt && (
                        <p className="mt-3 line-clamp-2 text-sm text-muted-foreground">
                            {interview.excerpt}
                        </p>
                    )}
                </div>
            </Link>
        </motion.article>
    );
}
