import { FormEventHandler } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { Loader2, Lock, ShieldCheck } from 'lucide-react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('password.confirm'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <AuthLayout
            title="Confirmation requise"
            subtitle="Zone sensible — confirme ton mot de passe pour continuer."
        >
            <Head title="Confirmer le mot de passe" />

            <div className="mb-6 flex items-center gap-3 rounded-2xl border border-champagne/20 bg-card p-4 text-sm">
                <div className="rounded-full border border-border bg-background p-2">
                    <ShieldCheck className="h-4 w-4 text-champagne" />
                </div>
                <p className="text-muted-foreground">
                    Ta session est déjà ouverte — on te redemande le mot de passe pour cette action sensible.
                </p>
            </div>

            <form onSubmit={submit} className="space-y-5">
                <Field label="Mot de passe" required error={errors.password}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password"
                            value={data.password}
                            autoComplete="current-password"
                            required
                            autoFocus
                            placeholder="••••••••"
                            onChange={(e) => setData('password', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Button type="submit" size="lg" disabled={processing} className="w-full">
                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <ShieldCheck className="h-4 w-4" />}
                    Confirmer
                </Button>
            </form>
        </AuthLayout>
    );
}
