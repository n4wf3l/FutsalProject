import { motion } from 'framer-motion';
import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';

interface PageHeaderProps {
    /** Small over-title text — treated as an editorial masthead */
    kicker?: string;
    /** Optional date/context shown to the right of kicker */
    kickerRight?: string;
    title: string;
    subtitle?: string;
    breadcrumb?: Array<{ label: string; href?: string }>;
    align?: 'left' | 'center';
    variant?: 'display' | 'editorial';
    className?: string;
    children?: React.ReactNode;
}

export function PageHeader({
    kicker,
    kickerRight,
    title,
    subtitle,
    breadcrumb,
    align = 'left',
    variant = 'display',
    className,
    children,
}: PageHeaderProps) {
    return (
        <section
            className={cn(
                'relative mx-auto max-w-7xl px-4 pb-8 pt-8',
                align === 'center' && 'text-center',
                className
            )}
        >
            <div className={cn('flex flex-col gap-5', align === 'center' && 'items-center')}>
                {breadcrumb && breadcrumb.length > 0 && (
                    <nav className="flex items-center gap-1.5 text-xs text-muted-foreground">
                        {breadcrumb.map((crumb, i) => (
                            <span key={i} className="flex items-center gap-1.5">
                                {crumb.href ? (
                                    <Link href={crumb.href} className="transition-colors hover:text-foreground">
                                        {crumb.label}
                                    </Link>
                                ) : (
                                    <span className="text-foreground">{crumb.label}</span>
                                )}
                                {i < breadcrumb.length - 1 && (
                                    <ChevronRight className="h-3 w-3 opacity-40" />
                                )}
                            </span>
                        ))}
                    </nav>
                )}

                {(kicker || kickerRight) && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        transition={{ duration: 0.4 }}
                        className={cn(
                            'flex items-baseline justify-between border-b border-border pb-3 font-mono text-[11px] uppercase tracking-[0.18em]',
                            align === 'center' && 'justify-center gap-6'
                        )}
                    >
                        {kicker && <span className="text-champagne">{kicker}</span>}
                        {kickerRight && (
                            <span className="text-muted-foreground">{kickerRight}</span>
                        )}
                    </motion.div>
                )}

                <motion.h1
                    initial={{ opacity: 0, y: 16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, delay: 0.05 }}
                    className={cn(
                        'text-foreground',
                        variant === 'editorial'
                            ? 'font-editorial text-4xl font-medium leading-[1.02] tracking-tight sm:text-5xl lg:text-[5.5rem]'
                            : 'font-display text-display-xl'
                    )}
                >
                    {title}
                </motion.h1>

                {subtitle && (
                    <motion.p
                        initial={{ opacity: 0, y: 16 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5, delay: 0.1 }}
                        className={cn(
                            'max-w-2xl text-lg text-muted-foreground',
                            align === 'center' && 'mx-auto'
                        )}
                    >
                        {subtitle}
                    </motion.p>
                )}

                {children && (
                    <motion.div
                        initial={{ opacity: 0, y: 16 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5, delay: 0.15 }}
                        className="mt-2"
                    >
                        {children}
                    </motion.div>
                )}
            </div>
        </section>
    );
}
