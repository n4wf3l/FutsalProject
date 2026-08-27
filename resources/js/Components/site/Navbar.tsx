import { useEffect, useState } from 'react';
import { Link, usePage } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { Menu, X, ChevronRight } from 'lucide-react';
import { Logo } from './Logo';
import { ThemeToggle } from './ThemeToggle';
import { Button } from '@/Components/ui/Button';
import { cn } from '@/lib/utils';

type NavItem = { label: string; href: string; name: string };

const NAV: NavItem[] = [
    { label: 'Accueil', href: '/', name: 'home' },
    { label: 'Équipes', href: '/teams', name: 'teams' },
    { label: 'Calendrier', href: '/calendar', name: 'calendar' },
    { label: 'Actualités', href: '/news', name: 'news' },
    { label: 'Galerie', href: '/galleries', name: 'gallery' },
    { label: 'Fanshop', href: '/fanshop', name: 'fanshop' },
    { label: 'Contact', href: '/contact', name: 'contact' },
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

    const isActive = (href: string) => (href === '/' ? url === '/' : url.startsWith(href));

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
                    <Link href="/" className="focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-crimson rounded-lg">
                        <Logo />
                    </Link>

                    <nav className="hidden items-center gap-1 lg:flex">
                        {NAV.map((item) => (
                            <Link
                                key={item.name}
                                href={item.href}
                                className={cn(
                                    'relative rounded-lg px-4 py-2 text-sm font-medium transition-colors',
                                    isActive(item.href)
                                        ? 'text-foreground'
                                        : 'text-muted-foreground hover:text-foreground'
                                )}
                            >
                                {item.label}
                                {isActive(item.href) && (
                                    <motion.span
                                        layoutId="nav-underline"
                                        className="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-gradient-to-r from-crimson via-champagne to-crimson"
                                        transition={{ type: 'spring', stiffness: 400, damping: 32 }}
                                    />
                                )}
                            </Link>
                        ))}
                    </nav>

                    <div className="flex items-center gap-2">
                        <ThemeToggle />
                        <Button
                            asChild
                            variant="default"
                            size="sm"
                            className="hidden sm:inline-flex"
                        >
                            <Link href="/login">
                                Espace membre
                                <ChevronRight className="h-3.5 w-3.5" />
                            </Link>
                        </Button>
                        <button
                            type="button"
                            onClick={() => setMobileOpen((o) => !o)}
                            className="lg:hidden inline-flex h-10 w-10 items-center justify-center rounded-full border border-border bg-card/60 text-foreground"
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
                        className="lg:hidden absolute inset-x-4 top-full mt-2 glass-strong rounded-2xl p-2 shadow-xl shadow-black/30"
                    >
                        {NAV.map((item) => (
                            <Link
                                key={item.name}
                                href={item.href}
                                className={cn(
                                    'flex items-center justify-between rounded-lg px-4 py-3 text-sm font-medium transition-colors',
                                    isActive(item.href)
                                        ? 'bg-crimson/15 text-crimson'
                                        : 'text-foreground hover:bg-muted'
                                )}
                            >
                                {item.label}
                                <ChevronRight className="h-4 w-4 opacity-40" />
                            </Link>
                        ))}
                        <Link
                            href="/login"
                            className="mt-1 flex items-center justify-between rounded-lg bg-crimson px-4 py-3 text-sm font-semibold text-crimson-foreground"
                        >
                            Espace membre
                            <ChevronRight className="h-4 w-4" />
                        </Link>
                    </motion.div>
                )}
            </AnimatePresence>
        </header>
    );
}
