import { Link, useForm, usePage } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { CheckCircle2, Loader2, Mail, Save, User as UserIcon } from 'lucide-react';
import { FormEventHandler } from 'react';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
    className?: string;
}

export default function UpdateProfileInformation({ mustVerifyEmail, status, className }: Props) {
    const user = usePage<{
        auth: { user: { name: string; email: string; email_verified_at: string | null } };
    }>().props.auth.user;

    const { data, setData, patch, errors, processing, recentlySuccessful } = useForm({
        name: user.name,
        email: user.email,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        patch(route('profile.update'));
    };

    return (
        <section className={className}>
            <header>
                <div className="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                    <UserIcon className="h-3 w-3" />
                    Identité
                </div>
                <h2 className="mt-2 font-display text-xl font-bold">Informations du profil</h2>
                <p className="mt-1 text-sm text-muted-foreground">
                    Mets à jour ton nom et ton adresse email.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-5">
                <Field label="Nom" required error={errors.name}>
                    <div className="relative">
                        <UserIcon className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            id="name"
                            value={data.name}
                            onChange={(e) => setData('name', e.target.value)}
                            required
                            autoComplete="name"
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Email" required error={errors.email}>
                    <div className="relative">
                        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            id="email"
                            type="email"
                            value={data.email}
                            onChange={(e) => setData('email', e.target.value)}
                            required
                            autoComplete="username"
                            className="pl-10"
                        />
                    </div>
                </Field>

                {mustVerifyEmail && user.email_verified_at === null && (
                    <div className="rounded-lg border border-amber/30 bg-amber/10 px-4 py-3 text-sm">
                        <p className="text-amber">
                            Ton email n'est pas vérifié.{' '}
                            <Link
                                href={route('verification.send')}
                                method="post"
                                as="button"
                                className="font-semibold underline hover:no-underline"
                            >
                                Renvoyer le lien de vérification
                            </Link>
                        </p>
                        {status === 'verification-link-sent' && (
                            <p className="mt-2 text-mint">Un nouveau lien vient d'être envoyé.</p>
                        )}
                    </div>
                )}

                <div className="flex items-center gap-4 pt-2">
                    <Button type="submit" disabled={processing}>
                        {processing ? (
                            <Loader2 className="h-4 w-4 animate-spin" />
                        ) : (
                            <Save className="h-4 w-4" />
                        )}
                        Enregistrer
                    </Button>

                    <AnimatePresence>
                        {recentlySuccessful && (
                            <motion.span
                                initial={{ opacity: 0, x: -8 }}
                                animate={{ opacity: 1, x: 0 }}
                                exit={{ opacity: 0 }}
                                className="inline-flex items-center gap-1.5 text-sm text-mint"
                            >
                                <CheckCircle2 className="h-4 w-4" />
                                Enregistré
                            </motion.span>
                        )}
                    </AnimatePresence>
                </div>
            </form>
        </section>
    );
}
