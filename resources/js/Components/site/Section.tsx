import { Link } from '@inertiajs/react';
import { ArrowRight } from 'lucide-react';
import { cn } from '@/lib/utils';

interface SectionHeaderProps {
    kicker?: string;
    title: string;
    description?: string;
    action?: { label: string; href: string };
    className?: string;
}

export function SectionHeader({
    kicker,
    title,
    description,
    action,
    className,
}: SectionHeaderProps) {
    return (
        <div className={cn('flex flex-wrap items-end justify-between gap-4', className)}>
            <div>
                {kicker && (
                    <div className="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        <span className="h-px w-8 bg-champagne" />
                        {kicker}
                    </div>
                )}
                <h2 className="mt-3 font-display text-display-lg text-foreground">{title}</h2>
                {description && (
                    <p className="mt-2 max-w-2xl text-muted-foreground">{description}</p>
                )}
            </div>
            {action && (
                <Link
                    href={action.href}
                    className="hidden shrink-0 items-center gap-1.5 rounded-full border border-border px-4 py-2 text-sm font-medium text-muted-foreground transition-all hover:border-crimson/50 hover:text-foreground sm:inline-flex"
                >
                    {action.label}
                    <ArrowRight className="h-3.5 w-3.5" />
                </Link>
            )}
        </div>
    );
}
