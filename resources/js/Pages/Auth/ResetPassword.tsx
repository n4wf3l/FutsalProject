import { FormEventHandler } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { KeyRound, Loader2, Lock, Mail } from 'lucide-react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

interface Props {
    token: string;
    email: string;
}

export default function ResetPassword({ token, email }: Props) {
    const { data, setData, post, processing, errors, reset } = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.store'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout
            title="Nouveau mot de passe"
            subtitle="Choisis un nouveau mot de passe sécurisé pour ton compte."
        >
            <Head title="Réinitialiser le mot de passe" />

            <form onSubmit={submit} className="space-y-5">
                <Field label="Email" required error={errors.email}>
                    <div className="relative">
                        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="email"
                            name="email"
                            value={data.email}
                            autoComplete="username"
                            required
                            onChange={(e) => setData('email', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Nouveau mot de passe" required error={errors.password} hint="8 caractères minimum">
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password"
                            value={data.password}
                            autoComplete="new-password"
                            required
                            autoFocus
                            placeholder="••••••••"
                            onChange={(e) => setData('password', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Confirmer le mot de passe" required error={errors.password_confirmation}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password_confirmation"
                            value={data.password_confirmation}
                            autoComplete="new-password"
                            required
                            placeholder="••••••••"
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Button type="submit" size="lg" disabled={processing} className="w-full">
                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <KeyRound className="h-4 w-4" />}
                    Réinitialiser
                </Button>
            </form>
        </AuthLayout>
    );
}
