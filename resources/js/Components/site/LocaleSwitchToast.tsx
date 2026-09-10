import { useEffect, useState } from 'react';
import { createPortal } from 'react-dom';
import { AnimatePresence, motion } from 'framer-motion';
import { Info, X } from 'lucide-react';
import { useTranslation } from 'react-i18next';

const STORAGE_KEY = 'locale_switch_toast';
const AUTO_DISMISS_MS = 7000;

/**
 * Renders a bottom-right toast one time after a locale switch, telling the
 * user that content authored in another language will keep its original
 * wording. Trigger is a sessionStorage flag set by switchLocale in i18n.
 */
export function LocaleSwitchToast() {
    const { t } = useTranslation('common');
    const [open, setOpen] = useState(false);

    useEffect(() => {
        if (typeof window === 'undefined') return;
        if (sessionStorage.getItem(STORAGE_KEY) === '1') {
            sessionStorage.removeItem(STORAGE_KEY);
            setOpen(true);
            const timer = window.setTimeout(() => setOpen(false), AUTO_DISMISS_MS);
            return () => window.clearTimeout(timer);
        }
    }, []);

    if (typeof document === 'undefined') return null;

    return createPortal(
        <AnimatePresence>
            {open && (
                <motion.div
                    role="status"
                    aria-live="polite"
                    initial={{ opacity: 0, y: 24, x: 0 }}
                    animate={{ opacity: 1, y: 0, x: 0 }}
                    exit={{ opacity: 0, y: 24 }}
                    transition={{ duration: 0.25, ease: [0.22, 1, 0.36, 1] }}
                    className="fixed bottom-6 right-6 z-[300] flex max-w-sm items-start gap-3 rounded-2xl border border-border bg-card p-4 shadow-2xl shadow-black/30"
                >
                    <div className="mt-0.5 rounded-full border border-champagne/40 bg-champagne/10 p-1.5 text-champagne">
                        <Info className="h-4 w-4" />
                    </div>
                    <div className="flex-1 pr-4">
                        <div className="font-display text-sm font-semibold text-foreground">
                            {t('locale_switch.title')}
                        </div>
                        <p className="mt-1 text-xs leading-relaxed text-muted-foreground">
                            {t('locale_switch.body')}
                        </p>
                    </div>
                    <button
                        type="button"
                        onClick={() => setOpen(false)}
                        aria-label={t('action.close')}
                        className="rounded-full p-1 text-muted-foreground transition-colors hover:bg-muted hover:text-foreground"
                    >
                        <X className="h-4 w-4" />
                    </button>
                </motion.div>
            )}
        </AnimatePresence>,
        document.body,
    );
}

export function markLocaleSwitchToast(): void {
    if (typeof window === 'undefined') return;
    sessionStorage.setItem(STORAGE_KEY, '1');
}
