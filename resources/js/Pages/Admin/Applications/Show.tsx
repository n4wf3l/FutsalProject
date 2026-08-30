import { useState } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import {
    ArrowLeft,
    Calendar,
    CheckCircle2,
    FileText,
    Loader2,
    Mail,
    MapPin,
    Phone,
    Save,
    User,
    XCircle,
} from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Textarea } from '@/Components/ui/Input';
import { Badge } from '@/Components/ui/Badge';
import { Field } from '@/Components/ui/Field';
import { formatMatchDate, cn } from '@/lib/utils';
import type { PlayerApplication } from '@/types/models';

interface Props {
    application: PlayerApplication;
    statuses: string[];
    categories: Record<string, string>;
}

const STATUS_LABELS: Record<string, string> = {
    pending: 'En attente',
    reviewed: 'Étudiée',
    contacted: 'Contactée',
    accepted: 'Acceptée',
    rejected: 'Refusée',
};

const STATUS_VARIANT: Record<string, 'soon' | 'muted' | 'default' | 'win' | 'live'> = {
    pending: 'soon',
    reviewed: 'muted',
    contacted: 'default',
    accepted: 'win',
    rejected: 'live',
};

export default function AdminApplicationShow({ application, statuses, categories }: Props) {
    const [saved, setSaved] = useState(false);
    const { data, setData, patch, processing } = useForm({
        status: application.status,
        admin_notes: application.admin_notes ?? '',
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        patch(route('admin.applications.updateStatus', application.id), {
            preserveScroll: true,
            onSuccess: () => {
                setSaved(true);
                setTimeout(() => setSaved(false), 2000);
            },
        });
    };

    const quickStatus = (status: string) => {
        setData('status', status);
        router.patch(
            route('admin.applications.updateStatus', application.id),
            { status, admin_notes: data.admin_notes || null },
            { preserveScroll: true }
        );
    };

    const age = new Date().getFullYear() - new Date(application.birthdate).getFullYear();
    const submitted = formatMatchDate(application.created_at);

    return (
        <AdminLayout>
            <Head title={`${application.first_name} ${application.last_name}`} />

            <div className="mb-6">
                <Link
                    href={route('admin.applications.index')}
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Toutes les candidatures
                </Link>

                <div className="mt-4 flex flex-wrap items-center justify-between gap-4">
                    <div>
                        <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                            Candidature #{application.id}
                        </div>
                        <h1 className="mt-1 font-display text-3xl font-bold">
                            {application.first_name} {application.last_name}
                        </h1>
                        <div className="mt-2 flex flex-wrap items-center gap-2">
                            <Badge variant="champagne">
                                {categories[application.category] ?? application.category}
                            </Badge>
                            <Badge variant={STATUS_VARIANT[application.status] ?? 'muted'}>
                                {STATUS_LABELS[application.status] ?? application.status}
                            </Badge>
                            <span className="font-mono text-xs text-muted-foreground">
                                Reçue le {submitted.day} {submitted.month} {submitted.year}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div className="grid gap-6 lg:grid-cols-[1fr_340px]">
                {/* Main */}
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="space-y-6"
                >
                    {/* Contact */}
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Coordonnées" />
                        <div className="mt-4 grid gap-4 sm:grid-cols-2">
                            <ContactRow
                                icon={Mail}
                                label="Email"
                                value={application.email}
                                href={`mailto:${application.email}`}
                            />
                            <ContactRow
                                icon={Phone}
                                label="Téléphone"
                                value={application.phone}
                                href={`tel:${application.phone.replace(/\s+/g, '')}`}
                            />
                            <ContactRow icon={Calendar} label="Âge" value={`${age} ans`} />
                            {application.city && (
                                <ContactRow icon={MapPin} label="Ville" value={application.city} />
                            )}
                            {application.nationality && (
                                <ContactRow icon={User} label="Nationalité" value={application.nationality} />
                            )}
                        </div>
                    </div>

                    {/* Sport profile */}
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Profil sportif" />
                        <div className="mt-4 grid gap-4 sm:grid-cols-3">
                            <InfoBlock
                                label="Poste préféré"
                                value={application.position_preference ?? '—'}
                            />
                            <InfoBlock
                                label="Expérience"
                                value={
                                    application.experience_years !== null
                                        ? `${application.experience_years} an(s)`
                                        : '—'
                                }
                            />
                            <InfoBlock
                                label="Club actuel"
                                value={application.current_club ?? '—'}
                            />
                        </div>
                    </div>

                    {/* Message */}
                    {application.message && (
                        <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                            <SectionTitle title="Message du candidat" />
                            <p className="mt-4 whitespace-pre-line leading-relaxed text-foreground/90">
                                {application.message}
                            </p>
                        </div>
                    )}

                    {/* Admin notes */}
                    <form
                        onSubmit={submit}
                        className="rounded-2xl border border-crimson/20 bg-card p-6 sm:p-8"
                    >
                        <SectionTitle title="Notes internes" />
                        <p className="mt-1 text-xs text-muted-foreground">
                            Visibles uniquement par le staff. Utilise-le pour tracer les échanges avec le candidat.
                        </p>
                        <div className="mt-4">
                            <Field label="Notes">
                                <Textarea
                                    value={data.admin_notes}
                                    onChange={(e) => setData('admin_notes', e.target.value)}
                                    rows={5}
                                    placeholder="Retour d'essai, décision, prochains contacts…"
                                />
                            </Field>
                        </div>
                        <div className="mt-4 flex items-center gap-3">
                            <Button type="submit" disabled={processing}>
                                {processing ? (
                                    <Loader2 className="h-4 w-4 animate-spin" />
                                ) : (
                                    <Save className="h-4 w-4" />
                                )}
                                Enregistrer les notes
                            </Button>
                            {saved && (
                                <span className="inline-flex items-center gap-1.5 text-sm text-mint">
                                    <CheckCircle2 className="h-4 w-4" />
                                    Enregistré
                                </span>
                            )}
                        </div>
                    </form>
                </motion.div>

                {/* Sidebar */}
                <motion.aside
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.05 }}
                    className="space-y-6"
                >
                    {/* Status actions */}
                    <div className="rounded-2xl border border-champagne/20 bg-card p-6">
                        <SectionTitle title="Décision" />
                        <div className="mt-4 space-y-2">
                            {statuses.map((s) => {
                                const isCurrent = application.status === s;
                                return (
                                    <button
                                        key={s}
                                        onClick={() => quickStatus(s)}
                                        disabled={isCurrent}
                                        className={cn(
                                            'flex w-full items-center justify-between rounded-lg border px-3 py-2 text-sm font-medium transition-all disabled:opacity-70',
                                            isCurrent
                                                ? 'border-crimson bg-crimson/10 text-crimson'
                                                : 'border-border bg-background hover:border-crimson/40'
                                        )}
                                    >
                                        <span>{STATUS_LABELS[s] ?? s}</span>
                                        {isCurrent && <CheckCircle2 className="h-4 w-4" />}
                                    </button>
                                );
                            })}
                        </div>
                        {application.reviewer && application.reviewed_at && (
                            <div className="mt-4 border-t border-border pt-4 text-xs text-muted-foreground">
                                Dernière révision par <span className="font-semibold text-foreground">{application.reviewer.name}</span>
                                {' · '}
                                {formatMatchDate(application.reviewed_at).day} {formatMatchDate(application.reviewed_at).month}
                            </div>
                        )}
                    </div>

                    {/* CV */}
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <SectionTitle title="CV / documents" />
                        {application.cv_path ? (
                            <a
                                href={`/admin/applications/${application.id}/cv`}
                                target="_blank"
                                rel="noopener noreferrer"
                                className="mt-4 flex items-center gap-3 rounded-xl border border-border bg-background p-4 transition-colors hover:border-champagne/40"
                            >
                                <div className="rounded-lg border border-border bg-card p-2">
                                    <FileText className="h-4 w-4 text-champagne" />
                                </div>
                                <div className="min-w-0 flex-1">
                                    <div className="text-sm font-semibold">Ouvrir le CV</div>
                                    <div className="truncate font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                        {application.cv_path.split('/').pop()}
                                    </div>
                                </div>
                            </a>
                        ) : (
                            <div className="mt-4 flex items-center gap-2 rounded-lg border border-dashed border-border bg-background/40 p-4 text-xs text-muted-foreground">
                                <XCircle className="h-4 w-4" />
                                Aucun CV fourni
                            </div>
                        )}
                    </div>

                    {/* Quick contact */}
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <SectionTitle title="Répondre" />
                        <a
                            href={`mailto:${application.email}?subject=${encodeURIComponent(`Ta candidature à Dina Kenitra FC (${application.first_name} ${application.last_name})`)}`}
                            className="mt-4 flex items-center justify-center gap-2 rounded-lg border border-crimson bg-crimson px-4 py-2 text-sm font-semibold text-crimson-foreground transition-all hover:brightness-110"
                        >
                            <Mail className="h-4 w-4" />
                            Envoyer un email
                        </a>
                        <a
                            href={`tel:${application.phone.replace(/\s+/g, '')}`}
                            className="mt-2 flex items-center justify-center gap-2 rounded-lg border border-border px-4 py-2 text-sm font-medium text-foreground transition-colors hover:border-crimson/50 hover:text-crimson"
                        >
                            <Phone className="h-4 w-4" />
                            Appeler
                        </a>
                    </div>
                </motion.aside>
            </div>
        </AdminLayout>
    );
}

function SectionTitle({ title }: { title: string }) {
    return (
        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
            {title}
        </div>
    );
}

function ContactRow({
    icon: Icon,
    label,
    value,
    href,
}: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string;
    href?: string;
}) {
    const inner = (
        <>
            <div className="rounded-lg border border-border bg-background p-2">
                <Icon className="h-4 w-4 text-champagne" />
            </div>
            <div className="min-w-0">
                <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    {label}
                </div>
                <div className="mt-0.5 truncate font-display text-sm font-semibold">{value}</div>
            </div>
        </>
    );

    if (href) {
        return (
            <a
                href={href}
                className="flex items-center gap-3 rounded-lg border border-border bg-background p-3 transition-colors hover:border-crimson/40"
            >
                {inner}
            </a>
        );
    }
    return <div className="flex items-center gap-3 rounded-lg border border-border bg-background p-3">{inner}</div>;
}

function InfoBlock({ label, value }: { label: string; value: string }) {
    return (
        <div className="rounded-lg border border-border bg-background p-3">
            <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                {label}
            </div>
            <div className="mt-1 font-display text-base font-semibold">{value}</div>
        </div>
    );
}
