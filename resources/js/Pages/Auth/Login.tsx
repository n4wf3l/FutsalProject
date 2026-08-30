import { FormEventHandler } from 'react';
import { Head, Link, useForm, usePage } from '@inertiajs/react';
import { CheckCircle2, Copy, KeyRound, Loader2, LogIn, Mail, Lock, TriangleAlert } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import { Checkbox } from '@/Components/ui/Checkbox';

interface Props {
    status?: string;
    canResetPassword: boolean;
}

// Comptes seeder pour le dev. NE JAMAIS EXPOSER EN PROD.
// Affichés uniquement si app.env !== 'production' (voir HandleInertiaRequests).
const DEV_ACCOUNTS: Array<{ label: string; email: string; password: string }> = [
    { label: 'Abderrahmane Gayedi', email: 'a.gayedi@dkfc.ma', password: 'a.gayedi7789' },
    { label: 'Mouad Benallal', email: 'm.benallal@dkfc.ma', password: 'm.benallal6655' },
    { label: 'Karim Bouabdeli', email: 'k.bouabdeli@dkfc.ma', password: 'k.bouabdeli4472' },
];

export default function Login({ status, canResetPassword }: Props) {
    const { t } = useTranslation('auth');
    const { props } = usePage<{ app?: { env?: string } }>();
    const isDev = (props.app?.env ?? 'production') !== 'production';

    const { data, setData, post, processing, errors, reset } = useForm({
        email: '',
        password: '',
        remember: false,
    });

    const fillCreds = (email: string, password: string) => {
        setData('email', email);
        setData('password', password);
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('login'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <AuthLayout
            title={t('login.title')}
            subtitle={t('login.subtitle')}
            footer={
                <p>
                    {t('login.footer_prefix')}{' '}
                    <Link href="/rejoindre" className="font-semibold text-champagne hover:underline">
                        {t('login.footer_link')}
                    </Link>
                </p>
            }
        >
            <Head title={t('login.title')} />

            {status && (
                <div className="mb-6 flex items-center gap-2 rounded-lg border border-mint/30 bg-mint/10 px-4 py-3 text-sm text-mint">
                    <CheckCircle2 className="h-4 w-4" />
                    {status}
                </div>
            )}

            <form onSubmit={submit} className="space-y-5">
                <Field label={t('login.email')} required error={errors.email}>
                    <div className="relative">
                        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="email"
                            name="email"
                            value={data.email}
                            autoComplete="username"
                            autoFocus
                            required
                            placeholder={t('login.email_placeholder')}
                            onChange={(e) => setData('email', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label={t('login.password')} required error={errors.password}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password"
                            value={data.password}
                            autoComplete="current-password"
                            required
                            placeholder={t('login.password_placeholder')}
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
                        label={t('login.remember')}
                    />
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="text-sm text-muted-foreground transition-colors hover:text-crimson"
                        >
                            {t('login.forgot')}
                        </Link>
                    )}
                </div>

                <Button type="submit" size="lg" disabled={processing} className="w-full">
                    {processing ? (
                        <Loader2 className="h-4 w-4 animate-spin" />
                    ) : (
                        <LogIn className="h-4 w-4" />
                    )}
                    {t('login.submit')}
                </Button>
            </form>

            {isDev && (
                <aside className="mt-8 rounded-2xl border border-amber/30 bg-amber/5 p-4 text-sm">
                    <div className="flex items-center gap-2 font-mono text-[10px] font-semibold uppercase tracking-widest text-amber">
                        <TriangleAlert className="h-3.5 w-3.5" />
                        {t('login.dev.title')}
                    </div>
                    <p className="mt-2 text-xs text-muted-foreground">
                        {t('login.dev.hint')}
                        <code className="mx-1 rounded bg-muted px-1 py-0.5 text-[11px]">APP_ENV=production</code>.
                    </p>

                    <ul className="mt-3 space-y-2">
                        {DEV_ACCOUNTS.map((account) => (
                            <li key={account.email}>
                                <button
                                    type="button"
                                    onClick={() => fillCreds(account.email, account.password)}
                                    className="group flex w-full items-center gap-3 rounded-lg border border-border bg-card p-3 text-left transition-colors hover:border-amber/50"
                                >
                                    <KeyRound className="h-4 w-4 shrink-0 text-amber" />
                                    <div className="min-w-0 flex-1">
                                        <div className="font-medium text-foreground">
                                            {account.label}
                                        </div>
                                        <div className="mt-0.5 truncate font-mono text-[11px] text-muted-foreground">
                                            {account.email} · {account.password}
                                        </div>
                                    </div>
                                    <Copy className="h-3.5 w-3.5 shrink-0 text-muted-foreground opacity-0 transition-opacity group-hover:opacity-100" />
                                </button>
                            </li>
                        ))}
                    </ul>
                </aside>
            )}
        </AuthLayout>
    );
}
