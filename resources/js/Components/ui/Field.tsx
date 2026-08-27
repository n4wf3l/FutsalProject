import * as React from 'react';
import { cn } from '@/lib/utils';

interface FieldProps {
    label?: string;
    hint?: string;
    error?: string;
    required?: boolean;
    className?: string;
    children: React.ReactNode;
}

export function Field({ label, hint, error, required, className, children }: FieldProps) {
    return (
        <div className={cn('space-y-1.5', className)}>
            {label && (
                <label className="block font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                    {label}
                    {required && <span className="ml-0.5 text-crimson">*</span>}
                </label>
            )}
            {children}
            {(hint || error) && (
                <p className={cn('text-xs', error ? 'text-plasma' : 'text-muted-foreground')}>
                    {error ?? hint}
                </p>
            )}
        </div>
    );
}
