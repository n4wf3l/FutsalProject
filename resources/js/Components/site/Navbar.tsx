import { useEffect, useMemo, useRef, useState } from 'react';
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
    ShieldPlus,
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
    const [hidden, setHidden] = useState(false);
    const [mobileOpen, setMobileOpen] = useState(false);
    const lastScrollY = useRef(0);

    useEffect(() => {
        const onScroll = () => {
            const y = window.scrollY;
            setScrolled(y > 12);
            const delta = y - lastScrollY.current;
            if (y > 120 && delta > 4) {
                setHidden(true);
            } else if (delta < -4 || y < 120) {
                setHidden(false);
            }
            lastScrollY.current = y;
        };
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });
        return () => window.removeEventListener('scroll', onScroll);
    }, []);

    useEffect(() => {
        setMobileOpen(false);
    }, [url]);

    // Lock body scroll while the mobile menu is open so touch scrolling stays
    // trapped inside the sheet, and close on Escape for keyboard users.
    useEffect(() => {
        if (!mobileOpen) return;
        const previousOverflow = document.body.style.overflow;
        document.body.style.overflow = 'hidden';
        const onKey = (e: KeyboardEvent) => {
            if (e.key === 'Escape') setMobileOpen(false);
        };
        document.addEventListener('keydown', onKey);
        return () => {
            document.body.style.overflow = previousOverflow;
            document.removeEventListener('keydown', onKey);
        };
    }, [mobileOpen]);

    const NAV: NavGroup[] = useMemo(
        () => [
            { kind: 'link', label: t('items.home'), href: '/', hrefMatches: ['/'] },
            {
                kind: 'menu',
                label: t('items.team_group'),
                hrefMatches: ['/teams', '/espoirs', '/coaches', '/staff', '/about'],
                items: [
                    { label: t('items.team_roster'), href: '/teams', icon: Users, description: t('items.team_roster_desc') },
                    { label: t('items.team_espoirs'), href: '/espoirs', icon: ShieldPlus, description: t('items.team_espoirs_desc') },
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
                hrefMatches: ['/news', '/communiques', '/interviews', '/galleries', '/videos', '/articles'],
                items: [
                    { label: t('items.media_news'), href: '/news', icon: Newspaper, description: t('items.media_news_desc') },
                    { label: t('items.media_press'), href: '/communiques', icon: FileText, description: t('items.media_press_desc') },
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
            { label: t('items.team_espoirs'), href: '/espoirs', icon: ShieldPlus },
            { label: t('items.competition_calendar'), href: '/calendar', icon: Calendar },
            { label: t('items.media_news'), href: '/news', icon: Newspaper },
            { label: t('items.media_press'), href: '/communiques', icon: FileText },
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
                scrolled ? 'py-2' : 'py-4',
                hidden && !mobileOpen ? '-translate-y-full' : 'translate-y-0'
            )}
        >
            <div className="mx-auto max-w-7xl px-4">
                <div
                    className={cn(
                        'flex h-16 items-center justify-between rounded-2xl px-4 transition-all duration-300',
                        scrolled || mobileOpen ? 'glass-strong shadow-lg shadow-black/20' : 'bg-transparent'
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
                            className="inline-flex h-11 w-11 items-center justify-center rounded-full border border-border bg-card/60 text-foreground lg:hidden"
                            aria-label={mobileOpen ? tCommon('action.close') : tCommon('app.menu_open')}
                            aria-expanded={mobileOpen}
                            aria-controls="mobile-menu-sheet"
                        >
                            {mobileOpen ? <X className="h-5 w-5" /> : <Menu className="h-5 w-5" />}
                        </button>
                    </div>
                </div>
            </div>

            <AnimatePresence>
                {mobileOpen && (
                    <motion.div
                        id="mobile-menu-sheet"
                        role="dialog"
                        aria-modal="true"
                        aria-label={tCommon('app.menu_open')}
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        transition={{ duration: 0.2 }}
                        className="fixed inset-0 z-40 flex flex-col bg-background lg:hidden"
                    >
                        <motion.nav
                            initial={{ x: '100%' }}
                            animate={{ x: 0 }}
                            exit={{ x: '100%' }}
                            transition={{ type: 'spring', damping: 30, stiffness: 300 }}
                            className="flex h-full w-full flex-col overflow-y-auto pt-24 pb-10"
                        >
                            <div className="flex flex-1 flex-col gap-1 px-4">
                                {MOBILE_NAV.map((item) => {
                                    const active =
                                        item.href === '/' ? url === '/' : url.startsWith(item.href);
                                    return (
                                        <Link
                                            key={item.href}
                                            href={item.href}
                                            className={cn(
                                                'flex min-h-[56px] items-center gap-4 rounded-xl px-4 py-4 text-base font-medium transition-colors active:scale-[0.98]',
                                                active
                                                    ? 'bg-crimson/10 text-crimson'
                                                    : 'text-foreground active:bg-muted'
                                            )}
                                        >
                                            <span
                                                className={cn(
                                                    'inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border',
                                                    active
                                                        ? 'border-crimson/30 bg-crimson/10 text-crimson'
                                                        : 'border-border bg-card text-champagne'
                                                )}
                                            >
                                                <item.icon className="h-4 w-4" />
                                            </span>
                                            <span className="flex-1">{item.label}</span>
                                            <ChevronRight className="h-5 w-5 opacity-40" />
                                        </Link>
                                    );
                                })}
                            </div>

                            <div className="mt-6 border-t border-border px-4 pt-6">
                                <Link
                                    href="/login"
                                    className="flex min-h-[56px] items-center justify-between rounded-xl bg-crimson px-5 py-4 text-base font-semibold text-crimson-foreground shadow-sm active:scale-[0.98]"
                                >
                                    {tCommon('app.staff_area')}
                                    <ChevronRight className="h-5 w-5" />
                                </Link>
                            </div>
                        </motion.nav>
                    </motion.div>
                )}
            </AnimatePresence>
        </header>
    );
}
