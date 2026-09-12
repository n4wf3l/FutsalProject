import { Link } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { motion } from 'framer-motion';
import { ArrowRight, Building2, CalendarCheck2, Globe2, Handshake, Mail, MapPin, Users } from 'lucide-react';
import { SEO } from '@/Components/SEO';
import { breadcrumbLd } from '@/lib/seo';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Badge } from '@/Components/ui/Badge';
import { Button } from '@/Components/ui/Button';

interface Sponsor {
    id: number;
    name: string;
    logo: string | null;
    website: string | null;
}

interface Props {
    sponsors: Sponsor[];
}

export default function Partenaires({ sponsors }: Props) {
    const { t } = useTranslation(['pages', 'nav']);

    const crumbs = [
        { label: t('nav:items.home'), href: '/' },
        { label: t('pages:partenaires.breadcrumb') },
    ];

    return (
        <SiteLayout>
            <SEO
                title={t('pages:partenaires.seo_title')}
                description={t('pages:partenaires.seo_description')}
                jsonLd={breadcrumbLd(crumbs)}
            />

            <PageHeader
                kicker={t('pages:partenaires.kicker')}
                title={t('pages:partenaires.title')}
                subtitle={t('pages:partenaires.subtitle')}
                breadcrumb={crumbs}
                variant="editorial"
            />

            {/* Current partners */}
            <section className="mx-auto max-w-7xl px-4 pb-12">
                <div className="mb-6 flex items-baseline justify-between gap-3 border-b border-border pb-3 sm:mb-8">
                    <h2 className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne sm:text-sm">
                        {t('pages:partenaires.current_kicker')}
                    </h2>
                    <span className="font-mono text-[11px] text-muted-foreground">
                        {sponsors.length}
                    </span>
                </div>

                {sponsors.length === 0 ? (
                    <EmptyState
                        icon={Handshake}
                        title={t('pages:partenaires.empty_title')}
                        description={t('pages:partenaires.empty_description')}
                    />
                ) : (
                    <div className="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
                        {sponsors.map((sponsor, i) => (
                            <SponsorCard key={sponsor.id} sponsor={sponsor} index={i} />
                        ))}
                    </div>
                )}
            </section>

            {/* Partner with us CTA */}
            <section className="mx-auto max-w-7xl px-4 pb-16">
                <div className="rounded-2xl border border-champagne/30 bg-card">
                    <div className="grid gap-8 p-6 sm:p-10 lg:grid-cols-[1.3fr_1fr] lg:gap-12 lg:p-14">
                        <div>
                            <Badge variant="champagne" className="mb-4">
                                <Handshake className="h-3 w-3" />
                                {t('pages:partenaires.cta_badge')}
                            </Badge>
                            <h2 className="font-editorial text-3xl italic leading-tight text-foreground sm:text-4xl lg:text-5xl">
                                {t('pages:partenaires.cta_title')}
                            </h2>
                            <p className="mt-5 max-w-2xl text-base leading-relaxed text-muted-foreground sm:text-lg">
                                {t('pages:partenaires.cta_body')}
                            </p>

                            <div className="mt-8 grid gap-4 sm:grid-cols-2">
                                <Perk
                                    icon={Globe2}
                                    title={t('pages:partenaires.perk_friendly_title')}
                                    body={t('pages:partenaires.perk_friendly_body')}
                                />
                                <Perk
                                    icon={CalendarCheck2}
                                    title={t('pages:partenaires.perk_camp_title')}
                                    body={t('pages:partenaires.perk_camp_body')}
                                />
                                <Perk
                                    icon={Users}
                                    title={t('pages:partenaires.perk_youth_title')}
                                    body={t('pages:partenaires.perk_youth_body')}
                                />
                                <Perk
                                    icon={Building2}
                                    title={t('pages:partenaires.perk_brand_title')}
                                    body={t('pages:partenaires.perk_brand_body')}
                                />
                            </div>

                            <div className="mt-10 flex flex-wrap items-center gap-3">
                                <Button asChild size="lg">
                                    <Link href="/contact">
                                        <Mail className="h-4 w-4" />
                                        {t('pages:partenaires.cta_button')}
                                        <ArrowRight className="h-4 w-4" />
                                    </Link>
                                </Button>
                                <a
                                    href="mailto:contact@dinakenitrafc.ma?subject=Partenariat%20Dina%20Kenitra%20FC"
                                    className="inline-flex items-center gap-2 text-sm font-semibold text-champagne hover:underline"
                                >
                                    contact@dinakenitrafc.ma
                                </a>
                            </div>
                        </div>

                        <aside className="rounded-2xl border border-border bg-background/40 p-6 sm:p-8">
                            <div className="font-mono text-[10px] font-semibold uppercase tracking-[0.28em] text-champagne">
                                {t('pages:partenaires.venue_kicker')}
                            </div>
                            <h3 className="mt-2 font-display text-xl font-semibold sm:text-2xl">
                                Salle Al Wahda
                            </h3>
                            <ul className="mt-5 space-y-3 text-sm">
                                <VenueRow icon={Users} label={t('pages:partenaires.venue_capacity')} value="2 500" />
                                <VenueRow icon={MapPin} label={t('pages:partenaires.venue_city')} value="Kénitra, Maroc" />
                                <VenueRow icon={Globe2} label={t('pages:partenaires.venue_reach')} value={t('pages:partenaires.venue_reach_value')} />
                            </ul>
                            <p className="mt-6 border-t border-border pt-4 text-xs leading-relaxed text-muted-foreground">
                                {t('pages:partenaires.venue_note')}
                            </p>
                        </aside>
                    </div>
                </div>
            </section>
        </SiteLayout>
    );
}

