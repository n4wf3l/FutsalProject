import { useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import {
    Calendar,
    ChevronRight,
    FileText,
    Image as ImageIcon,
    Menu,
    Mic,
    Newspaper,
    Ticket,
    Trophy,
    UserCog,
    Users,
    Video,
    X,
    type LucideIcon,
} from 'lucide-react';
import { Logo } from './Logo';
import { ThemeToggle } from './ThemeToggle';
import { Button } from '@/Components/ui/Button';
import { MegaMenu, type MegaItem } from './MegaMenu';
import { cn } from '@/lib/utils';

type NavGroup =
    | { kind: 'link'; label: string; href: string; hrefMatches: string[] }
    | { kind: 'menu'; label: string; items: MegaItem[]; hrefMatches: string[] };

const NAV: NavGroup[] = [
    { kind: 'link', label: 'Accueil', href: '/', hrefMatches: ['/'] },
    {
        kind: 'menu',
        label: "L'équipe",
        hrefMatches: ['/teams', '/coaches', '/staff', '/about'],
        items: [
            { label: 'Effectif', href: '/teams', icon: Users, description: 'Joueurs, staff et coach' },
            { label: 'Notre histoire', href: '/about', icon: Trophy, description: 'Le club depuis 2011' },
        ],
    },
    {
        kind: 'menu',
        label: 'Compétition',
        hrefMatches: ['/calendar', '/games'],
        items: [
            { label: 'Calendrier', href: '/calendar', icon: Calendar, description: 'Matchs à venir et résultats' },
            { label: 'Classement', href: '/calendar', icon: Trophy, description: 'Tableau du championnat' },
        ],
    },
    {
        kind: 'menu',
        label: 'Média',
        hrefMatches: ['/news', '/interviews', '/galleries', '/videos', '/articles'],
        items: [
            { label: 'Actualités', href: '/news', icon: Newspaper, description: 'News du club' },
            { label: 'La Voix du Futsal', href: '/interviews', icon: Mic, description: 'Interviews du futsal marocain' },
            { label: 'Galerie', href: '/galleries', icon: ImageIcon, description: 'Les photos du club' },
            { label: 'Vidéos', href: '/videos', icon: Video, description: 'Résumés et moments forts' },
        ],
    },
    { kind: 'link', label: 'Fanshop', href: '/fanshop', hrefMatches: ['/fanshop'] },
];

// Mobile flat list (all links, no dropdown)
const MOBILE_NAV: Array<{ label: string; href: string; icon: LucideIcon }> = [
    { label: 'Accueil', href: '/', icon: Trophy },
    { label: 'Effectif', href: '/teams', icon: Users },
    { label: 'Calendrier', href: '/calendar', icon: Calendar },
    { label: 'Actualités', href: '/news', icon: Newspaper },
    { label: 'La Voix du Futsal', href: '/interviews', icon: Mic },
    { label: 'Galerie', href: '/galleries', icon: ImageIcon },
    { label: 'Vidéos', href: '/videos', icon: Video },
    { label: 'Fanshop', href: '/fanshop', icon: Ticket },
    { label: 'Notre histoire', href: '/about', icon: FileText },
    { label: 'Contact', href: '/contact', icon: UserCog },
];

export function Navbar() {
    const { url } = usePage();
    const [scrolled, setScrolled] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 12);
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    useEffect(() => {
        setMobileOpen(false);
    }, [url]);

    const isActive = (matches: string[]) =>
        matches.some((m) => (m === '/' ? url === '/' : url.startsWith(m)));

    return (
        <header
            className={cn(
                'fixed inset-x-0 top-0 z-50 transition-all duration-300',
                scrolled ? 'py-2' : 'py-4'
            )}
        >
            <div className="mx-auto max-w-7xl px-4">
                <div
                    className={cn(
                        'flex h-16 items-center justify-between rounded-2xl px-4 transition-all duration-300',
                        scrolled ? 'glass-strong shadow-lg shadow-black/20' : 'bg-transparent'
                    )}
                >
                    <Link href="/" className="rounded-lg focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-crimson">
                        <Logo />
                    </Link>

                    <nav className="hidden items-center gap-0.5 lg:flex">
                        {NAV.map((group) => {
                            const active = isActive(group.hrefMatches);
                            if (group.kind === 'link') {
                                return (
                                    <Link
                                        key={group.label}
                                        href={group.href}
                                        className={cn(
                                            'relative rounded-lg px-4 py-2 text-sm font-medium transition-colors',
                                            active
                                                ? 'text-foreground'
                                                : 'text-muted-foreground hover:text-foreground'
                                        )}
                                    >
                                        {group.label}
                                        {active && (
                                            <motion.span
                                                layoutId="nav-underline"
                                                className="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-gradient-to-r from-crimson via-champagne to-crimson"
                                                transition={{ type: 'spring', stiffness: 400, damping: 32 }}
                                            />
                                        )}
                                    </Link>
                                );
                            }
                            return (
                                <MegaMenu
                                    key={group.label}
                                    label={group.label}
                                    items={group.items}
                                    isActive={active}
                                />
                            );
                        })}
                    </nav>

                    <div className="flex items-center gap-2">
                        <ThemeToggle />
                        <Button
                            asChild
                            variant="outline"
                            size="sm"
                            className="hidden sm:inline-flex"
                        >
                            <Link href="/login">
                                Espace staff
                                <ChevronRight className="h-3.5 w-3.5" />
                            </Link>
                        </Button>
                        <button
                            type="button"
                            onClick={() => setMobileOpen((o) => !o)}
                            className="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border bg-card/60 text-foreground lg:hidden"
                            aria-label="Ouvrir le menu"
                        >
                            {mobileOpen ? <X className="h-4 w-4" /> : <Menu className="h-4 w-4" />}
                        </button>
                    </div>
                </div>
            </div>

            <AnimatePresence>
                {mobileOpen && (
                    <motion.div
                        initial={{ opacity: 0, y: -8 }}
                        animate={{ opacity: 1, y: 0 }}
                        exit={{ opacity: 0, y: -8 }}
                        transition={{ duration: 0.2 }}
                        className="absolute inset-x-4 top-full mt-2 max-h-[80vh] overflow-y-auto rounded-2xl border border-border bg-card p-2 shadow-xl shadow-black/30 lg:hidden"
                    >
                        {MOBILE_NAV.map((item) => {
                            const active = item.href === '/' ? url === '/' : url.startsWith(item.href);
                            return (
                                <Link
                                    key={item.href}
                                    href={item.href}
                                    className={cn(
                                        'flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition-colors',
                                        active
                                            ? 'bg-crimson/10 text-crimson'
                                            : 'text-foreground hover:bg-muted'
                                    )}
                                >
                                    <item.icon className="h-4 w-4 text-champagne" />
                                    <span className="flex-1">{item.label}</span>
                                    <ChevronRight className="h-4 w-4 opacity-40" />
                                </Link>
                            );
                        })}
                        <Link
                            href="/login"
                            className="mt-2 flex items-center justify-between rounded-lg border border-crimson bg-crimson px-4 py-3 text-sm font-semibold text-crimson-foreground"
                        >
                            Espace staff
                            <ChevronRight className="h-4 w-4" />
                        </Link>
                    </motion.div>
                )}
            </AnimatePresence>
        </header>
    );
}
