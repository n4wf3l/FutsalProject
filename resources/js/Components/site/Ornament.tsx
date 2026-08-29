import { cn } from '@/lib/utils';

/**
 * Hand-drawn flourish divider — used to punctuate editorial content.
 * SVG made by hand, deliberately asymmetric so it doesn't look generated.
 */
export function Ornament({ className }: { className?: string }) {
    return (
        <svg
            viewBox="0 0 220 24"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden
            className={cn('mx-auto h-6 w-40 text-champagne/60', className)}
        >
            <path
                d="M2 12 C 30 4, 50 20, 78 12 L 92 12"
                stroke="currentColor"
                strokeWidth="1.4"
                strokeLinecap="round"
                fill="none"
            />
            <circle cx="110" cy="12" r="3.5" fill="none" stroke="currentColor" strokeWidth="1.4" />
            <circle cx="110" cy="12" r="0.9" fill="currentColor" />
            <path
                d="M128 12 L 142 12 C 170 20, 190 4, 218 12"
                stroke="currentColor"
                strokeWidth="1.4"
                strokeLinecap="round"
                fill="none"
            />
        </svg>
    );
}

/**
 * Small "DKFC" monogram stamp — hand-set letterforms, tilted slightly for a
 * ex-libris feel. Used at the end of editorial pieces as a signature.
 */
export function Monogram({ className }: { className?: string }) {
    return (
        <div
            className={cn(
                'inline-flex -rotate-3 items-center gap-1 rounded-sm border-2 border-champagne/70 px-2 py-1 font-editorial text-xs italic tracking-widest text-champagne/70',
                className
            )}
        >
            <span>DKFC</span>
            <span aria-hidden className="text-champagne/40">·</span>
            <span className="not-italic">EST. 2011</span>
        </div>
    );
}
