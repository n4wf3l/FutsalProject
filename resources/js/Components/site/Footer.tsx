import { Link } from '@inertiajs/react';
import { Mail, MapPin, Phone } from 'lucide-react';
import { Logo } from './Logo';

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

const LINKS = {
    Club: [
        { label: 'Notre histoire', href: '/about' },
        { label: 'Équipes', href: '/teams' },
        { label: 'Staff & Coachs', href: '/coaches' },
        { label: 'Sponsors', href: '/sponsors' },
    ],
    Compétition: [
        { label: 'Calendrier', href: '/calendar' },
        { label: 'Résultats', href: '/news' },
        { label: 'Classement', href: '/teams' },
    ],
    Fans: [
        { label: 'Fanshop', href: '/fanshop' },
        { label: 'Galerie photos', href: '/galleries' },
        { label: 'Vidéos', href: '/videos' },
        { label: 'Contact', href: '/contact' },
    ],
};

export function Footer() {
    return (
        <footer className="relative mt-32 border-t border-border bg-card/40">
            <div className="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-crimson to-transparent" />

            <div className="mx-auto max-w-7xl px-4 py-16">
                <div className="grid gap-12 lg:grid-cols-[1.4fr_1fr_1fr_1fr]">
                    <div>
                        <Logo />
                        <p className="mt-6 max-w-sm text-sm leading-relaxed text-muted-foreground">
                            Club de futsal de Kénitra, fondé en 2011. Passion, engagement et
                            excellence sur le parquet marocain.
                        </p>

                        <div className="mt-6 flex items-center gap-2">
                            <SocialLink href="#" icon={FacebookIcon} label="Facebook" />
                            <SocialLink href="#" icon={InstagramIcon} label="Instagram" />
                            <SocialLink href="#" icon={YoutubeIcon} label="YouTube" />
                        </div>
                    </div>

                    {Object.entries(LINKS).map(([title, items]) => (
                        <div key={title}>
                            <h4 className="mb-4 font-mono text-xs font-bold uppercase tracking-[0.25em] text-champagne">
                                {title}
                            </h4>
                            <ul className="space-y-2.5">
                                {items.map((item) => (
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
                        <span>Kénitra, Maroc</span>
                    </div>
                    <div className="flex items-center gap-2">
                        <Mail className="h-4 w-4 text-crimson" />
                        <a href="mailto:contact@dinakenitrafc.ma" className="hover:text-foreground">
                            contact@dinakenitrafc.ma
                        </a>
                    </div>
                    <div className="flex items-center gap-2 sm:justify-end">
                        <Phone className="h-4 w-4 text-crimson" />
                        <span>+212 000 000 000</span>
                    </div>
                </div>

                <div className="mt-8 flex flex-col items-center justify-between gap-2 border-t border-border pt-6 text-xs text-muted-foreground sm:flex-row">
                    <p>© {new Date().getFullYear()} Dina Kenitra FC — Tous droits réservés.</p>
                    <div className="flex flex-wrap items-center gap-3">
                        <Link href="/legal" className="hover:text-foreground">Mentions légales</Link>
                        <span className="opacity-30">·</span>
                        <Link href="/confidentialite" className="hover:text-foreground">Confidentialité</Link>
                        <span className="opacity-30">·</span>
                        <span className="font-mono uppercase tracking-widest">EST. 2011</span>
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
