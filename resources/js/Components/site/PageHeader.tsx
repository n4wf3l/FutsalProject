import { motion } from 'framer-motion';
import { Link } from '@inertiajs/react';
import { ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';

interface PageHeaderProps {
    kicker?: string;
    title: string;
    subtitle?: string;
    breadcrumb?: Array<{ label: string; href?: string }>;
    align?: 'left' | 'center';
    className?: string;
    children?: React.ReactNode;
}

export function PageHeader({
    kicker,
    title,
    subtitle,
    breadcrumb,
    align = 'left',
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
            <div
                className={cn(
                    'flex flex-col gap-4',
                    align === 'center' && 'items-center'
                )}
            >
                {breadcrumb && breadcrumb.length > 0 && (
                    <nav className="flex items-center gap-1.5 text-xs text-muted-foreground">
                        {breadcrumb.map((crumb, i) => (
                            <span key={i} className="flex items-center gap-1.5">
                                {crumb.href ? (
                                    <Link
                                        href={crumb.href}
                                        className="hover:text-foreground transition-colors"
                                    >
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

                {kicker && (
                    <motion.div
                        initial={{ opacity: 0, y: 8 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.4 }}
                        className={cn(
                            'flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne',
                            align === 'center' && 'justify-center'
                        )}
                    >
                        <span className="h-px w-8 bg-champagne" />
                        {kicker}
                    </motion.div>
                )}

                <motion.h1
                    initial={{ opacity: 0, y: 16 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5, delay: 0.05 }}
                    className="font-display text-display-xl text-foreground"
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
