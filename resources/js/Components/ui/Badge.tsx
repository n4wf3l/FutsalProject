import * as React from 'react';
import { cva, type VariantProps } from 'class-variance-authority';
import { cn } from '@/lib/utils';

const badgeVariants = cva(
    'inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-xs font-semibold uppercase tracking-wider transition-colors',
    {
        variants: {
            variant: {
                default: 'border-transparent bg-crimson/15 text-crimson',
                champagne: 'border-transparent bg-champagne/15 text-champagne',
                live: 'border-plasma/30 bg-plasma/10 text-plasma',
                win: 'border-mint/30 bg-mint/10 text-mint',
                soon: 'border-amber/30 bg-amber/10 text-amber',
                outline: 'border-border text-foreground',
                muted: 'border-transparent bg-muted text-muted-foreground',
            },
        },
        defaultVariants: { variant: 'default' },
    }
);

export interface BadgeProps
    extends React.HTMLAttributes<HTMLDivElement>,
        VariantProps<typeof badgeVariants> {}

export function Badge({ className, variant, ...props }: BadgeProps) {
    return <div className={cn(badgeVariants({ variant }), className)} {...props} />;
}

export { badgeVariants };
