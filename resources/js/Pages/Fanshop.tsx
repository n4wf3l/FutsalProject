import { Head, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Calendar, MapPin, Ticket, Trophy, Users, ShieldCheck } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { TeamBadge } from '@/Components/site/TeamBadge';
import { Button } from '@/Components/ui/Button';
import { Badge } from '@/Components/ui/Badge';
import { formatMatchDate } from '@/lib/utils';
import type { Championship, Game, Tribune } from '@/types/models';

interface Props {
    tribunes: Tribune[];
    nextGame: Game | null;
    championship: Championship | null;
    clubPrefix: string;
}

export default function Fanshop({ tribunes, nextGame, championship, clubPrefix }: Props) {
    return (
        <SiteLayout>
            <Head title="Billetterie" />

            <PageHeader
                kicker="Billetterie"
                title="Prends ta place"
                subtitle="Choisis ta tribune, achète ton ticket et viens vibrer avec nous au prochain match."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Fanshop' }]}
            >
                {championship && (
                    <Badge variant="champagne">
                        <Trophy className="h-3 w-3" />
                        {championship.name} · {championship.season}
                    </Badge>
                )}
            </PageHeader>

            {/* Next Game highlight */}
            {nextGame && (
                <section className="mx-auto max-w-7xl px-4 py-8">
                    <motion.div
                        initial={{ opacity: 0, y: 20 }}
                        whileInView={{ opacity: 1, y: 0 }}
                        viewport={{ once: true }}
                        className="relative overflow-hidden rounded-3xl border border-champagne/20 bg-gradient-to-br from-card via-card to-crimson/5 p-8"
                    >
                        <div className="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-crimson/20 blur-3xl" aria-hidden />

                        <div className="relative flex flex-wrap items-center justify-between gap-6">
                            <div>
                                <Badge variant="live">
                                    <span className="live-dot" />
                                    Prochain match à domicile
                                </Badge>
                                <div className="mt-4 grid grid-cols-[auto_auto_auto] items-center gap-6">
                                    <div className="flex flex-col items-center gap-2">
                                        <TeamBadge
                                            team={nextGame.homeTeam}
                                            isClub={nextGame.homeTeam?.name?.startsWith(clubPrefix) ?? false}
                                            size="md"
                                        />
                                        <div className="text-xs font-semibold">
                                            {nextGame.homeTeam?.name}
                                        </div>
                                    </div>
                                    <div className="flex flex-col items-center">
                                        <div className="font-mono text-xs uppercase tracking-widest text-champagne">
                                            VS
                                        </div>
                                        <div className="font-display text-2xl font-bold tabular-nums">
                                            {formatMatchDate(nextGame.match_date).time}
                                        </div>
                                    </div>
                                    <div className="flex flex-col items-center gap-2">
                                        <TeamBadge
                                            team={nextGame.awayTeam}
                                            isClub={nextGame.awayTeam?.name?.startsWith(clubPrefix) ?? false}
                                            size="md"
                                        />
                                        <div className="text-xs font-semibold">
                                            {nextGame.awayTeam?.name}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div className="flex flex-col gap-3 text-right">
                                <div className="flex items-center justify-end gap-2 text-sm text-muted-foreground">
                                    <Calendar className="h-4 w-4 text-crimson" />
                                    {formatMatchDate(nextGame.match_date).weekday}{' '}
                                    {formatMatchDate(nextGame.match_date).day}{' '}
                                    {formatMatchDate(nextGame.match_date).month}
                                </div>
                                <div className="flex items-center justify-end gap-2 text-sm text-muted-foreground">
                                    <MapPin className="h-4 w-4 text-crimson" />
                                    Complexe Sportif Municipal
                                </div>
                            </div>
                        </div>
                    </motion.div>
                </section>
            )}

            {/* Tribunes */}
            <section className="mx-auto max-w-7xl px-4 pb-16 pt-4">
                {tribunes.length === 0 ? (
                    <EmptyState
                        icon={Ticket}
                        title="Aucune tribune ouverte pour l'instant"
                        description="La billetterie ouvrira dès la programmation du prochain match."
                    />
                ) : (
                    <div className="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        {tribunes.map((t, i) => (
                            <TribuneCard key={t.id} tribune={t} index={i} game={nextGame} />
                        ))}
                    </div>
                )}
            </section>

            {/* Trust strip */}
            <section className="mx-auto max-w-7xl px-4 pb-16">
                <div className="grid gap-4 sm:grid-cols-3">
                    {[
                        { icon: ShieldCheck, title: 'Paiement sécurisé', desc: 'Powered by Stripe · TLS 1.3' },
                        { icon: Ticket, title: 'E-ticket instantané', desc: 'PDF + QR code par email' },
                        { icon: Users, title: 'Support 7j/7', desc: 'contact@dinakenitrafc.ma' },
                    ].map((f) => (
                        <div
                            key={f.title}
                            className="glass rounded-2xl p-5"
                        >
                            <f.icon className="h-5 w-5 text-champagne" />
                            <div className="mt-3 font-display text-sm font-semibold">{f.title}</div>
                            <div className="mt-0.5 text-xs text-muted-foreground">{f.desc}</div>
                        </div>
                    ))}
                </div>
            </section>
        </SiteLayout>
    );
}

