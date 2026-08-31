import { Link, usePage } from '@inertiajs/react';
import { Mail, MapPin, Phone } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { Logo } from './Logo';
import type { ClubInfoShared, FlashMessage, SponsorShared } from '@/types/models';

const FacebookIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...props}>
        <path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c5.05-.5 9-4.76 9-9.95z" />
    </svg>
);
const InstagramIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" strokeWidth="2" strokeLinecap="round" strokeLinejoin="round" {...props}>
        <rect width="20" height="20" x="2" y="2" rx="5" ry="5" />
        <path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z" />
        <line x1="17.5" x2="17.51" y1="6.5" y2="6.5" />
    </svg>
);
const YoutubeIcon = (props: React.SVGProps<SVGSVGElement>) => (
    <svg viewBox="0 0 24 24" fill="currentColor" {...props}>
        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
    </svg>
);

export function Footer() {
    const { t } = useTranslation('nav');
    const { t: tCommon } = useTranslation('common');
    const { props } = usePage<{
        club: ClubInfoShared;
        flashMessage: FlashMessage | null;
        sponsors: SponsorShared[];
    }>();
    const club = props.club;
    const slogan = props.flashMessage?.homemessage ?? null;
    const sponsors = (props.sponsors ?? []).filter((s) => s.logo);

    const groups = [
        {
            title: t('footer.col_club'),
            items: [
                { label: t('footer.club_history'), href: '/about' },
                { label: t('footer.club_teams'), href: '/teams' },
                { label: t('footer.club_staff'), href: '/coaches' },
            ],
        },
        {
            title: t('footer.col_competition'),
            items: [
                { label: t('footer.competition_calendar'), href: '/calendar' },
                { label: t('footer.competition_results'), href: '/news' },
                { label: t('footer.competition_standings'), href: '/teams' },
            ],
        },
        {
            title: t('footer.col_fans'),
            items: [
                { label: t('footer.fans_fanshop'), href: '/fanshop' },
                { label: t('footer.fans_gallery'), href: '/galleries' },
                { label: t('footer.fans_videos'), href: '/videos' },
                { label: t('footer.fans_contact'), href: '/contact' },
            ],
        },
    ];

    return (
        <footer className="relative mt-32 border-t border-border bg-card/40">
            <div className="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-crimson to-transparent" />

            {sponsors.length > 0 && (
                <div className="border-b border-border/60">
                    <div className="mx-auto max-w-7xl px-4 py-12">
                        <div className="mb-8 flex flex-col items-center text-center">
                            <span className="font-mono text-[10px] font-semibold uppercase tracking-[0.3em] text-champagne">
                                {t('footer.partners_kicker')}
                            </span>
                            <h3 className="mt-2 font-editorial text-2xl italic text-foreground/80">
                                {t('footer.partners_title')}
                            </h3>
                        </div>
                        <ul className="flex flex-wrap items-center justify-center gap-x-14 gap-y-8">
                            {sponsors.map((s) => (
                                <li key={s.id}>
                                    <SponsorLogo sponsor={s} />
                                </li>
                            ))}
                        </ul>
                    </div>
                </div>
            )}

            <div className="mx-auto max-w-7xl px-4 py-16">
                <div className="grid gap-12 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
                    <div>
                        <Logo />
                        {slogan && (
                            <p className="mt-5 max-w-sm font-editorial text-lg italic text-champagne">
                                {slogan}
                            </p>
                        )}
                        <p className="mt-4 max-w-sm text-sm leading-relaxed text-muted-foreground">
                            {t('footer.tagline')}
                        </p>

                        {(club?.federation_logo || club?.organization_logo) && (
                            <div className="mt-6 flex flex-wrap items-center gap-5">
                                {club.federation_logo && (
                                    <FederationLogo
                                        src={club.federation_logo}
                                        alt="Fédération Royale Marocaine de Football"
                                    />
                                )}
                                {club.organization_logo && (
                                    <FederationLogo
                                        src={club.organization_logo}
                                        alt="Ligue"
                                    />
                                )}
                            </div>
                        )}

                        <div className="mt-6 flex items-center gap-2">
                            <SocialLink href={club?.facebook ?? '#'} icon={FacebookIcon} label="Facebook" />
                            <SocialLink href={club?.instagram ?? '#'} icon={InstagramIcon} label="Instagram" />
                            <SocialLink href="#" icon={YoutubeIcon} label="YouTube" />
                        </div>
                    </div>

                    {groups.map((group) => (
                        <div key={group.title}>
                            <h4 className="mb-4 font-mono text-xs font-bold uppercase tracking-[0.25em] text-champagne">
                                {group.title}
                            </h4>
                            <ul className="space-y-2.5">
                                {group.items.map((item) => (
                                    <li key={item.href}>
                                        <Link
                                            href={item.href}
                                            className="text-sm text-muted-foreground transition-colors hover:text-foreground"
                                        >
                                            {item.label}
                                        </Link>
                                    </li>
                                ))}
                            </ul>
                        </div>
                    ))}
                </div>

                <div className="mt-16 grid gap-4 border-t border-border pt-8 text-sm text-muted-foreground sm:grid-cols-3">
                    <div className="flex items-center gap-2">
                        <MapPin className="h-4 w-4 text-crimson" />
                        <span>{club?.city ?? 'Kénitra'}, {t('footer.country_morocco')}</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <Mail className="h-4 w-4 text-crimson" />
                        <a href={`mailto:${club?.email ?? 'contact@dinakenitrafc.ma'}`} className="hover:text-foreground">
                            {club?.email ?? 'contact@dinakenitrafc.ma'}
                        </a>
                    </div>
                    <div className="flex items-center gap-2 sm:justify-end">
                        <Phone className="h-4 w-4 text-crimson" />
                        <span>{club?.phone ?? '+212 000 000 000'}</span>
                    </div>
                </div>

                <div className="mt-8 flex flex-col items-center justify-between gap-2 border-t border-border pt-6 text-xs text-muted-foreground sm:flex-row">
                    <p>{t('footer.rights', { year: new Date().getFullYear() })}</p>
                    <div className="flex flex-wrap items-center gap-3">
                        <Link href="/legal" className="hover:text-foreground">{t('footer.legal')}</Link>
                        <span className="opacity-30">·</span>
                        <Link href="/confidentialite" className="hover:text-foreground">{t('footer.privacy')}</Link>
                        <span className="opacity-30">·</span>
                        <span className="font-mono uppercase tracking-widest">{tCommon('brand.est')}</span>
                    </div>
                </div>
            </div>
        </footer>
    );
}

