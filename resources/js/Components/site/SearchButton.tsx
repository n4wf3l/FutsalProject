import { useCallback, useEffect, useRef, useState } from 'react';
import { createPortal } from 'react-dom';
import { router } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { Search, X } from 'lucide-react';
import { useTranslation } from 'react-i18next';

/**
 * Fixed floating search entry point. Renders a small round button in the
 * bottom-left corner of the viewport. Clicking it opens a full-screen
 * editorial search modal (via portal, so it escapes any transformed ancestor
 * per CLAUDE.md CSS pitfalls).
 */
export function SearchButton() {
    const { t } = useTranslation('common');
    const [open, setOpen] = useState(false);
    const [value, setValue] = useState('');
    const inputRef = useRef<HTMLInputElement>(null);

    const close = useCallback(() => {
        setOpen(false);
        setValue('');
    }, []);

    const submit = useCallback(
        (e: React.FormEvent) => {
            e.preventDefault();
            const q = value.trim();
            if (q.length < 2) return;
            close();
            router.visit(`/search?q=${encodeURIComponent(q)}`);
        },
        [value, close],
    );

    useEffect(() => {
        if (!open) return;
        const onKey = (e: KeyboardEvent) => {
            if (e.key === 'Escape') close();
        };
        window.addEventListener('keydown', onKey);
        document.body.style.overflow = 'hidden';
        // focus the input on next frame so the animation does not steal focus
        const t = window.setTimeout(() => inputRef.current?.focus(), 60);
        return () => {
            window.removeEventListener('keydown', onKey);
            document.body.style.overflow = '';
            window.clearTimeout(t);
        };
    }, [open, close]);

    return (
        <>
            <button
                type="button"
                onClick={() => setOpen(true)}
                aria-label={t('search.open')}
                className="fixed bottom-6 left-6 z-40 inline-flex h-12 w-12 items-center justify-center rounded-full border border-border bg-card text-foreground shadow-lg transition-colors hover:border-crimson hover:text-crimson focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-crimson"
            >
                <Search className="h-5 w-5" />
            </button>

            {typeof document !== 'undefined' &&
                createPortal(
                    <AnimatePresence>
                        {open && (
                            <motion.div
                                key="search-modal"
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                exit={{ opacity: 0 }}
                                transition={{ duration: 0.2 }}
                                className="fixed inset-0 z-[100] flex flex-col items-stretch bg-background"
                            >
                                <button
                                    type="button"
                                    onClick={close}
                                    aria-label={t('search.close')}
                                    className="absolute right-6 top-6 inline-flex h-11 w-11 items-center justify-center rounded-full border border-border bg-card text-foreground transition-colors hover:border-crimson hover:text-crimson focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-crimson"
                                >
                                    <X className="h-5 w-5" />
                                </button>

                                <div className="mx-auto flex w-full max-w-4xl flex-1 flex-col justify-center px-6">
                                    <div className="mb-6 flex items-center gap-3 font-mono text-[11px] uppercase tracking-[0.2em] text-champagne">
                                        <span className="h-px w-8 bg-champagne" />
                                        {t('search.kicker')}
                                    </div>

                                    <form onSubmit={submit}>
                                        <div className="relative border-b-2 border-border transition-colors focus-within:border-crimson">
                                            <Search className="pointer-events-none absolute left-0 top-1/2 h-6 w-6 -translate-y-1/2 text-muted-foreground" />
                                            <input
                                                ref={inputRef}
                                                type="search"
                                                value={value}
                                                onChange={(e) => setValue(e.target.value)}
                                                placeholder={t('search.placeholder')}
                                                className="w-full bg-transparent py-6 pl-12 pr-4 font-display text-2xl text-foreground placeholder:font-editorial placeholder:italic placeholder:text-muted-foreground/60 focus:outline-none sm:text-3xl md:text-4xl"
                                                aria-label={t('search.placeholder')}
                                            />
                                        </div>

                                        <div className="mt-6 flex flex-wrap items-center justify-between gap-4 text-xs text-muted-foreground">
                                            <span>
                                                {t('search.hint')}
                                            </span>
                                            <span className="font-mono uppercase tracking-widest">
                                                {t('search.enter_to_search')}
                                            </span>
                                        </div>
                                    </form>
                                </div>
                            </motion.div>
                        )}
                    </AnimatePresence>,
                    document.body,
                )}
        </>
    );
}
