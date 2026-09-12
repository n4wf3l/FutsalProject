import { useEffect, useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import { usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { AlertTriangle, CheckCircle2, X } from 'lucide-react';
import { cn } from '@/lib/utils';

type ToastKind = 'success' | 'error';
interface Toast {
    id: number;
    kind: ToastKind;
    message: string;
}

const AUTO_DISMISS_MS = 4500;

/**
 * Global toast surface. Listens to Inertia flash props (success / error)
 * and renders stacking toasts in the bottom-right corner. Portal ensures
 * it sits above every stacking context including the navbar transform.
 */
export function Toaster() {
    const { props } = usePage<{ flash?: { success?: string | null; error?: string | null } }>();
    const flash = props.flash;
    const [toasts, setToasts] = useState<Toast[]>([]);
    // Track the last flash content we surfaced so identical repeated flashes
    // don't create duplicate toasts when Inertia re-shares the same value.
    const seen = useRef<{ success?: string | null; error?: string | null }>({});

    useEffect(() => {
        const success = flash?.success ?? null;
        if (success && success !== seen.current.success) {
            seen.current.success = success;
            push('success', success);
        }
        const error = flash?.error ?? null;
        if (error && error !== seen.current.error) {
            seen.current.error = error;
            push('error', error);
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [flash?.success, flash?.error]);

    function push(kind: ToastKind, message: string) {
        const id = Date.now() + Math.random();
        setToasts((prev) => [...prev, { id, kind, message }]);
        window.setTimeout(() => {
            setToasts((prev) => prev.filter((t) => t.id !== id));
        }, AUTO_DISMISS_MS);
    }

    function dismiss(id: number) {
        setToasts((prev) => prev.filter((t) => t.id !== id));
    }

    if (typeof document === 'undefined') return null;

    return createPortal(
        <div
            role="region"
            aria-label="Notifications"
            className="pointer-events-none fixed inset-x-4 bottom-4 z-[400] flex flex-col items-end gap-2 sm:inset-x-auto sm:right-6 sm:bottom-6"
        >
            <AnimatePresence>
                {toasts.map((toast) => (
                    <motion.div
                        key={toast.id}
                        role="status"
                        aria-live="polite"
                        initial={{ opacity: 0, y: 20, scale: 0.96 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, x: 24, scale: 0.96 }}
                        transition={{ duration: 0.22, ease: [0.22, 1, 0.36, 1] }}
                        className={cn(
                            'pointer-events-auto flex w-full max-w-sm items-start gap-3 rounded-2xl border bg-card p-4 shadow-2xl shadow-black/30 sm:w-auto sm:min-w-[320px]',
                            toast.kind === 'success'
                                ? 'border-mint/40'
                                : 'border-plasma/40'
                        )}
                    >
                        <div
                            className={cn(
                                'mt-0.5 shrink-0 rounded-full border p-1.5',
                                toast.kind === 'success'
                                    ? 'border-mint/40 bg-mint/10 text-mint'
                                    : 'border-plasma/40 bg-plasma/10 text-plasma'
                            )}
                        >
                            {toast.kind === 'success' ? (
                                <CheckCircle2 className="h-4 w-4" />
                            ) : (
                                <AlertTriangle className="h-4 w-4" />
                            )}
                        </div>
                        <p className="flex-1 pr-2 text-sm leading-relaxed text-foreground">
                            {toast.message}
                        </p>
                        <button
                            type="button"
                            onClick={() => dismiss(toast.id)}
                            aria-label="Fermer"
                            className="shrink-0 rounded-full p-1 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                        >
                            <X className="h-4 w-4" />
                        </button>
                    </motion.div>
                ))}
            </AnimatePresence>
        </div>,
        document.body,
    );
}
