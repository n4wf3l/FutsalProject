import { useState } from 'react';
import { Link, router, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { AlertTriangle, CheckCircle2, FileText, Loader2, Mail, Phone, Trash2, User } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { SEO } from '@/Components/SEO';
import { Button } from '@/Components/ui/Button';
import { Badge } from '@/Components/ui/Badge';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import { formatMatchDate } from '@/lib/utils';

interface Props {
    application: {
        first_name: string;
        last_name: string;
        email: string;
        phone: string;
        category: string;
        status: string;
        created_at: string;
        has_cv: boolean;
        deletion_token: string;
    };
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

export default function CandidatureManage({ application }: Props) {
    const { props } = usePage<{ flash: { success?: string } }>();
    const flash = props.flash;
    const [confirmDelete, setConfirmDelete] = useState(false);
    const [deleting, setDeleting] = useState(false);
    const submitted = formatMatchDate(application.created_at);

    const deleteAccount = () => {
        setDeleting(true);
        router.delete(`/candidature/${application.deletion_token}`, {
            onFinish: () => setDeleting(false),
        });
    };

    return (
        <SiteLayout>
            <SEO
                title="Ma candidature"
                description="Consulte les données de ta candidature et exerce ton droit de suppression."
                noindex
            />

            <PageHeader
                kicker="Loi 09-08 · Espace personnel"
                title="Ma candidature"
                subtitle="Consulte les données que Dina Kenitra FC détient sur toi, ou exerce ton droit de suppression à tout moment."
                variant="editorial"
            />

            <section className="mx-auto max-w-3xl px-4 pb-16">
                {flash?.success && (
                    <motion.div
                        initial={{ opacity: 0, y: -8 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="mb-8 flex items-start gap-3 rounded-2xl border border-mint/30 bg-mint/10 p-5"
                    >
                        <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-mint" />
                        <p className="text-sm text-mint">{flash.success}</p>
                    </motion.div>
                )}

                {/* Recap card */}
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="rounded-2xl border border-border bg-card p-6 sm:p-8"
                >
                    <div className="flex flex-wrap items-start justify-between gap-4">
                        <div>
                            <div className="font-mono text-[11px] uppercase tracking-widest text-champagne">
                                Candidature reçue le {submitted.day} {submitted.month} {submitted.year}
                            </div>
                            <h2 className="mt-2 font-editorial text-3xl font-medium">
                                {application.first_name} {application.last_name}
                            </h2>
                        </div>
                        <Badge variant={STATUS_VARIANT[application.status] ?? 'muted'}>
                            {STATUS_LABELS[application.status] ?? application.status}
                        </Badge>
                    </div>

                    <div className="mt-6 grid gap-3 sm:grid-cols-2">
                        <InfoRow icon={Mail} label="Email" value={application.email} />
                        <InfoRow icon={Phone} label="Téléphone" value={application.phone} />
                        <InfoRow icon={User} label="Équipe visée" value={application.category} />
                        <InfoRow
                            icon={FileText}
                            label="CV"
                            value={application.has_cv ? 'Téléversé' : 'Aucun fichier'}
                        />
                    </div>
                </motion.div>

                {/* Rights */}
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.05 }}
                    className="mt-6 rounded-2xl border border-border bg-card p-6 sm:p-8"
                >
                    <h3 className="font-editorial text-2xl font-medium">Tes droits</h3>
                    <p className="mt-2 text-sm text-muted-foreground">
                        Conformément à la Loi 09-08 sur la protection des données personnelles
                        au Maroc, tu peux à tout moment :
                    </p>
                    <ul className="mt-4 space-y-2 text-sm text-foreground/90">
                        <li>
                            <strong>Corriger</strong> une information erronée : écris à{' '}
                            <a
                                href="mailto:contact@dinakenitrafc.ma?subject=Rectification%20de%20mes%20donn%C3%A9es"
                                className="text-crimson underline hover:no-underline"
                            >
                                contact@dinakenitrafc.ma
                            </a>
                            .
                        </li>
                        <li>
                            <strong>Retirer ton consentement</strong> et supprimer définitivement
                            ta candidature (bouton ci-dessous).
                        </li>
                        <li>
                            <strong>Consulter</strong> à tout moment cette page via ton lien
                            personnel.
                        </li>
                    </ul>
                </motion.div>

                {/* Danger zone */}
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.1 }}
                    className="mt-6 rounded-2xl border border-plasma/20 bg-card p-6 sm:p-8"
                >
                    <div className="flex items-start gap-3">
                        <AlertTriangle className="mt-0.5 h-5 w-5 shrink-0 text-plasma" />
                        <div className="flex-1">
                            <h3 className="font-editorial text-xl font-medium">
                                Supprimer ma candidature
                            </h3>
                            <p className="mt-2 text-sm text-muted-foreground">
                                Ta candidature, ton CV et toutes les informations associées
                                seront effacés définitivement de nos serveurs. Cette action
                                est irréversible.
                            </p>
                            <Button
                                variant="destructive"
                                className="mt-5"
                                onClick={() => setConfirmDelete(true)}
                            >
                                <Trash2 className="h-4 w-4" />
                                Supprimer définitivement
                            </Button>
                        </div>
                    </div>
                </motion.div>

                <div className="mt-8 text-center text-sm text-muted-foreground">
                    Retour au{' '}
                    <Link href="/" className="text-crimson underline hover:no-underline">
                        site public
                    </Link>
                    .
                </div>
            </section>

            <ConfirmDialog
                open={confirmDelete}
                title="Confirmer la suppression"
                description="Tes données (nom, contact, CV, message) seront effacées définitivement. Notre staff ne pourra plus étudier ta candidature. Confirmes-tu ?"
                confirmLabel={deleting ? 'Suppression…' : 'Oui, tout supprimer'}
                variant="destructive"
                onCancel={() => setConfirmDelete(false)}
                onConfirm={() => {
                    setConfirmDelete(false);
                    deleteAccount();
                }}
            />

            {deleting && (
                <div className="fixed inset-0 z-[60] flex items-center justify-center bg-obsidian/70 backdrop-blur-sm">
                    <div className="flex items-center gap-3 rounded-2xl border border-border bg-card px-6 py-4">
                        <Loader2 className="h-4 w-4 animate-spin text-crimson" />
                        <span className="text-sm">Suppression en cours…</span>
                    </div>
                </div>
            )}
        </SiteLayout>
    );
}

function InfoRow({
    icon: Icon,
    label,
    value,
}: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string;
}) {
    return (
        <div className="flex items-center gap-3 rounded-lg border border-border bg-background p-3">
            <div className="rounded-md border border-border bg-card p-2">
                <Icon className="h-4 w-4 text-champagne" />
            </div>
            <div>
                <div className="font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                    {label}
                </div>
                <div className="mt-0.5 text-sm font-semibold">{value}</div>
            </div>
        </div>
    );
}
