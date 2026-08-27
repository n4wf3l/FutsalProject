import { AnimatePresence, motion } from 'framer-motion';
import { AlertTriangle, X } from 'lucide-react';
import { Button } from '@/Components/ui/Button';

interface Props {
    open: boolean;
    title: string;
    description?: string;
    confirmLabel?: string;
    cancelLabel?: string;
    variant?: 'default' | 'destructive';
    onCancel: () => void;
    onConfirm: () => void;
}

export function ConfirmDialog({
    open,
    title,
    description,
    confirmLabel = 'Confirmer',
    cancelLabel = 'Annuler',
    variant = 'default',
    onCancel,
    onConfirm,
}: Props) {
    return (
        <AnimatePresence>
            {open && (
                <motion.div
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    exit={{ opacity: 0 }}
                    onClick={onCancel}
                    className="fixed inset-0 z-50 flex items-center justify-center bg-obsidian/80 p-4 backdrop-blur-sm"
                >
                    <motion.div
                        onClick={(e) => e.stopPropagation()}
                        initial={{ scale: 0.95, opacity: 0 }}
                        animate={{ scale: 1, opacity: 1 }}
                        exit={{ scale: 0.95, opacity: 0 }}
                        transition={{ duration: 0.2 }}
                        className="relative w-full max-w-md overflow-hidden rounded-2xl border border-border bg-card p-6 shadow-2xl"
                    >
                        <button
                            type="button"
                            onClick={onCancel}
                            className="absolute right-4 top-4 rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-foreground"
                            aria-label="Fermer"
                        >
                            <X className="h-4 w-4" />
                        </button>

                        <div className="flex items-start gap-3">
                            {variant === 'destructive' && (
                                <div className="rounded-full border border-plasma/30 bg-plasma/10 p-2.5">
                                    <AlertTriangle className="h-4 w-4 text-plasma" />
                                </div>
                            )}
                            <div>
                                <h3 className="font-display text-lg font-bold">{title}</h3>
                                {description && (
                                    <p className="mt-1 text-sm text-muted-foreground">{description}</p>
                                )}
                            </div>
                        </div>

                        <div className="mt-6 flex justify-end gap-2">
                            <Button type="button" variant="outline" onClick={onCancel}>
                                {cancelLabel}
                            </Button>
                            <Button
                                type="button"
                                variant={variant === 'destructive' ? 'destructive' : 'default'}
                                onClick={onConfirm}
                            >
                                {confirmLabel}
                            </Button>
                        </div>
                    </motion.div>
                </motion.div>
            )}
        </AnimatePresence>
    );
}
