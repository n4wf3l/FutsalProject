import { Moon, Sun } from 'lucide-react';
import { AnimatePresence, motion } from 'framer-motion';
import { useTheme } from '@/lib/theme';
import { cn } from '@/lib/utils';

export function ThemeToggle({ className }: { className?: string }) {
    const { theme, toggle } = useTheme();
    const isDark = theme === 'dark';

    return (
        <button
            type="button"
            onClick={toggle}
            aria-label={isDark ? 'Passer en mode clair' : 'Passer en mode sombre'}
            className={cn(
                'group relative inline-flex h-10 w-10 items-center justify-center rounded-full border border-border bg-card/60 text-foreground transition-all hover:border-champagne/50 hover:text-champagne',
                className
            )}
        >
            <AnimatePresence mode="wait" initial={false}>
                <motion.span
                    key={isDark ? 'moon' : 'sun'}
                    initial={{ opacity: 0, rotate: -90, scale: 0.6 }}
                    animate={{ opacity: 1, rotate: 0, scale: 1 }}
                    exit={{ opacity: 0, rotate: 90, scale: 0.6 }}
                    transition={{ duration: 0.25, ease: [0.22, 1, 0.36, 1] }}
                    className="absolute inset-0 flex items-center justify-center"
                >
                    {isDark ? <Moon className="h-4 w-4" /> : <Sun className="h-4 w-4" />}
                </motion.span>
            </AnimatePresence>
        </button>
    );
}
