import { PropsWithChildren, ReactNode, useEffect, useState } from 'react';
import { Link, usePage, router } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import {
    LayoutDashboard,
    Users,
    UserCog,
    Calendar,
    Newspaper,
    Image as ImageIcon,
    Video,
    Handshake,
    Ticket,
    FileText,
    Mic,
    Inbox,
    Settings2,
    LogOut,
    User,
    ChevronDown,
    Menu,
    X,
    ExternalLink,
    ShieldPlus,
    type LucideIcon,
} from 'lucide-react';
import { Logo } from '@/Components/site/Logo';
import { ThemeToggle } from '@/Components/site/ThemeToggle';
import { cn } from '@/lib/utils';

interface NavItem {
    label: string;
    href: string;
    icon: LucideIcon;
    match?: (url: string) => boolean;
    badgeKey?: 'applicationsPending' | 'interviewsDraft';
}

interface NavGroup {
    label: string;
    items: NavItem[];
}

const NAV: NavGroup[] = [
    {
        label: 'Général',
        items: [
            { label: 'Dashboard', href: '/dashboard', icon: LayoutDashboard, match: (u) => u === '/dashboard' },
        ],
    },
    {
        label: 'Réception',
        items: [
            {
                label: 'Candidatures',
                href: '/admin/applications',
                icon: Inbox,
                badgeKey: 'applicationsPending',
            },
        ],
    },
    {
        label: 'Effectif',
        items: [
            { label: 'Joueurs', href: '/players', icon: Users },
            { label: 'Joueurs U21', href: '/playersu21', icon: ShieldPlus },
            { label: 'Coachs', href: '/coaches', icon: UserCog },
            { label: 'Staff', href: '/staff', icon: UserCog },
        ],
    },
    {
        label: 'Compétition',
        items: [
            { label: 'Matchs', href: '/games', icon: Calendar },
            { label: 'Calendrier', href: '/calendar', icon: Calendar },
        ],
    },
    {
        label: 'Contenu',
        items: [
            { label: 'Articles', href: '/articles', icon: Newspaper },
            {
                label: 'Interviews',
                href: '/admin/interviews',
                icon: Mic,
                badgeKey: 'interviewsDraft',
            },
            { label: 'Galeries', href: '/galleries', icon: ImageIcon },
            { label: 'Vidéos', href: '/videos', icon: Video },
        ],
    },
    {
        label: 'Club',
        items: [
            { label: 'Sponsors', href: '/sponsors', icon: Handshake },
            { label: 'Tribunes', href: '/tribunes', icon: Ticket },
            { label: 'Règlements', href: '/about', icon: FileText },
        ],
    },
];

interface AdminInbox {
    applicationsPending: number;
    interviewsDraft: number;
    total: number;
}

interface Props {
    title?: string;
    header?: ReactNode;
}

