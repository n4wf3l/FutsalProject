import { Head, Link, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import {
    ArrowRight,
    Calendar,
    CheckCircle2,
    Image as ImageIcon,
    Inbox,
    LayoutDashboard,
    Mic,
    Newspaper,
    Plus,
    Ticket,
    Trophy,
    Users,
    UserCog,
    type LucideIcon,
} from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { TeamBadge } from '@/Components/site/TeamBadge';
import { EmptyState } from '@/Components/site/EmptyState';
import { formatMatchDate, cn } from '@/lib/utils';
import type { Article, ClubInfoShared, Game } from '@/types/models';

interface PendingApplication {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    category: string;
    created_at: string;
}

interface DraftInterview {
    id: number;
    title: string;
    interviewee_name: string;
    interviewee_role: string;
    updated_at: string;
}

interface DashboardProps {
    stats: {
        players: number;
        playersU21: number;
        staff: number;
        coaches: number;
        games: number;
        articles: number;
        videos: number;
        photos: number;
        tribunes: number;
        users: number;
        upcomingGames: number;
        pastGames: number;
    };
    inbox: {
        applicationsPending: number;
        interviewsDraft: number;
    };
    pendingApplications: PendingApplication[];
    draftInterviews: DraftInterview[];
    recentArticles: Article[];
    upcomingGames: Game[];
    recentUsers: Array<{ id: number; name: string; email: string; created_at: string }>;
    registrationOpen: boolean;
}

const CATEGORY_LABELS: Record<string, string> = {
    junior: 'Junior',
    feminine: 'Féminine',
    senior_masculine: 'Senior M.',
};

const QUICK_ACTIONS: Array<{ label: string; href: string; icon: LucideIcon; hint: string }> = [
    { label: 'Nouvel article', href: '/articles/create', icon: Newspaper, hint: 'Publier une news' },
    { label: 'Nouveau joueur', href: '/players/create', icon: Users, hint: 'Ajouter à l\'effectif' },
    { label: 'Nouveau match', href: '/games/create', icon: Calendar, hint: 'Planifier une rencontre' },
    { label: 'Nouvelle galerie', href: '/galleries/create', icon: ImageIcon, hint: 'Créer un album photo' },
];

export default function Dashboard({
    stats,
    inbox,
    pendingApplications,
    draftInterviews,
    recentArticles,
    upcomingGames,
    recentUsers,
}: DashboardProps) {
    const { props } = usePage<{ auth: { user: { name: string } }, club: ClubInfoShared }>();
    const userName = props.auth?.user?.name ?? 'Coach';
    const hasInboxItems = inbox.applicationsPending > 0 || inbox.interviewsDraft > 0;

    return (
        <AdminLayout>
            <Head title="Dashboard" />

            {/* Welcome hero */}
            <motion.div
                initial={{ opacity: 0, y: 16 }}
                animate={{ opacity: 1, y: 0 }}
                transition={{ duration: 0.5 }}
                className="relative overflow-hidden rounded-3xl border border-champagne/20 bg-card p-8"
            >
                <div className="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-crimson/20 blur-3xl" aria-hidden />
                <div className="absolute inset-0 bg-noise opacity-[0.04]" aria-hidden />

                <div className="relative flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                            {new Date().toLocaleDateString('fr-FR', {
                                weekday: 'long',
                                day: 'numeric',
                                month: 'long',
                            })}
                        </div>
                        <h1 className="mt-3 font-display text-3xl font-bold sm:text-4xl">
                            Bienvenue, <span className="italic text-champagne">{userName}</span>
                        </h1>
                        <p className="mt-2 max-w-lg text-muted-foreground">
                            Voici l'état du club en un coup d'œil. Matchs à venir, actualités récentes et
                            statistiques globales.
                        </p>
                    </div>

                    <div className="flex gap-3">
                        <Button asChild variant="champagne">
                            <Link href="/articles/create">
                                <Plus className="h-4 w-4" />
                                Nouvel article
                            </Link>
                        </Button>
                        <Button asChild variant="outline">
                            <Link href="/">
                                Voir le site
                                <ArrowRight className="h-4 w-4" />
                            </Link>
                        </Button>
                    </div>
                </div>
            </motion.div>

            {/* À traiter */}
            <section className="mt-8">
                <div className="flex flex-wrap items-end justify-between gap-3">
                    <div>
                        <div className="flex items-center gap-2 font-mono text-[11px] uppercase tracking-[0.18em] text-crimson">
                            <Inbox className="h-3 w-3" />
                            À traiter
                        </div>
                        <h2 className="mt-2 font-display text-2xl font-bold">
                            {hasInboxItems ? (
                                <>
                                    {inbox.applicationsPending + inbox.interviewsDraft} élément
                                    {inbox.applicationsPending + inbox.interviewsDraft > 1 ? 's' : ''}{' '}
                                    en attente
                                </>
                            ) : (
                                'Tout est propre'
                            )}
                        </h2>
                    </div>
                </div>

                <div className="mt-4 grid gap-4 lg:grid-cols-2">
                    {/* Candidatures en attente */}
                    <div className={cn(
                        'relative overflow-hidden rounded-2xl border p-5',
                        inbox.applicationsPending > 0
                            ? 'border-plasma/40 bg-plasma/5'
                            : 'border-border bg-card'
                    )}>
                        <div className="flex items-start justify-between gap-3">
                            <div className="flex items-center gap-3">
                                <div className={cn(
                                    'rounded-lg border p-2.5',
                                    inbox.applicationsPending > 0
                                        ? 'border-plasma/40 bg-plasma/10 text-plasma'
                                        : 'border-border bg-background text-muted-foreground'
                                )}>
                                    <Inbox className="h-4 w-4" />
                                </div>
                                <div>
                                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        Candidatures joueurs
                                    </div>
                                    <div className="mt-0.5 font-display text-xl font-bold">
                                        {inbox.applicationsPending} en attente
                                    </div>
                                </div>
                            </div>
                            <Button asChild size="sm" variant={inbox.applicationsPending > 0 ? 'default' : 'outline'}>
                                <Link href={route('admin.applications.index', { status: 'pending' })}>
                                    Traiter
                                    <ArrowRight className="h-3.5 w-3.5" />
                                </Link>
                            </Button>
                        </div>

                        {pendingApplications.length > 0 ? (
                            <ul className="mt-4 divide-y divide-border">
                                {pendingApplications.map((app) => {
                                    const d = formatMatchDate(app.created_at);
                                    return (
                                        <li key={app.id}>
                                            <Link
                                                href={route('admin.applications.show', app.id)}
                                                className="group flex items-center gap-3 py-2.5 transition-colors hover:text-crimson"
                                            >
                                                <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-full bg-plasma/15 font-mono text-xs font-bold text-plasma">
                                                    {app.first_name.charAt(0)}
                                                    {app.last_name.charAt(0)}
                                                </div>
                                                <div className="min-w-0 flex-1">
                                                    <div className="truncate text-sm font-semibold">
                                                        {app.first_name} {app.last_name}
                                                    </div>
                                                    <div className="truncate font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                                        {CATEGORY_LABELS[app.category] ?? app.category} · reçue {d.day} {d.month}
                                                    </div>
                                                </div>
                                                <ArrowRight className="h-3.5 w-3.5 shrink-0 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100" />
                                            </Link>
                                        </li>
                                    );
                                })}
                            </ul>
                        ) : (
                            <div className="mt-4 flex items-center gap-2 rounded-lg border border-dashed border-border bg-background/40 px-3 py-4 text-xs text-muted-foreground">
                                <CheckCircle2 className="h-4 w-4 text-mint" />
                                Aucune candidature à examiner pour le moment.
                            </div>
                        )}
                    </div>

                    {/* Interviews en brouillon */}
                    <div className={cn(
                        'relative overflow-hidden rounded-2xl border p-5',
                        inbox.interviewsDraft > 0
                            ? 'border-amber/40 bg-amber/5'
                            : 'border-border bg-card'
                    )}>
                        <div className="flex items-start justify-between gap-3">
                            <div className="flex items-center gap-3">
                                <div className={cn(
                                    'rounded-lg border p-2.5',
                                    inbox.interviewsDraft > 0
                                        ? 'border-amber/40 bg-amber/10 text-amber'
                                        : 'border-border bg-background text-muted-foreground'
                                )}>
                                    <Mic className="h-4 w-4" />
                                </div>
                                <div>
                                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        Interviews en brouillon
                                    </div>
                                    <div className="mt-0.5 font-display text-xl font-bold">
                                        {inbox.interviewsDraft} à publier
                                    </div>
                                </div>
                            </div>
                            <Button asChild size="sm" variant={inbox.interviewsDraft > 0 ? 'champagne' : 'outline'}>
                                <Link href={route('admin.interviews.index')}>
                                    Ouvrir
                                    <ArrowRight className="h-3.5 w-3.5" />
                                </Link>
                            </Button>
                        </div>

                        {draftInterviews.length > 0 ? (
                            <ul className="mt-4 divide-y divide-border">
                                {draftInterviews.map((draft) => {
                                    const d = formatMatchDate(draft.updated_at);
                                    return (
                                        <li key={draft.id}>
                                            <Link
                                                href={route('admin.interviews.edit', draft.id)}
                                                className="group flex items-center gap-3 py-2.5 transition-colors hover:text-champagne"
                                            >
                                                <div className="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg bg-amber/15 text-amber">
                                                    <Mic className="h-3.5 w-3.5" />
                                                </div>
                                                <div className="min-w-0 flex-1">
                                                    <div className="line-clamp-1 text-sm font-semibold">
                                                        {draft.title}
                                                    </div>
                                                    <div className="truncate font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                                        {draft.interviewee_name} · maj {d.day} {d.month}
                                                    </div>
                                                </div>
                                                <ArrowRight className="h-3.5 w-3.5 shrink-0 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100" />
                                            </Link>
                                        </li>
                                    );
                                })}
                            </ul>
                        ) : (
                            <div className="mt-4 flex items-center gap-2 rounded-lg border border-dashed border-border bg-background/40 px-3 py-4 text-xs text-muted-foreground">
                                <CheckCircle2 className="h-4 w-4 text-mint" />
                                Aucun brouillon en attente.
                            </div>
                        )}
                    </div>
                </div>
            </section>

            {/* Stats grid */}
            <section className="mt-8">
                <div className="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                    <StatCard
                        icon={Users}
                        label="Joueurs"
                        value={stats.players}
                        detail={`+ ${stats.playersU21} U21`}
                        href="/players"
                        tone="crimson"
                    />
                    <StatCard
                        icon={Calendar}
                        label="Matchs à venir"
                        value={stats.upcomingGames}
                        detail={`${stats.pastGames} joués`}
                        href="/calendar"
                        tone="champagne"
                    />
                    <StatCard
                        icon={Newspaper}
                        label="Articles"
                        value={stats.articles}
                        detail="Actualités publiées"
                        href="/articles"
                        tone="mint"
                    />
                    <StatCard
                        icon={ImageIcon}
                        label="Médias"
                        value={stats.photos + stats.videos}
                        detail={`${stats.photos} photos · ${stats.videos} vidéos`}
                        href="/galleries"
                        tone="plasma"
                    />
                </div>
            </section>

            {/* Quick actions */}
            <section className="mt-10">
                <SectionTitle icon={LayoutDashboard} kicker="Raccourcis" title="Actions rapides" />
                <div className="mt-4 grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    {QUICK_ACTIONS.map((action) => (
                        <Link
                            key={action.href}
                            href={action.href}
                            className="group flex items-center gap-4 rounded-2xl border border-border bg-card p-4 transition-all hover:border-crimson/50 "
                        >
                            <div className="rounded-lg border border-border bg-background p-2.5 transition-colors group-hover:border-crimson/50">
                                <action.icon className="h-4 w-4 text-crimson" />
                            </div>
                            <div className="min-w-0 flex-1">
                                <div className="font-display text-sm font-semibold transition-colors group-hover:text-crimson">
                                    {action.label}
                                </div>
                                <div className="truncate text-xs text-muted-foreground">
                                    {action.hint}
                                </div>
                            </div>
                            <Plus className="h-4 w-4 text-muted-foreground transition-transform group-hover:translate-x-0 group-hover:rotate-90 group-hover:text-crimson" />
                        </Link>
                    ))}
                </div>
            </section>

            <div className="mt-10 grid gap-8 lg:grid-cols-[1.4fr_1fr]">
                {/* Upcoming matches */}
                <section>
                    <SectionTitle icon={Trophy} kicker="Compétition" title="Prochains matchs" />
                    <div className="mt-4 space-y-3">
                        {upcomingGames.length === 0 ? (
                            <EmptyState
                                icon={Calendar}
                                title="Aucun match programmé"
                                description="Planifie une nouvelle rencontre pour la voir apparaître ici."
                                action={
                                    <Button asChild size="sm">
                                        <Link href="/games/create">
                                            <Plus className="h-4 w-4" />
                                            Nouveau match
                                        </Link>
                                    </Button>
                                }
                            />
                        ) : (
                            upcomingGames.map((game, i) => (
                                <UpcomingGameRow key={game.id} game={game} index={i} />
                            ))
                        )}
                    </div>
                </section>

                {/* Recent articles */}
                <section>
                    <SectionTitle icon={Newspaper} kicker="Contenu" title="Articles récents" />
                    <div className="mt-4 space-y-2">
                        {recentArticles.length === 0 ? (
                            <EmptyState
                                icon={Newspaper}
                                title="Aucun article"
                                description="Publie ta première actualité."
                            />
                        ) : (
                            recentArticles.map((a) => <RecentArticleRow key={a.id} article={a} />)
                        )}
                    </div>
                </section>
            </div>

            {/* Secondary stats + users */}
            <section className="mt-10 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <MiniStat icon={UserCog} label="Staff" value={stats.staff} href="/staff" />
                <MiniStat icon={UserCog} label="Coachs" value={stats.coaches} href="/coaches" />
                <MiniStat icon={Ticket} label="Tribunes" value={stats.tribunes} href="/tribunes" />
                <MiniStat icon={Users} label="Utilisateurs" value={stats.users} href="/dashboard" />
            </section>

            {/* Recent users */}
            {recentUsers.length > 0 && (
                <section className="mt-10">
                    <SectionTitle icon={Users} kicker="Communauté" title="Derniers inscrits" />
                    <div className="mt-4 overflow-hidden rounded-2xl border border-border bg-card">
                        <table className="w-full">
                            <thead>
                                <tr className="border-b border-border bg-muted/50 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                    <th className="px-5 py-3 text-left">Nom</th>
                                    <th className="px-5 py-3 text-left">Email</th>
                                    <th className="px-5 py-3 text-right">Inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                {recentUsers.map((u) => (
                                    <tr key={u.id} className="border-b border-border last:border-0 hover:bg-muted/30">
                                        <td className="px-5 py-3">
                                            <div className="flex items-center gap-3">
                                                <div className="flex h-8 w-8 items-center justify-center rounded-full bg-crimson text-xs font-semibold text-crimson-foreground">
                                                    {u.name.charAt(0).toUpperCase()}
                                                </div>
                                                <span className="text-sm font-medium">{u.name}</span>
                                            </div>
                                        </td>
                                        <td className="px-5 py-3 text-sm text-muted-foreground">{u.email}</td>
                                        <td className="px-5 py-3 text-right font-mono text-xs text-muted-foreground">
                                            {formatMatchDate(u.created_at).day} {formatMatchDate(u.created_at).month} {formatMatchDate(u.created_at).year}
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </section>
            )}
        </AdminLayout>
    );
}

function StatCard({
    icon: Icon,
    label,
    value,
    detail,
    href,
    tone,
}: {
    icon: LucideIcon;
    label: string;
    value: number;
    detail: string;
    href: string;
    tone: 'crimson' | 'champagne' | 'mint' | 'plasma';
}) {
    const toneClasses = {
        crimson: 'text-crimson border-crimson/30 bg-crimson/5',
        champagne: 'text-champagne border-champagne/30 bg-champagne/5',
        mint: 'text-mint border-mint/30 bg-mint/5',
        plasma: 'text-plasma border-plasma/30 bg-plasma/5',
    }[tone];

    return (
        <Link
            href={href}
            className="group relative overflow-hidden rounded-2xl border border-border bg-card p-5 transition-all hover:border-crimson/40 "
        >
            <div className={`inline-flex items-center gap-1.5 rounded-full border px-2.5 py-0.5 text-[10px] font-semibold uppercase tracking-wider ${toneClasses}`}>
                <Icon className="h-3 w-3" />
                {label}
            </div>
            <div className="mt-4 font-display text-4xl font-bold tabular-nums">{value}</div>
            <div className="mt-1 text-xs text-muted-foreground">{detail}</div>
            <ArrowRight className="absolute right-4 top-4 h-4 w-4 -translate-x-2 opacity-0 transition-all group-hover:translate-x-0 group-hover:opacity-100" />
        </Link>
    );
}

function MiniStat({
    icon: Icon,
    label,
    value,
    href,
}: {
    icon: LucideIcon;
    label: string;
    value: number;
    href: string;
}) {
    return (
        <Link
            href={href}
            className="group flex items-center gap-4 rounded-xl border border-border bg-card p-4 transition-colors hover:border-crimson/40"
        >
            <div className="rounded-lg border border-border bg-background p-2">
                <Icon className="h-4 w-4 text-champagne" />
            </div>
            <div className="min-w-0 flex-1">
                <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    {label}
                </div>
                <div className="mt-0.5 font-display text-xl font-bold tabular-nums">{value}</div>
            </div>
        </Link>
    );
}

function SectionTitle({
    icon: Icon,
    kicker,
    title,
}: {
    icon: LucideIcon;
    kicker: string;
    title: string;
}) {
    return (
        <div>
            <div className="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                <Icon className="h-3 w-3" />
                {kicker}
            </div>
            <h2 className="mt-2 font-display text-xl font-bold">{title}</h2>
        </div>
    );
}

function UpcomingGameRow({ game, index }: { game: Game; index: number }) {
    const date = formatMatchDate(game.match_date);
    return (
        <motion.div
            initial={{ opacity: 0, y: 10 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: index * 0.05 }}
            className="group flex items-center gap-4 rounded-xl border border-border bg-card p-4 transition-all hover:border-crimson/40"
        >
            <div className="flex flex-col items-center justify-center rounded-lg border border-border bg-background px-3 py-2">
                <div className="font-display text-2xl font-bold leading-none">{date.day}</div>
                <div className="font-mono text-[10px] uppercase tracking-widest text-champagne">
                    {date.month}
                </div>
            </div>

            <div className="flex flex-1 items-center gap-3">
                <TeamBadge team={game.homeTeam} size="sm" />
                <div className="min-w-0 flex-1">
                    <div className="truncate text-sm font-semibold">
                        {game.homeTeam?.name} <span className="text-muted-foreground">vs</span>{' '}
                        {game.awayTeam?.name}
                    </div>
                    <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                        {date.weekday} · {date.time}
                    </div>
                </div>
                <TeamBadge team={game.awayTeam} size="sm" />
            </div>

            <Link
                href={`/games/${game.id}/edit`}
                className="rounded-lg border border-border bg-background p-2 text-muted-foreground transition-colors group-hover:border-crimson/50 group-hover:text-crimson"
            >
                <ArrowRight className="h-4 w-4" />
            </Link>
        </motion.div>
    );
}

function RecentArticleRow({ article }: { article: Article }) {
    const date = formatMatchDate(article.created_at);
    return (
        <Link
            href={`/articles/${article.slug}`}
            className="group flex items-center gap-3 rounded-xl border border-border bg-card p-3 transition-all hover:border-crimson/40"
        >
            <div className="h-12 w-12 shrink-0 overflow-hidden rounded-lg bg-muted">
                {article.image ? (
                    <img
                        src={`/storage/${article.image}`}
                        alt=""
                        loading="lazy"
                        className="h-full w-full object-cover"
                    />
                ) : (
                    <div className="flex h-full w-full items-center justify-center">
                        <Newspaper className="h-5 w-5 text-muted-foreground/40" />
                    </div>
                )}
            </div>
            <div className="min-w-0 flex-1">
                <div className="line-clamp-1 text-sm font-semibold transition-colors group-hover:text-crimson">
                    {article.title}
                </div>
                <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    {date.day} {date.month} {date.year}
                </div>
            </div>
        </Link>
    );
}
