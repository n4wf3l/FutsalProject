import { useEffect, useRef, useState } from 'react';
import { Link } from '@inertiajs/react';
import { motion, AnimatePresence } from 'framer-motion';
import { ChevronDown, type LucideIcon } from 'lucide-react';
import { cn } from '@/lib/utils';

export interface MegaItem {
    label: string;
    href: string;
    icon?: LucideIcon;
    description?: string;
}

interface Props {
    label: string;
    items: MegaItem[];
    isActive?: boolean;
    align?: 'left' | 'center';
}

export function MegaMenu({ label, items, isActive, align = 'center' }: Props) {
    const [open, setOpen] = useState(false);
    const [focused, setFocused] = useState(-1);
    const wrapRef = useRef<HTMLDivElement>(null);
    const timerRef = useRef<number | null>(null);
    const itemRefs = useRef<Array<HTMLAnchorElement | null>>([]);

    const cancelClose = () => {
        if (timerRef.current) {
            window.clearTimeout(timerRef.current);
            timerRef.current = null;
        }
    };

    const scheduleClose = () => {
        cancelClose();
        timerRef.current = window.setTimeout(() => setOpen(false), 140);
    };

    useEffect(() => {
        if (!open) return;
        const onKey = (e: KeyboardEvent) => {
            if (e.key === 'Escape') {
                setOpen(false);
                setFocused(-1);
            }
            if (e.key === 'ArrowDown') {
                e.preventDefault();
                setFocused((i) => {
                    const next = i < items.length - 1 ? i + 1 : 0;
                    itemRefs.current[next]?.focus();
                    return next;
                });
            }
            if (e.key === 'ArrowUp') {
                e.preventDefault();
                setFocused((i) => {
                    const next = i > 0 ? i - 1 : items.length - 1;
                    itemRefs.current[next]?.focus();
                    return next;
                });
            }
        };
        window.addEventListener('keydown', onKey);
        return () => window.removeEventListener('keydown', onKey);
    }, [open, items.length]);

    useEffect(() => {
        const onClickOutside = (e: MouseEvent) => {
            if (wrapRef.current && !wrapRef.current.contains(e.target as Node)) {
                setOpen(false);
            }
        };
        if (open) document.addEventListener('mousedown', onClickOutside);
        return () => document.removeEventListener('mousedown', onClickOutside);
    }, [open]);

    return (
        <div
            ref={wrapRef}
            className="relative"
            onMouseEnter={() => {
                cancelClose();
                setOpen(true);
            }}
            onMouseLeave={scheduleClose}
        >
            <button
                type="button"
                onClick={() => setOpen((o) => !o)}
                onFocus={() => setOpen(true)}
                aria-haspopup="true"
                aria-expanded={open}
                className={cn(
                    'relative flex items-center gap-1 rounded-lg px-4 py-2 text-sm font-medium transition-colors',
                    isActive || open
                        ? 'text-foreground'
                        : 'text-muted-foreground hover:text-foreground'
                )}
            >
                {label}
                <ChevronDown
                    className={cn(
                        'h-3.5 w-3.5 transition-transform',
                        open && 'rotate-180'
                    )}
                />
                {isActive && (
                    <motion.span
                        layoutId="nav-underline"
                        className="absolute inset-x-3 -bottom-0.5 h-0.5 rounded-full bg-gradient-to-r from-crimson via-champagne to-crimson"
                        transition={{ type: 'spring', stiffness: 400, damping: 32 }}
                    />
                )}
            </button>

            <AnimatePresence>
                {open && (
                    <motion.div
                        initial={{ opacity: 0, y: -6, scale: 0.98 }}
                        animate={{ opacity: 1, y: 0, scale: 1 }}
                        exit={{ opacity: 0, y: -6, scale: 0.98 }}
                        transition={{ duration: 0.15, ease: [0.22, 1, 0.36, 1] }}
                        className={cn(
                            'absolute top-full z-50 mt-2 min-w-[280px] overflow-hidden rounded-2xl border border-border bg-card p-2 shadow-xl shadow-black/20',
                            align === 'center' && 'left-1/2 -translate-x-1/2',
                            align === 'left' && 'left-0'
                        )}
                        role="menu"
                    >
                        {items.map((item, i) => (
                            <Link
                                key={item.href}
                                ref={(el: HTMLAnchorElement | null) => {
                                    itemRefs.current[i] = el;
                                }}
                                href={item.href}
                                onClick={() => setOpen(false)}
                                role="menuitem"
                                className={cn(
                                    'group flex items-start gap-3 rounded-lg px-3 py-2.5 transition-colors',
                                    focused === i
                                        ? 'bg-muted'
                                        : 'hover:bg-muted'
                                )}
                            >
                                {item.icon && (
                                    <div className="mt-0.5 rounded-md border border-border bg-background p-1.5 transition-colors group-hover:border-crimson/40">
                                        <item.icon className="h-3.5 w-3.5 text-champagne" />
                                    </div>
                                )}
                                <div className="min-w-0 flex-1">
                                    <div className="text-sm font-semibold text-foreground transition-colors group-hover:text-crimson">
                                        {item.label}
                                    </div>
                                    {item.description && (
                                        <div className="mt-0.5 text-xs text-muted-foreground">
                                            {item.description}
                                        </div>
                                    )}
                                </div>
                            </Link>
                        ))}
                    </motion.div>
                )}
            </AnimatePresence>
        </div>
    );
}
