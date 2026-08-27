import * as React from 'react';
import { cn } from '@/lib/utils';

export interface InputProps extends React.InputHTMLAttributes<HTMLInputElement> {}

export const Input = React.forwardRef<HTMLInputElement, InputProps>(
    ({ className, type, ...props }, ref) => {
        return (
            <input
                type={type}
                ref={ref}
                className={cn(
                    'flex h-10 w-full rounded-lg border border-input bg-card px-4 py-2 text-sm text-foreground shadow-sm transition-colors placeholder:text-muted-foreground focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20 disabled:cursor-not-allowed disabled:opacity-50',
                    className
                )}
                {...props}
            />
        );
    }
);
Input.displayName = 'Input';

export const Textarea = React.forwardRef<HTMLTextAreaElement, React.TextareaHTMLAttributes<HTMLTextAreaElement>>(
    ({ className, ...props }, ref) => {
        return (
            <textarea
                ref={ref}
                className={cn(
                    'flex min-h-[100px] w-full rounded-lg border border-input bg-card px-4 py-3 text-sm text-foreground shadow-sm transition-colors placeholder:text-muted-foreground focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20 disabled:cursor-not-allowed disabled:opacity-50',
                    className
                )}
                {...props}
            />
        );
    }
);
Textarea.displayName = 'Textarea';