function TribuneCard({
    tribune,
    index,
    game,
}: {
    tribune: Tribune;
    index: number;
    game: Game | null;
}) {
    const { data, setData, post, processing } = useForm({
        tribune_id: tribune.id,
        game_id: game?.id ?? null,
        quantity: 1,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/checkout');
    };

    const soldOut = tribune.available_seats <= 0;

    return (
        <motion.article
            initial={{ opacity: 0, y: 20 }}
            whileInView={{ opacity: 1, y: 0 }}
            viewport={{ once: true, margin: '-40px' }}
            transition={{ duration: 0.5, delay: (index % 6) * 0.06 }}
            className="group overflow-hidden rounded-2xl border border-border bg-card transition-all hover:border-crimson/40 hover:shadow-glow-crimson"
        >
            <div className="relative aspect-video overflow-hidden bg-muted">
                {tribune.photo ? (
                    <img
                        src={`/storage/${tribune.photo}`}
                        alt={tribune.name}
                        loading="lazy"
                        className="h-full w-full object-cover transition-transform duration-700 group-hover:scale-105"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center">
                        <Ticket className="h-12 w-12 text-muted-foreground/30" />
                    </div>
                )}
                <div className="absolute left-4 top-4">
                    {soldOut ? (
                        <Badge variant="live">Complet</Badge>
                    ) : (
                        <Badge variant="win">{tribune.available_seats} places</Badge>
                    )}
                </div>
            </div>

            <form onSubmit={submit} className="p-5">
                <div className="flex items-start justify-between gap-2">
                    <div>
                        <h3 className="font-display text-lg font-semibold leading-tight">
                            {tribune.name}
                        </h3>
                        {tribune.description && (
                            <p className="mt-1 line-clamp-2 text-xs text-muted-foreground">
                                {tribune.description}
                            </p>
                        )}
                    </div>
                    <div className="text-right">
                        <div className="font-display text-2xl font-bold tabular-nums text-champagne">
                            {tribune.price}
                        </div>
                        <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                            {tribune.currency}
                        </div>
                    </div>
                </div>

                <div className="mt-5 flex items-center gap-2">
                    <div className="flex items-center rounded-lg border border-border">
                        <button
                            type="button"
                            onClick={() => setData('quantity', Math.max(1, data.quantity - 1))}
                            className="h-9 w-9 text-muted-foreground hover:text-foreground"
                            disabled={data.quantity <= 1}
                        >
                            −
                        </button>
                        <div className="w-8 text-center font-mono text-sm font-semibold">
                            {data.quantity}
                        </div>
                        <button
                            type="button"
                            onClick={() =>
                                setData(
                                    'quantity',
                                    Math.min(tribune.available_seats, data.quantity + 1)
                                )
                            }
                            className="h-9 w-9 text-muted-foreground hover:text-foreground"
                            disabled={data.quantity >= tribune.available_seats}
                        >
                            +
                        </button>
                    </div>
                    <Button
                        type="submit"
                        disabled={soldOut || processing || !game}
                        className="flex-1"
                    >
                        <Ticket className="h-4 w-4" />
                        {soldOut ? 'Complet' : !game ? 'Bientôt' : 'Acheter'}
                    </Button>
                </div>
            </form>
        </motion.article>
    );
}
