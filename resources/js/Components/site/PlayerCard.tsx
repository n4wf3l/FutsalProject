import { motion } from 'framer-motion';
import { Hand, Shield, Target, User, Wind, type LucideIcon } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import type { Player, PlayerEspoir } from '@/types/models';
import { cn } from '@/lib/utils';
import { SmartImage } from './SmartImage';

interface Props {
    player: Player | PlayerEspoir;
    index?: number;
}

const POSITION_ICONS: Record<string, LucideIcon> = {
    Gardien: Hand,
    Fixe: Shield,
    Ailier: Wind,
    Pivot: Target,
};

export function PlayerCard({ player, index = 0 }: Props) {
    const { t } = useTranslation('common');
    const src = player.photo ? `/storage/${player.photo}` : null;
    const isKeeper = /gardien|goal|gk/i.test(player.position ?? '');
    const positionLabel = t(`positions.${player.position}`, { defaultValue: player.position });
    const PositionIcon = POSITION_ICONS[player.position] ?? User;

    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: (index % 8) * 0.04 }}
            className={cn(
                'group relative overflow-hidden rounded-2xl border bg-card transition-all',
                isKeeper
                    ? 'border-champagne bg-champagne/[0.06] hover:border-champagne'
                    : 'border-border hover:border-crimson/40'
            )}
        >
            {/* Top ribbon for goalkeepers, immediate visual differentiator */}
            {isKeeper && <div className="absolute inset-x-0 top-0 z-10 h-1 bg-champagne" />}

            {/* Club crest and jersey number, stacked in the top-right corner */}
            <div className="absolute right-4 top-4 z-10 flex items-start gap-2.5">
                <img
                    src="/logo-dinakenitra.png"
                    alt=""
                    aria-hidden="true"
                    className="mt-2 h-7 w-7 shrink-0 object-contain opacity-70 group-hover:opacity-100"
                />
                <div
                    className={cn(
                        'font-editorial text-6xl italic leading-none transition-all duration-500 group-hover:-translate-y-1 group-hover:text-champagne',
                        isKeeper ? 'text-champagne/70' : 'text-champagne/30'
                    )}
                >
                    {player.number}
                </div>
            </div>

            {/* Portrait or centered position icon so the card is never empty */}
            <div className="relative aspect-[3/4] overflow-hidden bg-muted">
                {src ? (
                    <SmartImage
                        src={src}
                        alt={`${player.first_name} ${player.last_name}`}
                        className="group-hover:scale-105"
                    />
                ) : (
                    <div
                        className={cn(
                            'flex h-full w-full items-center justify-center bg-gradient-to-b',
                            isKeeper ? 'from-champagne/10 to-transparent' : 'from-muted to-card'
                        )}
                    >
                        <PositionIcon
                            className={cn(
                                'h-32 w-32 transition-transform duration-500 group-hover:scale-110',
                                isKeeper ? 'text-champagne/60' : 'text-muted-foreground/25'
                            )}
                            strokeWidth={1}
                        />
                    </div>
                )}
                <div className="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-card via-card/70 to-transparent" />
            </div>

            {/* Info block */}
            <div className="relative -mt-20 px-5 pb-5">
                <div className="flex flex-wrap items-center gap-2">
                    <span
                        className={cn(
                            'inline-flex items-center gap-1.5 rounded-md border px-2.5 py-1 font-mono text-[10px] font-semibold uppercase tracking-widest',
                            isKeeper
                                ? 'border-champagne/60 bg-champagne/15 text-champagne'
                                : 'border-crimson/50 bg-crimson/10 text-crimson'
                        )}
                    >
                        <PositionIcon className="h-3 w-3" strokeWidth={2} />
                        {positionLabel}
                    </span>
                    {player.nationality && (
                        <span className="inline-flex items-center rounded-md border border-border bg-background/60 px-2 py-1 font-mono text-[10px] font-semibold tracking-widest text-muted-foreground">
                            {player.nationality.slice(0, 3).toUpperCase()}
                        </span>
                    )}
                </div>

                <h3 className="mt-3 font-display text-xl font-semibold leading-tight">
                    <span className="block text-muted-foreground">{player.first_name}</span>
                    <span className="block text-foreground">{player.last_name}</span>
                </h3>
            </div>
        </motion.article>
    );
}
