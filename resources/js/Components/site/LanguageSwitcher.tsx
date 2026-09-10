import { useEffect, useState } from 'react';
import { createPortal } from 'react-dom';
import { router } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { Check, Globe, X } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { LOCALE_META, SUPPORTED_LOCALES, switchLocale, type Locale } from '@/i18n';
import { markLocaleSwitchToast } from './LocaleSwitchToast';
import { cn } from '@/lib/utils';

interface Props {
    className?: string;
    /** Compact variant (icon only, opens dropdown). Default = compact */
    variant?: 'compact' | 'inline';
}

export function LanguageSwitcher({ className, variant = 'compact' }: Props) {
    const { i18n, t } = useTranslation('common');
    const [open, setOpen] = useState(false);

    const current = (i18n.language.split('-')[0] as Locale) || 'fr';

    useEffect(() => {
        if (!open) return;
        const onKey = (e: KeyboardEvent) => {
            if (e.key === 'Escape') setOpen(false);
        };
        document.addEventListener('keydown', onKey);
        document.body.style.overflow = 'hidden';
        return () => {
            document.removeEventListener('keydown', onKey);
            document.body.style.overflow = '';
        };
    }, [open]);

    const change = async (locale: Locale) => {
        if (locale === current) {
            setOpen(false);
            return;
        }
        await switchLocale(locale);
        markLocaleSwitchToast();
        setOpen(false);
        router.reload();
    };

    return (
        <>
            <button
                type="button"
                onClick={() => setOpen(true)}
                aria-label={t('app.language')}
                aria-haspopup="dialog"
                aria-expanded={open}
                className={cn(
                    'inline-flex h-10 items-center gap-1.5 rounded-full border border-border bg-card/60 text-foreground transition-colors hover:border-champagne/50 hover:text-champagne',
                    variant === 'compact' ? 'w-16 justify-center px-2' : 'px-3',
                    className
                )}
            >
                <Globe className="h-4 w-4" />
                <span className="font-mono text-[11px] font-semibold tracking-widest">
                    {LOCALE_META[current]?.native ?? current.toUpperCase()}
                </span>
            </button>

            {/* Render the overlay into document.body via a portal so it is
                NOT clipped/anchored by the navbar's transform. Any ancestor
                with transform (like the scroll-hide translate-y on <header>)
                would otherwise trap position:fixed inside its box. */}
            {typeof document !== 'undefined' &&
                createPortal(
                    <AnimatePresence>
                        {open && (
                            <LanguageOverlay
                                current={current}
                                onSelect={change}
                                onClose={() => setOpen(false)}
                            />
                        )}
                    </AnimatePresence>,
                    document.body
                )}
        </>
    );
}

function LanguageOverlay({
    current,
    onSelect,
    onClose,
}: {
    current: Locale;
    onSelect: (l: Locale) => void;
    onClose: () => void;
}) {
    const { t } = useTranslation('common');

    return (
        <motion.div
            role="dialog"
            aria-modal="true"
            aria-label={t('app.language')}
            initial={{ opacity: 0 }}
            animate={{ opacity: 1 }}
            exit={{ opacity: 0 }}
            transition={{ duration: 0.2 }}
            onClick={onClose}
            className="fixed inset-0 z-[200] flex items-center justify-center bg-black/[0.92] p-6 text-white backdrop-blur-2xl"
        >
            {/* Close button, tapping the backdrop also closes */}
            <button
                type="button"
                onClick={onClose}
                aria-label={t('action.close')}
                className="absolute right-4 top-4 inline-flex h-12 w-12 items-center justify-center rounded-full border border-white/15 bg-white/[0.04] text-white/70 backdrop-blur transition-all hover:border-white/40 hover:text-white sm:right-6 sm:top-6"
            >
                <X className="h-5 w-5" />
            </button>

            <motion.div
                initial={{ opacity: 0, y: 12 }}
                animate={{ opacity: 1, y: 0 }}
                exit={{ opacity: 0, y: 12 }}
                transition={{ duration: 0.3, ease: [0.22, 1, 0.36, 1] }}
                onClick={(e) => e.stopPropagation()}
                className="w-full max-w-2xl"
            >
                <div className="mb-8 flex items-center justify-center gap-3 sm:mb-10">
                    <Globe className="h-4 w-4 text-champagne" />
                    <span className="font-mono text-xs font-semibold uppercase tracking-[0.4em] text-champagne">
                        {t('app.language')}
                    </span>
                </div>

                <ul className="flex flex-col divide-y divide-white/10">
                    {SUPPORTED_LOCALES.map((loc, i) => {
                        const meta = LOCALE_META[loc];
                        const isActive = loc === current;
                        const isArabic = loc === 'ar';
                        return (
                            <li key={loc}>
                                <motion.button
                                    initial={{ opacity: 0, y: 8 }}
                                    animate={{ opacity: 1, y: 0 }}
                                    transition={{ duration: 0.3, delay: 0.1 + i * 0.05 }}
                                    type="button"
                                    onClick={() => onSelect(loc)}
                                    className={cn(
                                        'group flex w-full items-center gap-5 py-6 text-left transition-all active:scale-[0.98] sm:gap-6 sm:py-7',
                                        isActive ? 'text-champagne' : 'text-white/85 hover:text-white'
                                    )}
                                >
                                    <span
                                        className={cn(
                                            'inline-flex h-12 w-14 shrink-0 items-center justify-center rounded-lg border font-mono text-xs font-bold tracking-widest transition-colors sm:h-14 sm:w-16 sm:text-sm',
                                            isActive
                                                ? 'border-champagne/50 bg-champagne/10 text-champagne'
                                                : 'border-white/15 bg-white/[0.02] text-white/60 group-hover:border-white/40 group-hover:text-white'
                                        )}
                                    >
                                        {meta.native}
                                    </span>
                                    <span className="flex flex-1 items-baseline justify-between gap-4">
                                        <span
                                            className={cn(
                                                'font-editorial italic leading-none',
                                                isArabic
                                                    ? 'text-4xl sm:text-5xl'
                                                    : 'text-4xl sm:text-5xl'
                                            )}
                                        >
                                            {meta.label}
                                        </span>
                                        <span
                                            className={cn(
                                                'hidden font-editorial italic text-white/50 sm:inline sm:text-2xl',
                                                isArabic && 'sm:text-3xl'
                                            )}
                                            dir={isArabic ? 'rtl' : 'ltr'}
                                        >
                                            {meta.greeting}
                                        </span>
                                    </span>
                                    {isActive && <Check className="h-6 w-6 shrink-0 text-champagne" />}
                                </motion.button>
                            </li>
                        );
                    })}
                </ul>

                <div className="mt-10 hidden items-center justify-center gap-1.5 font-mono text-[10px] uppercase tracking-[0.3em] text-white/40 sm:flex">
                    <kbd className="rounded border border-white/15 bg-white/[0.04] px-1.5 py-0.5 text-[10px] text-white/70">
                        Esc
                    </kbd>
                    {t('app.esc_to_close')}
                </div>
            </motion.div>
        </motion.div>
    );
}
