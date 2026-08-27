import { useRef, FormEventHandler } from 'react';
import { useForm } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { CheckCircle2, KeyRound, Loader2, Lock, Save } from 'lucide-react';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

export default function UpdatePasswordForm({ className }: { className?: string }) {
    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    const { data, setData, errors, put, reset, processing, recentlySuccessful } = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: (err) => {
                if (err.password) {
                    reset('password', 'password_confirmation');
                    passwordInput.current?.focus();
                }
                if (err.current_password) {
                    reset('current_password');
                    currentPasswordInput.current?.focus();
                }
            },
        });
    };

    return (
        <section className={className}>
            <header>
                <div className="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                    <KeyRound className="h-3 w-3" />
                    Sécurité
                </div>
                <h2 className="mt-2 font-display text-xl font-bold">Mot de passe</h2>
                <p className="mt-1 text-sm text-muted-foreground">
                    Un mot de passe long et unique garde ton compte en sécurité.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-5">
                <Field label="Mot de passe actuel" error={errors.current_password}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            ref={currentPasswordInput}
                            id="current_password"
                            type="password"
                            value={data.current_password}
                            onChange={(e) => setData('current_password', e.target.value)}
                            autoComplete="current-password"
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Nouveau mot de passe" error={errors.password}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            ref={passwordInput}
                            id="password"
                            type="password"
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            autoComplete="new-password"
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Confirmer le mot de passe" error={errors.password_confirmation}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            id="password_confirmation"
                            type="password"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            autoComplete="new-password"
                            className="pl-10"
                        />
                    </div>
                </Field>

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
