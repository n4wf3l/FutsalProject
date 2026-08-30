import { PropsWithChildren, ReactNode } from 'react';
import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, ShieldCheck, Sparkles, Trophy } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { Logo } from '@/Components/site/Logo';
import { LanguageSwitcher } from '@/Components/site/LanguageSwitcher';
import { ThemeToggle } from '@/Components/site/ThemeToggle';

interface Props {
    title: string;
    subtitle?: string;
    footer?: ReactNode;
}

export default function AuthLayout({ title, subtitle, footer, children }: PropsWithChildren<Props>) {
    const { t } = useTranslation('auth');
    const HIGHLIGHTS = [
        { icon: Trophy, label: t('layout.highlights.founded') },
        { icon: Sparkles, label: t('layout.highlights.trophies') },
        { icon: ShieldCheck, label: t('layout.highlights.secure') },
    ];
    return (
        <div className="relative flex min-h-screen bg-background text-foreground">
            <div className="pointer-events-none fixed inset-0 -z-10">
                <div className="absolute inset-0 bg-grid-fade opacity-70" />
            </div>

            {/* Left: brand column */}
            <aside className="relative hidden overflow-hidden lg:flex lg:w-[45%] lg:flex-col lg:justify-between lg:border-r lg:border-border lg:bg-card lg:p-12">
                <div className="pointer-events-none absolute inset-0">
                    <div className="absolute -left-32 -top-32 h-[400px] w-[400px] rounded-full bg-crimson/25 blur-[120px]" />
                    <div className="absolute -bottom-32 -right-32 h-[420px] w-[420px] rounded-full bg-champagne/15 blur-[120px]" />
                    <div className="absolute inset-0 bg-noise opacity-[0.04]" />
                </div>

                <Link href="/" className="relative inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-foreground">
                    <ArrowLeft className="h-4 w-4" />
                    {t('layout.back_to_site')}
                </Link>

                <motion.div
                    initial={{ opacity: 0, scale: 0.9 }}
                    animate={{ opacity: 1, scale: 1 }}
                    transition={{ duration: 0.8, ease: [0.22, 1, 0.36, 1] }}
                    className="relative flex flex-1 items-center justify-center"
                >
                    <div className="relative">
                        <div className="absolute inset-0 rounded-full bg-crimson/30 blur-3xl" />
                        <img
                            src="/logo-dinakenitra.png"
                            alt="Dina Kenitra FC"
                            className="relative h-64 w-64 drop-shadow-[0_16px_48px_rgba(168,26,31,0.6)]"
                        />
                    </div>
                </motion.div>

                <div className="relative space-y-6">
                    <motion.div
                        initial={{ opacity: 0, y: 12 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: 0.2 }}
                    >
                        <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                            {t('layout.private_area')}
                        </div>
                        <h2 className="mt-3 font-editorial text-3xl font-medium leading-tight">
                            {t('layout.welcome')} <span className="italic text-champagne">Dina Kenitra FC</span>
                        </h2>
                        <p className="mt-3 max-w-md text-sm leading-relaxed text-muted-foreground">
                            {t('layout.welcome_body')}
                        </p>
                    </motion.div>

                    <motion.ul
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ delay: 0.4 }}
                        className="grid gap-2"
                    >
                        {HIGHLIGHTS.map((item) => (
                            <li
                                key={item.label}
                                className="flex items-center gap-3 rounded-lg border border-border/50 bg-card/30 px-4 py-3 text-sm backdrop-blur-sm"
                            >
                                <item.icon className="h-4 w-4 text-champagne" />
                                <span className="text-foreground">{item.label}</span>
                            </li>
                        ))}
                    </motion.ul>
                </div>
            </aside>

            {/* Right: form column */}
            <main className="relative flex flex-1 flex-col">
                <div className="flex items-center justify-between px-6 py-6 lg:px-12">
                    <Link href="/" className="lg:hidden">
                        <Logo showText={false} />
                    </Link>
                    <div className="ml-auto flex items-center gap-2">
                        <LanguageSwitcher />
                        <ThemeToggle />
                    </div>
                </div>

                <div className="flex flex-1 items-center justify-center px-6 pb-12 lg:px-12">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5 }}
                        className="w-full max-w-md"
                    >
                        <div className="mb-8">
                            <h1 className="font-display text-3xl font-bold tracking-tight text-foreground sm:text-4xl">
                                {title}
                            </h1>
                            {subtitle && (
                                <p className="mt-2 text-muted-foreground">{subtitle}</p>
                            )}
                        </div>

                        {children}

                        {footer && (
                            <div className="mt-8 border-t border-border pt-6 text-center text-sm text-muted-foreground">
                                {footer}
                            </div>
                        )}
                    </motion.div>
                </div>
            </main>
        </div>
    );
}
