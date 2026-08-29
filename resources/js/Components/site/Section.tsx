import { Link } from '@inertiajs/react';
import { ArrowUpRight } from 'lucide-react';
import { cn } from '@/lib/utils';

interface SectionHeaderProps {
    kicker?: string;
    title: string;
    description?: string;
    action?: { label: string; href: string };
    className?: string;
    variant?: 'display' | 'editorial';
}

export function SectionHeader({
    kicker,
    title,
    description,
    action,
    className,
    variant = 'display',
}: SectionHeaderProps) {
    return (
        <header className={cn('flex flex-wrap items-end justify-between gap-6', className)}>
            <div className="max-w-2xl">
                {kicker && (
                    <div className="mb-3 font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                        {kicker}
                    </div>
                )}
                <h2
                    className={cn(
                        'text-foreground',
                        variant === 'editorial'
                            ? 'font-editorial text-4xl font-medium leading-[1.05] tracking-tight sm:text-5xl'
                            : 'font-display text-display-lg'
                    )}
                >
                    {title}
                </h2>
                {description && (
                    <p className="mt-3 text-muted-foreground">{description}</p>
                )}
            </div>
            {action && (
                <Link
                    href={action.href}
                    className="group inline-flex items-center gap-1.5 border-b border-champagne/40 pb-1 text-sm font-medium text-champagne transition-colors hover:border-champagne"
                >
                    {action.label}
                    <ArrowUpRight className="h-4 w-4 transition-transform group-hover:-translate-y-0.5 group-hover:translate-x-0.5" />
                </Link>
            )}
        </header>
    );
}
