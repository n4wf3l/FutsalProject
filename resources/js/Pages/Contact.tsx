import { useForm, usePage } from '@inertiajs/react';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { CheckCircle2, Loader2, Mail, MapPin, Phone, Send } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import type { ClubInfoShared } from '@/types/models';

export default function Contact() {
    const { props } = usePage<{ club: ClubInfoShared; flash: { success?: string } }>();
    const club = props.club;
    const flash = props.flash;

    const { data, setData, post, processing, errors, reset } = useForm({
        firstname: '',
        lastname: '',
        email: '',
        phone: '',
        message: '',
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
                title="Contact"
                description="Nous joindre : email, téléphone, adresse à Kénitra. On répond sous 24-48h ouvrées."
            />

            <PageHeader
                title="Une question ?"
                subtitle="Écris-nous. On répond sous 24-48h ouvrées. Pour les urgences match, préfère le téléphone."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Contact' }]}
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
                            <Field label="Prénom" error={errors.firstname}>
                                <Input
                                    value={data.firstname}
                                    onChange={(e) => setData('firstname', e.target.value)}
                                    placeholder="Youssef"
                                    required
                                />
                            </Field>
                            <Field label="Nom" error={errors.lastname}>
                                <Input
                                    value={data.lastname}
                                    onChange={(e) => setData('lastname', e.target.value)}
                                    placeholder="Amrani"
                                    required
                                />
                            </Field>
                            <Field label="Email" error={errors.email}>
                                <Input
                                    type="email"
                                    value={data.email}
                                    onChange={(e) => setData('email', e.target.value)}
                                    placeholder="tu@example.com"
                                    required
                                />
                            </Field>
                            <Field label="Téléphone" error={errors.phone}>
                                <Input
                                    type="tel"
                                    value={data.phone}
                                    onChange={(e) => setData('phone', e.target.value)}
                                    placeholder="+212 6 00 00 00 00"
                                    required
                                />
                            </Field>
                        </div>

                        <Field label="Message" error={errors.message} className="mt-5">
                            <Textarea
                                value={data.message}
                                onChange={(e) => setData('message', e.target.value)}
                                placeholder="Explique-nous ta demande…"
                                rows={6}
                                required
                            />
                        </Field>

                        <div className="mt-6 flex items-center justify-between gap-4">
                            <p className="text-xs text-muted-foreground">
                                On protège tes données. Pas de spam, promis.
                            </p>
                            <Button type="submit" size="lg" disabled={processing}>
                                {processing ? (
                                    <Loader2 className="h-4 w-4 animate-spin" />
                                ) : (
                                    <Send className="h-4 w-4" />
                                )}
                                Envoyer
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
                            title="Adresse"
                            lines={[club?.location ?? 'Complexe Sportif Municipal', club?.city ?? 'Kénitra, Maroc']}
                        />
                        <ContactInfoCard
                            icon={Mail}
                            title="Email"
                            lines={[club?.email ?? 'contact@dinakenitrafc.ma']}
                            href={`mailto:${club?.email ?? 'contact@dinakenitrafc.ma'}`}
                        />
                        <ContactInfoCard
                            icon={Phone}
                            title="Téléphone"
                            lines={[club?.phone ?? '+212 000 000 000']}
                            href={`tel:${(club?.phone ?? '').replace(/\s+/g, '')}`}
                        />

                        {club?.latitude && club?.longitude && (
                            <div className="overflow-hidden rounded-2xl border border-border">
                                <iframe
                                    src={`https://www.openstreetmap.org/export/embed.html?bbox=${club.longitude - 0.01},${club.latitude - 0.01},${club.longitude + 0.01},${club.latitude + 0.01}&marker=${club.latitude},${club.longitude}`}
                                    className="aspect-square w-full"
                                    loading="lazy"
                                    title="Carte du club"
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