export default function AdminLayout({ title, header, children }: PropsWithChildren<Props>) {
    const { props, url } = usePage<{
        auth: { user: { name: string; email: string } | null };
        adminInbox: AdminInbox | null;
    }>();
    const user = props.auth?.user;
    const inbox = props.adminInbox ?? { applicationsPending: 0, interviewsDraft: 0, total: 0 };
    const [mobileOpen, setMobileOpen] = useState(false);
    const [userMenuOpen, setUserMenuOpen] = useState(false);

    useEffect(() => {
        setMobileOpen(false);
        setUserMenuOpen(false);
    }, [url]);

    return (
        <div className="relative min-h-screen bg-background text-foreground">
            <div className="pointer-events-none fixed inset-0 -z-10">
                <div className="absolute inset-0 bg-grid-fade opacity-40" />
            </div>

            {/* Mobile top bar */}
            <div className="sticky top-0 z-40 flex items-center justify-between border-b border-border bg-background/90 px-4 py-3 backdrop-blur-lg lg:hidden">
                <button
                    onClick={() => setMobileOpen(true)}
                    className="rounded-lg border border-border bg-card p-2 text-foreground"
                    aria-label="Ouvrir la navigation"
                >
                    <Menu className="h-5 w-5" />
                </button>
                <Logo showText={false} />
                <ThemeToggle />
            </div>

            <div className="flex">
                {/* Sidebar */}
                <Sidebar
                    currentUrl={url}
                    inbox={inbox}
                    className="hidden lg:flex"
                    user={user}
                    userMenuOpen={userMenuOpen}
                    setUserMenuOpen={setUserMenuOpen}
                />

                <AnimatePresence>
                    {mobileOpen && (
                        <>
                            <motion.div
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                exit={{ opacity: 0 }}
                                onClick={() => setMobileOpen(false)}
                                className="fixed inset-0 z-40 bg-obsidian/80 backdrop-blur-sm lg:hidden"
                            />
                            <motion.div
                                initial={{ x: '-100%' }}
                                animate={{ x: 0 }}
                                exit={{ x: '-100%' }}
                                transition={{ type: 'spring', damping: 30, stiffness: 260 }}
                                className="fixed inset-y-0 left-0 z-50 lg:hidden"
                            >
                                <Sidebar
                                    currentUrl={url}
                                    inbox={inbox}
                                    onClose={() => setMobileOpen(false)}
                                    user={user}
                                    userMenuOpen={userMenuOpen}
                                    setUserMenuOpen={setUserMenuOpen}
                                />
                            </motion.div>
                        </>
                    )}
                </AnimatePresence>

                {/* Main content */}
                <div className="min-w-0 flex-1">
                    {(title || header) && (
                        <header className="border-b border-border bg-background/60 backdrop-blur-md">
                            <div className="mx-auto max-w-7xl px-6 py-6 lg:px-8">
                                {header ?? (
                                    <div>
                                        <div className="font-mono text-xs uppercase tracking-[0.25em] text-champagne">
                                            Admin
                                        </div>
                                        <h1 className="mt-1 font-display text-2xl font-bold text-foreground">
                                            {title}
                                        </h1>
                                    </div>
                                )}
                            </div>
                        </header>
                    )}
                    <main className="mx-auto max-w-7xl px-6 py-8 lg:px-8">{children}</main>
                </div>
            </div>
        </div>
    );
}

