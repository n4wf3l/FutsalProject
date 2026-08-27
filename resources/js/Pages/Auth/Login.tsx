import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { CheckCircle2, Loader2, LogIn, Mail, Lock } from 'lucide-react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import { Checkbox } from '@/Components/ui/Checkbox';

interface Props {
    status?: string;
    canResetPassword: boolean;
}

export default function Login({ status, canResetPassword }: Props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <AuthLayout
            title="Connexion"
            subtitle="Accède à ton espace membre pour gérer ton profil et suivre le club."
            footer={
                <p>
                    Pas encore de compte ?{' '}
                    <Link href={route('register')} className="font-semibold text-crimson hover:underline">
                        Crée-en un
                    </Link>
                </p>
            }
        >
            <Head title="Connexion" />

            {status && (
                <div className="mb-6 flex items-center gap-2 rounded-lg border border-mint/30 bg-mint/10 px-4 py-3 text-sm text-mint">
                    <CheckCircle2 className="h-4 w-4" />
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                <Field label="Email" required error={errors.email}>
                    <div className="relative">
                        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="email"
                            name="email"
                            value={data.email}
                            autoComplete="username"
                            autoFocus
                            required
                            placeholder="tu@example.com"
                            onChange={(e) => setData('email', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Mot de passe" required error={errors.password}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password"
                            value={data.password}
                            autoComplete="current-password"
                            required
                            placeholder="••••••••"
                            onChange={(e) => setData('password', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <div className="flex items-center justify-between">
                    <Checkbox
                        name="remember"
                        checked={data.remember}
                        onChange={(e) => setData('remember', e.target.checked)}
                        label="Se souvenir de moi"
                    />
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-muted-foreground transition-colors hover:text-crimson"
                        >
                            Mot de passe oublié ?
                        </Link>
                    )}
                </div>

                <Button type="submit" size="lg" disabled={processing} className="w-full">
                    {processing ? (
                        <Loader2 className="h-4 w-4 animate-spin" />
                    ) : (
                        <LogIn className="h-4 w-4" />
                    )}
                    Se connecter
                </Button>
            </form>
        </AuthLayout>
    );
}
