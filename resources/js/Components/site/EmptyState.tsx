import { LucideIcon } from 'lucide-react';
import { cn } from '@/lib/utils';

interface EmptyStateProps {
    icon?: LucideIcon;
    title: string;
    description?: string;
    action?: React.ReactNode;
    className?: string;
}

export function EmptyState({ icon: Icon, title, description, action, className }: EmptyStateProps) {
    return (
        <div
            className={cn(
                'flex flex-col items-center justify-center gap-4 rounded-2xl border border-dashed border-border bg-card/40 px-8 py-16 text-center',
                className
            )}
        >
            {Icon && (
                <div className="rounded-full border border-border bg-background p-4">
                    <Icon className="h-6 w-6 text-champagne" />
                </div>
            )}
            <div className="max-w-sm">
                <h3 className="font-display text-lg font-semibold text-foreground">{title}</h3>
                {description && (
                    <p className="mt-1.5 text-sm text-muted-foreground">{description}</p>
                )}
            </div>
            {action}
        </div>
    );
}
