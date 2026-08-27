import { motion } from 'framer-motion';
import { User } from 'lucide-react';
import type { Player } from '@/types/models';
import { cn } from '@/lib/utils';

interface Props {
    player: Player;
    index?: number;
}

export function PlayerCard({ player, index = 0 }: Props) {
    const age = new Date().getFullYear() - new Date(player.birthdate).getFullYear();
    const src = player.photo ? `/storage/${player.photo}` : null;

    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: (index % 8) * 0.04 }}
            className="group relative overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40"
        >
            <div className="pointer-events-none absolute inset-0 bg-gradient-to-b from-transparent via-transparent to-crimson/20 opacity-0 transition-opacity duration-500 group-hover:opacity-100" />

            {/* Number */}
            <div className="absolute right-4 top-3 z-10 font-display text-6xl font-bold leading-none text-champagne/25 transition-colors duration-300 group-hover:text-champagne/60">
                {player.number}
            </div>

            {/* Photo */}
            <div className="relative aspect-[3/4] overflow-hidden bg-muted">
                {src ? (
                    <img
                        src={src}
                        alt={`${player.first_name} ${player.last_name}`}
                        loading="lazy"
                        className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center bg-gradient-to-b from-muted to-card">
                        <User className="h-24 w-24 text-muted-foreground/30" strokeWidth={1} />
                    </div>
                )}
                <div className="absolute inset-x-0 bottom-0 h-40 bg-gradient-to-t from-card via-card/70 to-transparent" />
            </div>

            {/* Info */}
            <div className="relative -mt-16 px-5 pb-5">
                <div className="font-mono text-[10px] uppercase tracking-widest text-crimson">
                    {player.position}
                </div>
                <h3 className="mt-1 font-display text-lg font-semibold leading-tight">
                    <span className="block text-muted-foreground">{player.first_name}</span>
                    <span className="block text-foreground">{player.last_name}</span>
                </h3>

                <div className="mt-4 grid grid-cols-3 gap-2 border-t border-border pt-4 text-xs">
                    <Stat label="Âge" value={String(age)} />
                    <Stat label="Nat." value={player.nationality?.slice(0, 3).toUpperCase() ?? '—'} />
                    <Stat label="Taille" value={`${player.height}`} suffix="cm" />
                </div>
            </div>
        </motion.article>
    );
}

function Stat({ label, value, suffix }: { label: string; value: string; suffix?: string }) {
    return (
        <div>
            <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                {label}
            </div>
            <div className="mt-0.5 font-display text-sm font-semibold tabular-nums">
                {value}
                {suffix && <span className="text-[10px] font-normal text-muted-foreground">{suffix}</span>}
            </div>
        </div>
    );
}
