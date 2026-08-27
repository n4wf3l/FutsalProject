import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Loader2, Mail, Lock, User, UserPlus } from 'lucide-react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        email: '',
        password: '',
        password_confirmation: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('register'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    return (
        <AuthLayout
            title="Créer un compte"
            subtitle="Rejoins la famille Dina Kenitra FC en quelques secondes."
            footer={
                <p>
                    Tu as déjà un compte ?{' '}
                    <Link href={route('login')} className="font-semibold text-crimson hover:underline">
                        Connecte-toi
                    </Link>
                </p>
            }
        >
            <Head title="Inscription" />

            <form onSubmit={submit} className="space-y-5">
                <Field label="Nom" required error={errors.name}>
                    <div className="relative">
                        <User className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            name="name"
                            value={data.name}
                            autoComplete="name"
                            autoFocus
                            required
                            placeholder="Youssef Amrani"
                            onChange={(e) => setData('name', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Email" required error={errors.email}>
                    <div className="relative">
                        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="email"
                            name="email"
                            value={data.email}
                            autoComplete="username"
                            required
                            placeholder="tu@example.com"
                            onChange={(e) => setData('email', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label="Mot de passe" required error={errors.password} hint="8 caractères minimum">
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password"
                            value={data.password}
                            autoComplete="new-password"
                            required
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
                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <UserPlus className="h-4 w-4" />}
                    Créer mon compte
                </Button>

                <p className="text-center text-xs text-muted-foreground">
                    En t'inscrivant, tu acceptes nos{' '}
                    <Link href="/legal" className="underline hover:text-foreground">
                        conditions d'utilisation
                    </Link>
                    .
                </p>
            </form>
        </AuthLayout>
    );
}
