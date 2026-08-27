import { Shield } from 'lucide-react';
import type { Team } from '@/types/models';
import { cn } from '@/lib/utils';

interface Props {
    team?: Team | null;
    size?: 'sm' | 'md' | 'lg' | 'xl';
    isClub?: boolean;
    className?: string;
}

const SIZES = {
    sm: 'h-10 w-10',
    md: 'h-16 w-16',
    lg: 'h-24 w-24',
    xl: 'h-32 w-32',
};

export function TeamBadge({ team, size = 'md', isClub = false, className }: Props) {
    const src = team?.logo_path
        ? isClub && team.logo_path.startsWith('demo/')
            ? '/logo-dinakenitra.png'
            : team.logo_path.startsWith('http')
              ? team.logo_path
              : `/storage/${team.logo_path}`
        : null;

    return (
        <div
            className={cn(
                'relative flex items-center justify-center rounded-full border border-border bg-card p-1 shadow-sm',
                isClub && 'shadow-glow-crimson border-crimson/30',
                SIZES[size],
                className
            )}
        >
            {src && !src.includes('demo/') ? (
                <img
                    src={src}
                    alt={team?.name ?? 'Team'}
                    className="h-full w-full rounded-full object-contain"
                    onError={(e) => {
                        (e.currentTarget as HTMLImageElement).style.display = 'none';
                        const fallback = e.currentTarget.nextElementSibling as HTMLElement | null;
                        if (fallback) fallback.style.display = 'flex';
                    }}
                />
            ) : null}
            {isClub ? (
                <img
                    src="/logo-dinakenitra.png"
                    alt={team?.name ?? 'Dina Kenitra FC'}
                    className="h-full w-full rounded-full object-contain"
                />
            ) : (
                <div
                    className="hidden h-full w-full items-center justify-center rounded-full bg-muted text-muted-foreground"
                    style={{ display: src && !src.includes('demo/') ? 'none' : 'flex' }}
                >
                    <Shield className="h-1/2 w-1/2" />
                </div>
            )}
        </div>
    );
}