function SponsorCard({ sponsor, index }: { sponsor: Sponsor; index: number }) {
    const inner = (
        <>
            <div className="flex h-24 w-full items-center justify-center overflow-hidden rounded-lg bg-background/60 p-4">
                {sponsor.logo ? (
                    <img
                        src={sponsor.logo}
                        alt={sponsor.name}
                        loading="lazy"
                        className="max-h-full max-w-full object-contain opacity-90 transition-opacity group-hover:opacity-100"
                    />
                ) : (
                    <div className="text-center font-display text-sm font-semibold text-muted-foreground">
                        {sponsor.name}
                    </div>
                )}
            </div>
            <div className="mt-3 truncate text-center font-display text-sm font-semibold text-foreground">
                {sponsor.name}
            </div>
        </>
    );

    const className =
        'group flex flex-col rounded-2xl border border-border bg-card p-4 transition-all hover:border-champagne/50 sm:p-5';

    return (
        <motion.div
            initial={{ opacity: 0, y: 12 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ delay: (index % 8) * 0.04 }}
        >
            {sponsor.website ? (
                <a href={sponsor.website} target="_blank" rel="noopener noreferrer" className={className}>
                    {inner}
                </a>
            ) : (
                <div className={className}>{inner}</div>
            )}
        </motion.div>
    );
}

function Perk({
    icon: Icon,
    title,
    body,
}: {
    icon: React.ComponentType<{ className?: string }>;
    title: string;
    body: string;
}) {
    return (
        <div className="rounded-xl border border-border bg-background/40 p-4">
            <div className="mb-2 inline-flex h-8 w-8 items-center justify-center rounded-lg border border-champagne/30 bg-champagne/10 text-champagne">
                <Icon className="h-4 w-4" />
            </div>
            <div className="font-display text-sm font-semibold">{title}</div>
            <p className="mt-1 text-xs leading-relaxed text-muted-foreground">{body}</p>
        </div>
    );
}

function VenueRow({
    icon: Icon,
    label,
    value,
}: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string;
}) {
    return (
        <li className="flex items-center gap-3">
            <span className="inline-flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border border-border bg-card text-champagne">
                <Icon className="h-3.5 w-3.5" />
            </span>
            <span className="flex flex-1 items-baseline justify-between gap-3">
                <span className="text-xs uppercase tracking-widest text-muted-foreground">{label}</span>
                <span className="font-display text-sm font-semibold text-foreground">{value}</span>
            </span>
        </li>
    );
}
