import { useEffect, useState } from 'react';
import { router } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { Check, Globe, X } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import { LOCALE_META, SUPPORTED_LOCALES, switchLocale, type Locale } from '@/i18n';
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

            <AnimatePresence>
                {open && (
                    <LanguageOverlay current={current} onSelect={change} onClose={() => setOpen(false)} />
                )}
            </AnimatePresence>
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
            className="fixed inset-0 z-[200] flex items-center justify-center bg-obsidian/85 p-4 backdrop-blur-md"
        >
            <button
                type="button"
                onClick={onClose}
                aria-label={t('action.close')}
                className="group absolute right-6 top-6 inline-flex h-10 w-10 items-center justify-center rounded-full border border-border/60 bg-card/40 text-muted-foreground transition-all hover:border-crimson/60 hover:text-crimson"
            >
                <X className="h-4 w-4 transition-transform group-hover:rotate-90" />
            </button>

            <motion.div
                initial={{ opacity: 0, y: 12, scale: 0.98 }}
                animate={{ opacity: 1, y: 0, scale: 1 }}
                exit={{ opacity: 0, y: 12, scale: 0.98 }}
                transition={{ duration: 0.25, ease: [0.22, 1, 0.36, 1] }}
                onClick={(e) => e.stopPropagation()}
                className="w-full max-w-sm rounded-2xl border border-border/60 bg-card p-2 shadow-2xl shadow-black/40"
            >
                <div className="flex items-center gap-2.5 px-4 py-3">
                    <Globe className="h-3.5 w-3.5 text-champagne" />
                    <span className="font-mono text-[10px] font-semibold uppercase tracking-[0.25em] text-champagne">
                        {t('app.language')}
                    </span>
                </div>

                <ul className="space-y-1">
                    {SUPPORTED_LOCALES.map((loc) => {
                        const meta = LOCALE_META[loc];
                        const isActive = loc === current;
                        const isArabic = loc === 'ar';
                        return (
                            <li key={loc}>
                                <button
                                    type="button"
                                    onClick={() => onSelect(loc)}
                                    className={cn(
                                        'group flex w-full items-center gap-4 rounded-xl px-4 py-3 text-left transition-colors',
                                        isActive
                                            ? 'bg-champagne/10 text-champagne'
                                            : 'text-foreground hover:bg-muted'
                                    )}
                                >
                                    <span
                                        className={cn(
                                            'inline-flex h-8 w-10 shrink-0 items-center justify-center rounded-md border font-mono text-[11px] font-semibold tracking-widest transition-colors',
                                            isActive
                                                ? 'border-champagne/40 bg-champagne/10 text-champagne'
                                                : 'border-border bg-background text-muted-foreground group-hover:border-champagne/40 group-hover:text-foreground'
                                        )}
                                    >
                                        {meta.native}
                                    </span>
                                    <span className="flex flex-1 items-baseline justify-between gap-3">
                                        <span className="font-display text-base font-semibold">
                                            {meta.label}
                                        </span>
                                        <span
                                            className={cn(
                                                'font-editorial italic text-muted-foreground',
                                                isArabic ? 'text-lg' : 'text-base'
                                            )}
                                            dir={isArabic ? 'rtl' : 'ltr'}
                                        >
                                            {meta.greeting}
                                        </span>
                                    </span>
                                    {isActive && <Check className="h-4 w-4 shrink-0" />}
                                </button>
                            </li>
                        );
                    })}
                </ul>

                <div className="flex items-center justify-end gap-1.5 px-4 pb-2 pt-3 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    <kbd className="rounded border border-border/60 bg-background px-1.5 py-0.5 text-[10px] text-foreground">
                        Esc
                    </kbd>
                    {t('app.esc_to_close')}
                </div>
            </motion.div>
        </motion.div>
    );
}
