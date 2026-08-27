import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { ArrowLeft, CheckCircle2, Loader2, Mail, Send } from 'lucide-react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

export default function ForgotPassword({ status }: { status?: string }) {
    const { data, setData, post, processing, errors } = useForm({
        email: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.email'));
    };

    return (
        <AuthLayout
            title="Mot de passe oublié"
            subtitle="Entre ton email — on t'envoie un lien pour définir un nouveau mot de passe."
            footer={
                <Link
                    href={route('login')}
                    className="inline-flex items-center gap-1.5 text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-3.5 w-3.5" />
                    Retour à la connexion
                </Link>
            }
        >
            <Head title="Mot de passe oublié" />

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

                <Button type="submit" size="lg" disabled={processing} className="w-full">
                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Send className="h-4 w-4" />}
                    Envoyer le lien
                </Button>
            </form>
        </AuthLayout>
    );
}
