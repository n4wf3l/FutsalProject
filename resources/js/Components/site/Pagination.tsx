import { Link } from '@inertiajs/react';
import { ChevronLeft, ChevronRight } from 'lucide-react';
import { cn } from '@/lib/utils';

interface PaginationLink {
    url: string | null;
    label: string;
    active: boolean;
}

interface Props {
    links: PaginationLink[];
    className?: string;
}

export function Pagination({ links, className }: Props) {
    if (!links || links.length <= 3) return null;

    return (
        <nav
            className={cn(
                'flex flex-wrap items-center justify-center gap-1',
                className
            )}
            aria-label="Pagination"
        >
            {links.map((link, i) => {
                const isPrev = i === 0;
                const isNext = i === links.length - 1;
                const label = isPrev ? (
                    <ChevronLeft className="h-4 w-4" />
                ) : isNext ? (
                    <ChevronRight className="h-4 w-4" />
                ) : (
                    <span dangerouslySetInnerHTML={{ __html: link.label }} />
                );

                if (!link.url) {
                    return (
                        <span
                            key={i}
                            className={cn(
                                'inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-transparent px-3 text-sm text-muted-foreground/40',
                                (isPrev || isNext) && 'w-9 px-0'
                            )}
                        >
                            {label}
                        </span>
                    );
                }

                return (
                    <Link
                        key={i}
                        href={link.url}
                        preserveScroll
                        className={cn(
                            'inline-flex h-9 min-w-9 items-center justify-center rounded-lg border px-3 text-sm font-medium transition-colors',
                            link.active
                                ? 'border-crimson bg-crimson text-crimson-foreground shadow-glow-crimson'
                                : 'border-border bg-card text-foreground hover:border-crimson/50 hover:text-crimson',
                            (isPrev || isNext) && 'w-9 px-0'
                        )}
                    >
                        {label}
                    </Link>
                );
            })}
        </nav>
    );
}
