import { motion } from 'framer-motion';
import { Calendar, MapPin } from 'lucide-react';
import type { Game } from '@/types/models';
import { Badge } from '@/Components/ui/Badge';
import { TeamBadge } from './TeamBadge';
import { formatMatchDate } from '@/lib/utils';

interface Props {
    game: Game;
    variant?: 'result' | 'upcoming';
    clubPrefix: string;
    venue?: string;
}

export function MatchCard({ game, variant = 'result', clubPrefix, venue }: Props) {
    const isHome = game.homeTeam?.name?.startsWith(clubPrefix) ?? false;
    const date = formatMatchDate(game.match_date);
    const isPlayed = variant === 'result' && game.home_score !== null && game.away_score !== null;
    const homeWin = isPlayed && (game.home_score ?? 0) > (game.away_score ?? 0);
    const awayWin = isPlayed && (game.away_score ?? 0) > (game.home_score ?? 0);
    const draw = isPlayed && game.home_score === game.away_score;

    let resultLabel: 'V' | 'N' | 'D' | null = null;
    let resultVariant: 'win' | 'muted' | 'live' = 'muted';
    if (isPlayed) {
        const clubWon = (isHome && homeWin) || (!isHome && awayWin);
        if (draw) {
            resultLabel = 'N';
            resultVariant = 'muted';
        } else if (clubWon) {
            resultLabel = 'V';
            resultVariant = 'win';
        } else {
            resultLabel = 'D';
            resultVariant = 'live';
        }
    }

    return (
        <motion.article
            initial={{ opacity: 0, y: 16 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-80px' }}
            transition={{ duration: 0.5, ease: [0.22, 1, 0.36, 1] }}
            className="group relative overflow-hidden rounded-2xl border border-border bg-card p-6 transition-all hover:border-crimson/40 hover:shadow-glow-crimson"
        >
            <div className="pointer-events-none absolute inset-0 bg-gradient-to-br from-crimson/0 via-transparent to-champagne/0 opacity-0 transition-opacity group-hover:opacity-100" />

            <div className="relative flex items-center justify-between">
                <div className="flex items-center gap-3">
                    <Badge variant={variant === 'result' ? 'muted' : 'soon'}>
                        {variant === 'result' ? 'Résultat' : 'À venir'}
                    </Badge>
                    {resultLabel && (
                        <Badge variant={resultVariant} className="font-mono">
                            {resultLabel}
                        </Badge>
                    )}
                </div>
                <div className="flex items-center gap-1.5 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                    <Calendar className="h-3 w-3" />
                    <span>
                        {date.day} {date.month}
                    </span>
                </div>
            </div>

            <div className="relative mt-6 grid grid-cols-[1fr_auto_1fr] items-center gap-4">
                <div className="flex flex-col items-center gap-3 text-center">
                    <TeamBadge
                        team={game.homeTeam}
                        size="md"
                        isClub={game.homeTeam?.name?.startsWith(clubPrefix) ?? false}
                    />
                    <div className="text-sm font-semibold leading-tight">
                        {game.homeTeam?.name ?? '—'}
                    </div>
                </div>

                <div className="flex flex-col items-center gap-1">
                    {isPlayed ? (
                        <div className="font-mono text-4xl font-bold tracking-tighter tabular-nums text-foreground">
                            <span className={homeWin ? 'text-champagne' : ''}>{game.home_score}</span>
                            <span className="mx-2 text-muted-foreground">:</span>
                            <span className={awayWin ? 'text-champagne' : ''}>{game.away_score}</span>
                        </div>
                    ) : (
                        <div className="font-mono text-2xl font-bold tabular-nums text-foreground">
                            {date.time}
                        </div>
                    )}
                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {isPlayed ? 'Final' : date.weekday}
                    </div>
                </div>

                <div className="flex flex-col items-center gap-3 text-center">
                    <TeamBadge
                        team={game.awayTeam}
                        size="md"
                        isClub={game.awayTeam?.name?.startsWith(clubPrefix) ?? false}
                    />
                    <div className="text-sm font-semibold leading-tight">
                        {game.awayTeam?.name ?? '—'}
                    </div>
                </div>
            </div>

            {venue && (
                <div className="relative mt-6 flex items-center justify-center gap-1.5 text-xs text-muted-foreground">
                    <MapPin className="h-3 w-3 text-crimson" />
                    <span>{venue}</span>
                </div>
            )}
        </motion.article>
    );
}