function SocialLink({
    href,
    icon: Icon,
    label,
}: {
    href: string;
    icon: React.ComponentType<{ className?: string }>;
    label: string;
}) {
    return (
        <a
            href={href}
            aria-label={label}
            className="inline-flex h-9 w-9 items-center justify-center rounded-full border border-border bg-card text-muted-foreground transition-all hover:border-crimson hover:text-crimson hover:shadow-glow-crimson"
        >
            <Icon className="h-4 w-4" />
        </a>
    );
}

function FederationLogo({ src, alt }: { src: string; alt: string }) {
    return (
        <img
            src={src}
            alt={alt}
            loading="lazy"
            className="h-12 w-auto opacity-70 grayscale transition-all hover:opacity-100 hover:grayscale-0"
        />
    );
}

function SponsorLogo({ sponsor }: { sponsor: SponsorShared }) {
    const img = (
        <img
            src={sponsor.logo!}
            alt={sponsor.name}
            loading="lazy"
            className="h-12 w-auto max-w-[160px] object-contain opacity-60 grayscale transition-all group-hover:opacity-100 group-hover:grayscale-0 sm:h-14"
        />
    );
    if (sponsor.website) {
        return (
            <a
                href={sponsor.website}
                target="_blank"
                rel="noopener noreferrer"
                aria-label={sponsor.name}
                className="group inline-flex items-center"
            >
                {img}
            </a>
        );
    }
    return <span className="group inline-flex items-center">{img}</span>;
}
