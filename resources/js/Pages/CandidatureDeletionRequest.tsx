import { FormEventHandler } from 'react';
import { Link, useForm, usePage } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { CheckCircle2, Loader2, Mail, Send } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { SEO } from '@/Components/SEO';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

export default function CandidatureDeletionRequest() {
    const { props } = usePage<{ flash: { success?: string } }>();
    const flash = props.flash;

    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post('/candidature/supprimer');
    };

    return (
        <SiteLayout>
            <SEO
                title="Supprimer ma candidature"
                description="Demande le lien de gestion pour consulter ou supprimer ta candidature à Dina Kenitra FC."
                noindex
            />

            <PageHeader
                kicker="Loi 09-08 · Droit à la suppression"
                title="Retrouver mon dossier"
                subtitle="Entre l'email utilisé lors de ta candidature. Si un dossier existe, nous te renvoyons ton lien personnel pour le consulter ou le supprimer."
                variant="editorial"
            />

            <section className="mx-auto max-w-lg px-4 pb-16">
                {flash?.success && (
                    <motion.div
                        initial={{ opacity: 0, y: -8 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="mb-6 flex items-start gap-3 rounded-2xl border border-mint/30 bg-mint/10 p-5"
                    >
                        <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-mint" />
                        <p className="text-sm text-mint">{flash.success}</p>
                    </motion.div>
                )}

                <motion.form
                    onSubmit={submit}
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="rounded-2xl border border-border bg-card p-6 sm:p-8"
                >
                    <Field label="Email utilisé lors de la candidature" required error={errors.email}>
                        <div className="relative">
                            <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input
                                type="email"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                                autoFocus
                                autoComplete="email"
                                placeholder="tu@example.com"
                                className="pl-10"
                            />
                        </div>
                    </Field>

                    <Button type="submit" size="lg" disabled={processing} className="mt-6 w-full">
                        {processing ? (
                            <Loader2 className="h-4 w-4 animate-spin" />
                        ) : (
                            <Send className="h-4 w-4" />
                        )}
                        Recevoir mon lien de gestion
                    </Button>

                    <p className="mt-4 text-center text-xs text-muted-foreground">
                        Pour ta sécurité, nous ne confirmons jamais si une adresse est ou non
                        dans notre base. Si un dossier existe, l'email arrive dans quelques
                        minutes.
                    </p>
                </motion.form>

                <div className="mt-8 space-y-3 text-center text-sm text-muted-foreground">
                    <p>
                        Tu peux aussi écrire directement à{' '}
                        <a
                            href="mailto:contact@dinakenitrafc.ma?subject=Suppression%20de%20mes%20donn%C3%A9es"
                            className="text-crimson underline hover:no-underline"
                        >
                            contact@dinakenitrafc.ma
                        </a>
                        {' '}avec l'objet « Suppression de mes données ».
                    </p>
                    <p>
                        <Link
                            href="/confidentialite"
                            className="text-champagne underline hover:no-underline"
                        >
                            Politique de confidentialité complète
                        </Link>
                    </p>
                </div>
            </section>
        </SiteLayout>
    );
}
