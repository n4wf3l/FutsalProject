import { useEffect, useMemo, useState } from 'react';
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
import { useTranslation } from 'react-i18next';
import { Logo } from './Logo';
import { ThemeToggle } from './ThemeToggle';
import { LanguageSwitcher } from './LanguageSwitcher';
import { Button } from '@/Components/ui/Button';
import { MegaMenu, type MegaItem } from './MegaMenu';
import { cn } from '@/lib/utils';

type NavGroup =
    | { kind: 'link'; label: string; href: string; hrefMatches: string[] }
    | { kind: 'menu'; label: string; items: MegaItem[]; hrefMatches: string[] };

export function Navbar() {
    const { url } = usePage();
    const { t } = useTranslation('nav');
    const { t: tCommon } = useTranslation('common');
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

    const NAV: NavGroup[] = useMemo(
        () => [
            { kind: 'link', label: t('items.home'), href: '/', hrefMatches: ['/'] },
            {
                kind: 'menu',
                label: t('items.team_group'),
                hrefMatches: ['/teams', '/coaches', '/staff', '/about'],
                items: [
                    { label: t('items.team_roster'), href: '/teams', icon: Users, description: t('items.team_roster_desc') },
                    { label: t('items.team_history'), href: '/about', icon: Trophy, description: t('items.team_history_desc') },
                ],
            },
            {
                kind: 'menu',
                label: t('items.competition_group'),
                hrefMatches: ['/calendar', '/games'],
                items: [
                    { label: t('items.competition_calendar'), href: '/calendar', icon: Calendar, description: t('items.competition_calendar_desc') },
                ],
            },
            {
                kind: 'menu',
                label: t('items.media_group'),
                hrefMatches: ['/news', '/interviews', '/galleries', '/videos', '/articles'],
                items: [
                    { label: t('items.media_news'), href: '/news', icon: Newspaper, description: t('items.media_news_desc') },
                    { label: t('items.media_interviews'), href: '/interviews', icon: Mic, description: t('items.media_interviews_desc') },
                    { label: t('items.media_gallery'), href: '/galleries', icon: ImageIcon, description: t('items.media_gallery_desc') },
                    { label: t('items.media_videos'), href: '/videos', icon: Video, description: t('items.media_videos_desc') },
                ],
            },
            { kind: 'link', label: t('items.fanshop'), href: '/fanshop', hrefMatches: ['/fanshop'] },
        ],
        [t]
    );

    const MOBILE_NAV: Array<{ label: string; href: string; icon: LucideIcon }> = useMemo(
        () => [
            { label: t('items.home'), href: '/', icon: Trophy },
            { label: t('items.team_roster'), href: '/teams', icon: Users },
            { label: t('items.competition_calendar'), href: '/calendar', icon: Calendar },
            { label: t('items.media_news'), href: '/news', icon: Newspaper },
            { label: t('items.media_interviews'), href: '/interviews', icon: Mic },
            { label: t('items.media_gallery'), href: '/galleries', icon: ImageIcon },
            { label: t('items.media_videos'), href: '/videos', icon: Video },
            { label: t('items.fanshop'), href: '/fanshop', icon: Ticket },
            { label: t('items.team_history'), href: '/about', icon: FileText },
            { label: t('items.contact'), href: '/contact', icon: UserCog },
        ],
        [t]
    );

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
                        <LanguageSwitcher />
                        <ThemeToggle />
                        <Button
                            asChild
                            variant="outline"
                            size="sm"
                            className="hidden sm:inline-flex"
                        >
                            <Link href="/login">
                                {tCommon('app.staff_area')}
                                <ChevronRight className="h-3.5 w-3.5" />
                            </Link>
                        </Button>
                        <button
                            type="button"
                            onClick={() => setMobileOpen((o) => !o)}
                            className="inline-flex h-10 w-10 items-center justify-center rounded-full border border-border bg-card/60 text-foreground lg:hidden"
                            aria-label={t('items.home')}
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
                            {tCommon('app.staff_area')}
                            <ChevronRight className="h-4 w-4" />
                        </Link>
                    </motion.div>
                )}
            </AnimatePresence>
        </header>
    );
}
