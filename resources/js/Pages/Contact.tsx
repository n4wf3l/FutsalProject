import { useForm, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { CheckCircle2, Loader2, Mail, MapPin, Phone, Send } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import type { ClubInfoShared } from '@/types/models';

export default function Contact() {
    const { t } = useTranslation('contact');
    const { props } = usePage<{ club: ClubInfoShared; flash: { success?: string } }>();
    const club = props.club;
    const flash = props.flash;

    const { data, setData, post, processing, errors, reset } = useForm({
        firstname: '',
        lastname: '',
        email: '',
        phone: '',
        message: '',
        consent: false as boolean,
    });

    const submit = (e: React.FormEvent) => {
        e.preventDefault();
        post('/contact', {
            onSuccess: () => reset(),
        });
    };

    return (
        <SiteLayout>
            <SEO
                title={t('page.kicker')}
                description={t('page.subtitle')}
            />

            <PageHeader
                title={t('page.title')}
                subtitle={t('page.subtitle')}
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: t('page.kicker') }]}
            />

            <section className="mx-auto max-w-7xl px-4 pb-16">
                <div className="grid gap-8 lg:grid-cols-[1.4fr_1fr]">
                    {/* Form */}
                    <motion.form
                        onSubmit={submit}
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5 }}
                        className="glass rounded-3xl p-8"
                    >
                        {flash?.success && (
                            <motion.div
                                initial={{ opacity: 0, y: -8 }}
                                animate={{ opacity: 1, y: 0 }}
                                className="mb-6 flex items-center gap-2 rounded-lg border border-mint/30 bg-mint/10 px-4 py-3 text-sm text-mint"
                            >
                                <CheckCircle2 className="h-4 w-4" />
                                {flash.success}
                            </motion.div>
                        )}

                        <div className="grid gap-5 sm:grid-cols-2">
                            <Field label={t('form.firstname')} error={errors.firstname}>
                                <Input
                                    value={data.firstname}
                                    onChange={(e) => setData('firstname', e.target.value)}
                                    placeholder={t('form.firstname_placeholder')}
                                    required
                                />
                            </Field>
                            <Field label={t('form.lastname')} error={errors.lastname}>
                                <Input
                                    value={data.lastname}
                                    onChange={(e) => setData('lastname', e.target.value)}
                                    placeholder={t('form.lastname_placeholder')}
                                    required
                                />
                            </Field>
                            <Field label={t('form.email')} error={errors.email}>
                                <Input
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    placeholder={t('form.email_placeholder')}
                                    required
                                />
                            </Field>
                            <Field label={t('form.phone')} error={errors.phone}>
                                <Input
                                    type="tel"
                                    value={data.phone}
                                    onChange={(e) => setData('phone', e.target.value)}
                                    placeholder={t('form.phone_placeholder')}
                                    required
                                />
                            </Field>
                        </div>

                        <Field label={t('form.message')} error={errors.message} className="mt-5">
                            <Textarea
                                value={data.message}
                                onChange={(e) => setData('message', e.target.value)}
                                placeholder={t('form.message_placeholder')}
                                rows={6}
                                required
                            />
                        </Field>

                        {/* Consent (Loi 09-08) */}
                        <div className="mt-6 rounded-2xl border border-crimson/20 bg-card p-5">
                            <p className="text-sm leading-relaxed text-muted-foreground">
                                {t('consent.body_prefix')}{' '}
                                <a href={t('consent.policy_link')} className="font-semibold text-crimson hover:underline">
                                    /confidentialite
                                </a>
                                .
                            </p>

                            <label className="mt-4 flex cursor-pointer items-start gap-3 rounded-lg border border-border bg-background p-3">
                                <input
                                    type="checkbox"
                                    checked={data.consent}
                                    onChange={(e) => setData('consent', e.target.checked)}
                                    className="mt-0.5 h-4 w-4 shrink-0 rounded border-input text-crimson focus:ring-2 focus:ring-crimson/40"
                                    required
                                />
                                <span className="text-sm text-foreground">
                                    <strong>{t('consent.checkbox_bold')}</strong>{' '}
                                    {t('consent.checkbox_body')}
                                </span>
                            </label>
                            {errors.consent && (
                                <p className="mt-2 text-xs text-plasma">{errors.consent}</p>
                            )}
                        </div>

                        <div className="mt-6 flex items-center justify-between gap-4">
                            <p className="text-xs text-muted-foreground">
                                {t('form.privacy_note')}
                            </p>
                            <Button type="submit" size="lg" disabled={processing || !data.consent}>
                                {processing ? (
                                    <Loader2 className="h-4 w-4 animate-spin" />
                                ) : (
                                    <Send className="h-4 w-4" />
                                )}
                                {t('form.submit')}
                            </Button>
                        </div>
                    </motion.form>

                    {/* Info */}
                    <motion.aside
                        initial={{ opacity: 0, y: 20 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ duration: 0.5, delay: 0.1 }}
                        className="space-y-4"
                    >
                        <ContactInfoCard
                            icon={MapPin}
                            title={t('info.address')}
                            lines={[club?.location ?? 'Complexe Sportif Municipal', club?.city ?? 'Kénitra, Maroc']}
                        />
                        <ContactInfoCard
                            icon={Mail}
                            title={t('info.email')}
                            lines={[club?.email ?? 'contact@dinakenitrafc.ma']}
                            href={`mailto:${club?.email ?? 'contact@dinakenitrafc.ma'}`}
                        />
                        <ContactInfoCard
                            icon={Phone}
                            title={t('info.phone')}
                            lines={[club?.phone ?? '+212 000 000 000']}
                            href={`tel:${(club?.phone ?? '').replace(/\s+/g, '')}`}
                        />

                        {club?.latitude && club?.longitude && (
                            <div className="overflow-hidden rounded-2xl border border-border">
                                <iframe
                                    src={`https://www.openstreetmap.org/export/embed.html?bbox=${club.longitude - 0.01},${club.latitude - 0.01},${club.longitude + 0.01},${club.latitude + 0.01}&marker=${club.latitude},${club.longitude}`}
                                    className="aspect-square w-full"
                                    loading="lazy"
                                    title={t('info.map_alt')}
                                />
                            </div>
                        )}
                    </motion.aside>
                </div>
            </section>
        </SiteLayout>
    );
}

function Field({
    label,
    error,
    children,
    className,
}: {
    label: string;
    error?: string;
    children: React.ReactNode;
    className?: string;
}) {
    return (
        <div className={className}>
            <label className="mb-1.5 block font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                {label}
            </label>
            {children}
            {error && <p className="mt-1 text-xs text-plasma">{error}</p>}
        </div>
    );
}

function ContactInfoCard({
    icon: Icon,
    title,
    lines,
    href,
}: {
    icon: React.ComponentType<{ className?: string }>;
    title: string;
    lines: string[];
    href?: string;
}) {
    const Wrapper: React.ElementType = href ? 'a' : 'div';
    return (
        <Wrapper
            {...(href ? { href } : {})}
            className="group flex items-start gap-4 rounded-2xl border border-border bg-card p-5 transition-all hover:border-crimson/40"
        >
            <div className="rounded-full border border-border bg-background p-3">
                <Icon className="h-4 w-4 text-crimson" />
            </div>
            <div>
                <div className="font-mono text-[10px] uppercase tracking-widest text-champagne">
                    {title}
                </div>
                {lines.map((l, i) => (
                    <div
                        key={i}
                        className="mt-1 font-display text-sm font-semibold transition-colors group-hover:text-crimson"
                    >
                        {l}
                    </div>
                ))}
            </div>
        </Wrapper>
    );
}
