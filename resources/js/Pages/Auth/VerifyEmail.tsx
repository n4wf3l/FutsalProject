import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { CheckCircle2, LogOut, Mail, RefreshCw } from 'lucide-react';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';

export default function VerifyEmail({ status }: { status?: string }) {
    const { post, processing } = useForm({});

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('verification.send'));
    };

    return (
        <AuthLayout
            title="Vérifie ton email"
            subtitle="On t'a envoyé un lien pour confirmer ton adresse. Clique dessus pour activer ton compte."
        >
            <Head title="Vérification email" />

            <div className="mb-6 flex items-center gap-3 rounded-2xl border border-champagne/30 bg-champagne/10 p-4 text-sm">
                <div className="rounded-full border border-champagne/30 bg-background p-2">
                    <Mail className="h-4 w-4 text-champagne" />
                </div>
                <div>
                    <div className="font-semibold text-champagne">Email envoyé</div>
                    <p className="text-xs text-muted-foreground">
                        Vérifie ta boîte de réception (et les spams).
                    </p>
                </div>
            </div>

            {status === 'verification-link-sent' && (
                <div className="mb-6 flex items-center gap-2 rounded-lg border border-mint/30 bg-mint/10 px-4 py-3 text-sm text-mint">
                    <CheckCircle2 className="h-4 w-4" />
                    Un nouveau lien de vérification a été envoyé.
                </div>
            )}

            <form onSubmit={submit} className="space-y-3">
                <Button type="submit" size="lg" disabled={processing} className="w-full">
                    <RefreshCw className="h-4 w-4" />
                    Renvoyer l'email
                </Button>

                <Link
                    href={route('logout')}
                    method="post"
                    as="button"
                    className="flex w-full items-center justify-center gap-2 rounded-lg py-2 text-sm text-muted-foreground transition-colors hover:text-foreground"
                >
                    <LogOut className="h-3.5 w-3.5" />
                    Se déconnecter
                </Link>
            </form>
        </AuthLayout>
    );
}
