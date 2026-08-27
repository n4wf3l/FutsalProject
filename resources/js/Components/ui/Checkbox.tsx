import * as React from 'react';
import { Check } from 'lucide-react';
import { cn } from '@/lib/utils';

export interface CheckboxProps extends Omit<React.InputHTMLAttributes<HTMLInputElement>, 'type'> {
    label?: React.ReactNode;
}

export const Checkbox = React.forwardRef<HTMLInputElement, CheckboxProps>(
    ({ className, label, id, ...props }, ref) => {
        const autoId = React.useId();
        const inputId = id ?? autoId;
        return (
            <label htmlFor={inputId} className="group inline-flex cursor-pointer select-none items-center gap-2.5">
                <input
                    ref={ref}
                    id={inputId}
                    type="checkbox"
                    className="peer sr-only"
                    {...props}
                />
                <span
                    className={cn(
                        'inline-flex h-4 w-4 items-center justify-center rounded border border-input bg-card transition-all peer-checked:border-crimson peer-checked:bg-crimson peer-focus-visible:ring-2 peer-focus-visible:ring-crimson/40',
                        className
                    )}
                >
                    <Check className="h-3 w-3 text-crimson-foreground opacity-0 transition-opacity group-has-[input:checked]:opacity-100" />
                </span>
                {label && <span className="text-sm text-muted-foreground">{label}</span>}
            </label>
        );
    }
);
Checkbox.displayName = 'Checkbox';
