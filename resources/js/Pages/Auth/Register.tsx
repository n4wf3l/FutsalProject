import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { Loader2, Mail, Lock, User, UserPlus } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import AuthLayout from '@/Layouts/AuthLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

export default function Register() {
    const { t } = useTranslation('auth');

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
            title={t('register.title')}
            subtitle={t('register.subtitle')}
            footer={
                <p>
                    {t('register.footer_prefix')}{' '}
                    <Link href={route('login')} className="font-semibold text-crimson hover:underline">
                        {t('register.footer_link')}
                    </Link>
                </p>
            }
        >
            <Head title={t('register.title')} />

            <form onSubmit={submit} className="space-y-5">
                <Field label={t('register.name')} required error={errors.name}>
                    <div className="relative">
                        <User className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            name="name"
                            value={data.name}
                            autoComplete="name"
                            autoFocus
                            required
                            placeholder={t('register.name_placeholder')}
                            onChange={(e) => setData('name', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label={t('register.email')} required error={errors.email}>
                    <div className="relative">
                        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="email"
                            name="email"
                            value={data.email}
                            autoComplete="username"
                            required
                            placeholder={t('register.email_placeholder')}
                            onChange={(e) => setData('email', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label={t('register.password')} required error={errors.password} hint={t('register.password_hint')}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password"
                            value={data.password}
                            autoComplete="new-password"
                            required
                            placeholder={t('register.password_placeholder')}
                            onChange={(e) => setData('password', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Field label={t('register.password_confirm')} required error={errors.password_confirmation}>
                    <div className="relative">
                        <Lock className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input
                            type="password"
                            name="password_confirmation"
                            value={data.password_confirmation}
                            autoComplete="new-password"
                            required
                            placeholder={t('register.password_placeholder')}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            className="pl-10"
                        />
                    </div>
                </Field>

                <Button type="submit" size="lg" disabled={processing} className="w-full">
                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <UserPlus className="h-4 w-4" />}
                    {t('register.submit')}
                </Button>

                <p className="text-center text-xs text-muted-foreground">
                    {t('register.terms_prefix')}{' '}
                    <Link href="/legal" className="underline hover:text-foreground">
                        {t('register.terms_link')}
                    </Link>
                    .
                </p>
            </form>
        </AuthLayout>
    );
}