function Sidebar({
    currentUrl,
    inbox,
    className,
    onClose,
    user,
    userMenuOpen,
    setUserMenuOpen,
}: {
    currentUrl: string;
    inbox: AdminInbox;
    className?: string;
    onClose?: () => void;
    user: { name: string; email: string } | null;
    userMenuOpen: boolean;
    setUserMenuOpen: (b: boolean) => void;
}) {
    return (
        <aside
            className={cn(
                'sticky top-0 flex h-screen w-72 shrink-0 flex-col border-r border-border bg-card',
                className
            )}
        >
            <div className="flex items-center justify-between border-b border-border px-6 py-4">
                <Link href="/dashboard" className="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-crimson rounded-lg">
                    <Logo />
                </Link>
                {onClose && (
                    <button
                        onClick={onClose}
                        className="rounded-lg p-1.5 text-muted-foreground hover:text-foreground lg:hidden"
                        aria-label="Fermer"
                    >
                        <X className="h-5 w-5" />
                    </button>
                )}
            </div>

            <nav className="flex-1 space-y-6 overflow-y-auto px-3 py-6">
                {NAV.map((group) => (
                    <div key={group.label}>
                        <div className="mb-2 px-3 font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            {group.label}
                        </div>
                        <ul className="space-y-0.5">
                            {group.items.map((item) => {
                                const isActive = item.match
                                    ? item.match(currentUrl)
                                    : currentUrl.startsWith(item.href);
                                const badge = item.badgeKey ? inbox[item.badgeKey] : 0;
                                return (
                                    <li key={item.href}>
                                        <Link
                                            href={item.href}
                                            className={cn(
                                                'group relative flex items-center gap-3 rounded-lg px-3 py-2 text-sm font-medium transition-colors',
                                                isActive
                                                    ? 'bg-crimson/10 text-crimson'
                                                    : 'text-muted-foreground hover:bg-muted hover:text-foreground'
                                            )}
                                        >
                                            {isActive && (
                                                <motion.span
                                                    layoutId="admin-nav-indicator"
                                                    className="absolute inset-y-1 left-0 w-0.5 rounded-r-full bg-crimson"
                                                    transition={{ type: 'spring', stiffness: 400, damping: 32 }}
                                                />
                                            )}
                                            <item.icon className="h-4 w-4" />
                                            <span className="flex-1">{item.label}</span>
                                            {badge > 0 && (
                                                <span
                                                    className={cn(
                                                        'relative inline-flex h-5 min-w-5 items-center justify-center rounded-full px-1.5 font-mono text-[10px] font-bold tabular-nums',
                                                        item.badgeKey === 'applicationsPending'
                                                            ? 'bg-plasma text-crimson-foreground'
                                                            : 'bg-amber text-obsidian',
                                                    )}
                                                >
                                                    {item.badgeKey === 'applicationsPending' && (
                                                        <span className="absolute inset-0 -z-10 rounded-full bg-plasma opacity-60 animate-ping" />
                                                    )}
                                                    {badge > 99 ? '99+' : badge}
                                                </span>
                                            )}
                                        </Link>
                                    </li>
                                );
                            })}
                        </ul>
                    </div>
                ))}
            </nav>

            {/* Footer / user menu */}
            <div className="border-t border-border p-3">
                <a
                    href="/"
                    target="_blank"
                    rel="noopener"
                    className="mb-2 flex items-center gap-2 rounded-lg px-3 py-2 text-xs text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                >
                    <ExternalLink className="h-3.5 w-3.5" />
                    Voir le site public
                </a>

                {user && (
                    <div className="relative">
                        <button
                            onClick={() => setUserMenuOpen(!userMenuOpen)}
                            className="flex w-full items-center gap-3 rounded-lg border border-border bg-background px-3 py-2.5 text-left transition-colors hover:border-crimson/40"
                        >
                            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-crimson text-sm font-semibold text-crimson-foreground">
                                {user.name.charAt(0).toUpperCase()}
                            </div>
                            <div className="min-w-0 flex-1">
                                <div className="truncate text-sm font-semibold">{user.name}</div>
                                <div className="truncate text-xs text-muted-foreground">
                                    {user.email}
                                </div>
                            </div>
                            <ChevronDown
                                className={cn(
                                    'h-4 w-4 text-muted-foreground transition-transform',
                                    userMenuOpen && 'rotate-180'
                                )}
                            />
                        </button>

                        <AnimatePresence>
                            {userMenuOpen && (
                                <motion.div
                                    initial={{ opacity: 0, y: 4 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    exit={{ opacity: 0, y: 4 }}
                                    className="absolute bottom-full left-0 right-0 mb-2 overflow-hidden rounded-lg border border-border bg-card shadow-lg"
                                >
                                    <Link
                                        href={route('profile.edit')}
                                        className="flex items-center gap-2 px-3 py-2 text-sm text-foreground transition-colors hover:bg-muted"
                                    >
                                        <User className="h-4 w-4" />
                                        Mon profil
                                    </Link>
                                    <Link
                                        href="/settings"
                                        className="flex items-center gap-2 px-3 py-2 text-sm text-foreground transition-colors hover:bg-muted"
                                    >
                                        <Settings2 className="h-4 w-4" />
                                        Paramètres
                                    </Link>
                                    <button
                                        onClick={() => router.post(route('logout'))}
                                        className="flex w-full items-center gap-2 border-t border-border px-3 py-2 text-sm text-plasma transition-colors hover:bg-muted"
                                    >
                                        <LogOut className="h-4 w-4" />
                                        Se déconnecter
                                    </button>
                                </motion.div>
                            )}
                        </AnimatePresence>
                    </div>
                )}

                <div className="mt-3 flex items-center justify-between px-3">
                    <span className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        Thème
                    </span>
                    <ThemeToggle />
                </div>
            </div>
        </aside>
    );
}
