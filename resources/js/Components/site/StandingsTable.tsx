import type { Team } from '@/types/models';
import { cn } from '@/lib/utils';

interface Props {
    teams: Team[];
    clubPrefix: string;
    className?: string;
}

export function StandingsTable({ teams, clubPrefix, className }: Props) {
    return (
        <div className={cn('overflow-hidden rounded-2xl border border-border bg-card', className)}>
            <div className="border-b border-border bg-muted/50 px-5 py-3">
                <div className="grid grid-cols-[24px_1fr_repeat(6,32px)_40px] items-center gap-2 font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground sm:grid-cols-[24px_1fr_repeat(6,40px)_48px]">
                    <div>#</div>
                    <div>Équipe</div>
                    <div className="text-center">J</div>
                    <div className="text-center">V</div>
                    <div className="text-center">N</div>
                    <div className="text-center">D</div>
                    <div className="text-center">BP</div>
                    <div className="text-center">BC</div>
                    <div className="text-center text-champagne">PTS</div>
                </div>
            </div>

            <ul className="divide-y divide-border">
                {teams.map((team, i) => {
                    const isClub = team.name.startsWith(clubPrefix);
                    return (
                        <li
                            key={team.id}
                            className={cn(
                                'grid grid-cols-[24px_1fr_repeat(6,32px)_40px] items-center gap-2 px-5 py-3 text-sm transition-colors hover:bg-muted/40 sm:grid-cols-[24px_1fr_repeat(6,40px)_48px]',
                                isClub && 'bg-crimson/5'
                            )}
                        >
                            <div
                                className={cn(
                                    'font-mono font-semibold tabular-nums',
                                    i < 3 && 'text-champagne',
                                    i === teams.length - 1 && i > 3 && 'text-plasma'
                                )}
                            >
                                {i + 1}
                            </div>
                            <div className={cn('flex items-center gap-2 min-w-0', isClub && 'text-crimson')}>
                                {isClub && <span className="h-6 w-1 rounded-full bg-crimson" />}
                                <span className="truncate font-semibold">{team.name}</span>
                            </div>
                            <TC v={team.games_played} />
                            <TC v={team.wins} />
                            <TC v={team.draws} />
                            <TC v={team.losses} />
                            <TC v={team.goals_for} />
                            <TC v={team.goals_against} />
                            <div className="text-center font-mono font-bold text-champagne tabular-nums">
                                {team.points ?? 0}
                            </div>
                        </li>
                    );
                })}
            </ul>

            {teams.length === 0 && (
                <div className="p-10 text-center text-sm text-muted-foreground">
                    Le classement n'est pas encore disponible.
                </div>
            )}
        </div>
    );
}

function TC({ v }: { v?: number }) {
    return (
        <div className="text-center font-mono text-muted-foreground tabular-nums">
            {v ?? 0}
        </div>
    );
}
