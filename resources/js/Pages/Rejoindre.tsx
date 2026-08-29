import { FormEventHandler, useState } from 'react';
import { useForm, usePage } from '@inertiajs/react';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import {
    CheckCircle2,
    FileText,
    Loader2,
    Mail,
    MapPin,
    Phone,
    Send,
    Sparkles,
    Trash2,
    Upload,
    User,
    Users2,
    Trophy,
} from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import { cn } from '@/lib/utils';

interface Props {
    categories: Record<string, string>;
    positions: string[];
}

const CATEGORY_META: Record<string, { icon: React.ComponentType<{ className?: string }>; hint: string }> = {
    junior: { icon: Users2, hint: 'U15 · U17 · U21' },
    feminine: { icon: Sparkles, hint: 'Équipe féminine' },
    senior_masculine: { icon: Trophy, hint: 'Équipe première' },
};

export default function Rejoindre({ categories, positions }: Props) {
    const { props } = usePage<{ flash: { success?: string } }>();
    const flash = props.flash;

    const [cvName, setCvName] = useState<string | null>(null);

    const { data, setData, post, processing, errors, progress, reset } = useForm({
        first_name: '',
        last_name: '',
        email: '',
        phone: '',
        birthdate: '',
        nationality: '',
        city: '',
        category: '',
        position_preference: '',
        current_club: '',
        experience_years: '',
        message: '',
        cv: null as File | null,
        consent: false as boolean,
        parental_consent: false as boolean,
    });

    // Detect if the candidate declared as minor (based on birthdate)
    const isMinor = (() => {
        if (!data.birthdate) return false;
        const b = new Date(data.birthdate);
        const now = new Date();
        let age = now.getFullYear() - b.getFullYear();
        const m = now.getMonth() - b.getMonth();
        if (m < 0 || (m === 0 && now.getDate() < b.getDate())) age--;
        return age < 18;
    })();

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(route('rejoindre.store'), {
            forceFormData: true,
            onSuccess: () => {
                reset();
                setCvName(null);
            },
        });
    };

    const onCvChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('cv', file);
        setCvName(file?.name ?? null);
    };

    return (
        <SiteLayout>
            <SEO
                title="Rejoindre le club"
                description="Postule pour rejoindre Dina Kenitra FC. Détections ouvertes pour les équipes junior, féminine et senior masculine."
            />

            <PageHeader
                title="Rejoindre le club"
                subtitle="Envoie ta candidature pour les équipes junior, féminine ou senior masculine. Notre staff étudie chaque dossier et te recontacte pour un essai."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Rejoindre le club' }]}
            />

            <section className="mx-auto max-w-6xl px-4 pb-16">
                {flash?.success && (
                    <motion.div
                        initial={{ opacity: 0, y: -8 }}
                        animate={{ opacity: 1, y: 0 }}
                        className="mb-8 flex items-start gap-3 rounded-2xl border border-mint/30 bg-mint/10 p-5"
                    >
                        <div className="rounded-full border border-mint/30 bg-background p-2">
                            <CheckCircle2 className="h-4 w-4 text-mint" />
                        </div>
                        <div>
                            <div className="font-display font-semibold text-mint">
                                Candidature envoyée !
                            </div>
                            <p className="mt-1 text-sm text-muted-foreground">{flash.success}</p>
                        </div>
                    </motion.div>
                )}

                <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[1fr_320px]">
                    <div className="space-y-6">
                        {/* Category selector */}
                        <motion.div
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            className="rounded-2xl border border-border bg-card p-6 sm:p-8"
                        >
                            <SectionTitle kicker="Étape 1" title="Pour quelle équipe postules-tu ?" />
                            <div className="mt-4 grid gap-3 sm:grid-cols-3">
                                {Object.entries(categories).map(([key, label]) => {
                                    const meta = CATEGORY_META[key];
                                    const Icon = meta?.icon ?? Users2;
                                    const selected = data.category === key;
                                    return (
                                        <button
                                            key={key}
                                            type="button"
                                            onClick={() => setData('category', key)}
                                            className={cn(
                                                'group flex flex-col items-start gap-2 rounded-xl border p-4 text-left transition-all',
                                                selected
                                                    ? 'border-crimson bg-crimson/5 shadow-glow-crimson'
                                                    : 'border-border bg-background hover:border-crimson/40'
                                            )}
                                        >
                                            <div
                                                className={cn(
                                                    'inline-flex h-9 w-9 items-center justify-center rounded-lg border transition-colors',
                                                    selected
                                                        ? 'border-crimson bg-crimson text-crimson-foreground'
                                                        : 'border-border bg-card text-champagne'
                                                )}
                                            >
                                                <Icon className="h-4 w-4" />
                                            </div>
                                            <div>
                                                <div className={cn('font-display text-sm font-semibold', selected && 'text-crimson')}>
                                                    {label}
                                                </div>
                                                {meta?.hint && (
                                                    <div className="mt-0.5 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                                                        {meta.hint}
                                                    </div>
                                                )}
                                            </div>
                                        </button>
                                    );
                                })}
                            </div>
                            {errors.category && <p className="mt-3 text-xs text-plasma">{errors.category}</p>}
                        </motion.div>

                        {/* Identity */}
                        <motion.div
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.05 }}
                            className="rounded-2xl border border-border bg-card p-6 sm:p-8"
                        >
                            <SectionTitle kicker="Étape 2" title="Ton identité" />
                            <div className="mt-4 grid gap-5 sm:grid-cols-2">
                                <Field label="Prénom" required error={errors.first_name}>
                                    <Input
                                        value={data.first_name}
                                        onChange={(e) => setData('first_name', e.target.value)}
                                        required
                                        placeholder="Youssef"
                                        autoComplete="given-name"
                                    />
                                </Field>
                                <Field label="Nom" required error={errors.last_name}>
                                    <Input
                                        value={data.last_name}
                                        onChange={(e) => setData('last_name', e.target.value)}
                                        required
                                        placeholder="Amrani"
                                        autoComplete="family-name"
                                    />
                                </Field>
                                <Field label="Email" required error={errors.email}>
                                    <div className="relative">
                                        <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            required
                                            placeholder="tu@example.com"
                                            className="pl-10"
                                            autoComplete="email"
                                        />
                                    </div>
                                </Field>
                                <Field label="Téléphone" required error={errors.phone}>
                                    <div className="relative">
                                        <Phone className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            type="tel"
                                            value={data.phone}
                                            onChange={(e) => setData('phone', e.target.value)}
                                            required
                                            placeholder="+212 6 00 00 00 00"
                                            className="pl-10"
                                            autoComplete="tel"
                                        />
                                    </div>
                                </Field>
                                <Field label="Date de naissance" required error={errors.birthdate}>
                                    <Input
                                        type="date"
                                        value={data.birthdate}
                                        onChange={(e) => setData('birthdate', e.target.value)}
                                        required
                                    />
                                </Field>
                                <Field label="Nationalité" error={errors.nationality}>
                                    <Input
                                        value={data.nationality}
                                        onChange={(e) => setData('nationality', e.target.value)}
                                        placeholder="Marocaine"
                                    />
                                </Field>
                                <Field label="Ville" error={errors.city} className="sm:col-span-2">
                                    <div className="relative">
                                        <MapPin className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                                        <Input
                                            value={data.city}
                                            onChange={(e) => setData('city', e.target.value)}
                                            placeholder="Kénitra"
                                            className="pl-10"
                                            autoComplete="address-level2"
                                        />
                                    </div>
                                </Field>
                            </div>
                        </motion.div>

                        {/* Sport profile */}
                        <motion.div
                            initial={{ opacity: 0, y: 12 }}
                            animate={{ opacity: 1, y: 0 }}
                            transition={{ delay: 0.1 }}
                            className="rounded-2xl border border-border bg-card p-6 sm:p-8"
                        >
                            <SectionTitle kicker="Étape 3" title="Ton profil sportif" />
                            <div className="mt-4 grid gap-5 sm:grid-cols-2">
                                <Field label="Poste préféré" error={errors.position_preference}>
                                    <select
                                        value={data.position_preference}
                                        onChange={(e) => setData('position_preference', e.target.value)}
                                        className="flex h-10 w-full rounded-lg border border-input bg-card px-4 py-2 text-sm focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20"
                                    >
                                        <option value="">— Sélectionner —</option>
                                        {positions.map((p) => (
                                            <option key={p} value={p}>
                                                {p}
                                            </option>
                                        ))}
                                    </select>
                                </Field>
                                <Field label="Années d'expérience" error={errors.experience_years}>
                                    <Input
                                        type="number"
                                        min="0"
                                        max="60"
                                        value={data.experience_years}
                                        onChange={(e) => setData('experience_years', e.target.value)}
                                        placeholder="5"
                                    />
                                </Field>
                                <Field
                                    label="Club actuel / ancien club"
                                    hint="Facultatif. Aide notre staff à évaluer ton profil."
                                    error={errors.current_club}
                                    className="sm:col-span-2"
                                >
                                    <Input
                                        value={data.current_club}
                                        onChange={(e) => setData('current_club', e.target.value)}
                                        placeholder="Nom du club"
                                    />
                                </Field>
                                <Field
                                    label="Message (facultatif)"
                                    hint="Motivation, palmarès, disponibilités…"
                                    error={errors.message}
                                    className="sm:col-span-2"
                                >
                                    <Textarea
                                        value={data.message}
                                        onChange={(e) => setData('message', e.target.value)}
                                        rows={5}
                                        placeholder="Raconte-nous pourquoi tu veux rejoindre Dina Kenitra FC…"
                                    />
                                </Field>
                            </div>
                        </motion.div>

                        {/* Consent (Loi 09-08) */}
                        <div className="rounded-2xl border border-crimson/20 bg-card p-6">
                            <SectionTitle kicker="Étape 5" title="Consentement" />
                            <p className="mt-3 text-sm leading-relaxed text-muted-foreground">
                                En envoyant cette candidature tu confies à Dina Kenitra FC des
                                données personnelles (identité, contact, CV). Conformément à la
                                <span className="whitespace-nowrap"> Loi 09-08</span> sur la
                                protection des données personnelles au Maroc, elles sont
                                collectées pour évaluer ta candidature, conservées 6 mois
                                (candidatures non retenues) et accessibles uniquement au staff
                                sportif du club. Tu disposes d'un droit d'accès, de
                                rectification, d'opposition et de suppression que tu peux exercer
                                à tout moment via{' '}
                                <a
                                    href="mailto:contact@dinakenitrafc.ma?subject=Donn%C3%A9es%20personnelles"
                                    className="font-semibold text-crimson hover:underline"
                                >
                                    contact@dinakenitrafc.ma
                                </a>
                                {' '}ou en{' '}
                                <a href="/candidature/supprimer" className="font-semibold text-crimson hover:underline">
                                    demandant la suppression
                                </a>
                                . Politique complète :{' '}
                                <a href="/confidentialite" className="font-semibold text-crimson hover:underline">
                                    /confidentialite
                                </a>
                                .
                            </p>

                            <label className="mt-5 flex cursor-pointer items-start gap-3 rounded-lg border border-border bg-background p-3">
                                <input
                                    type="checkbox"
                                    checked={data.consent}
                                    onChange={(e) => setData('consent', e.target.checked)}
                                    className="mt-0.5 h-4 w-4 shrink-0 rounded border-input text-crimson focus:ring-2 focus:ring-crimson/40"
                                    required
                                />
                                <span className="text-sm text-foreground">
                                    <strong>J'accepte</strong> que Dina Kenitra FC traite mes
                                    données personnelles aux fins et conditions décrites ci-dessus.
                                </span>
                            </label>
                            {errors.consent && (
                                <p className="mt-2 text-xs text-plasma">{errors.consent}</p>
                            )}

                            {isMinor && (
                                <div className="mt-4 space-y-3 rounded-lg border border-amber/30 bg-amber/10 p-4">
                                    <p className="text-sm font-semibold text-amber">
                                        Candidat·e mineur·e détecté·e
                                    </p>
                                    <p className="text-xs text-muted-foreground">
                                        Comme tu as moins de 18 ans, la loi impose le consentement
                                        d'un parent ou tuteur légal. En cochant la case ci-dessous
                                        tu confirmes que ce consentement a été obtenu.
                                    </p>
                                    <label className="flex cursor-pointer items-start gap-3 rounded-lg border border-amber/30 bg-background p-3">
                                        <input
                                            type="checkbox"
                                            checked={data.parental_consent}
                                            onChange={(e) => setData('parental_consent', e.target.checked)}
                                            className="mt-0.5 h-4 w-4 shrink-0 rounded border-input text-amber focus:ring-2 focus:ring-amber/40"
                                            required
                                        />
                                        <span className="text-sm text-foreground">
                                            <strong>Un parent ou tuteur légal a donné son accord</strong>{' '}
                                            pour cette candidature et pour le traitement de mes
                                            données personnelles.
                                        </span>
                                    </label>
                                    {errors.parental_consent && (
                                        <p className="text-xs text-plasma">{errors.parental_consent}</p>
                                    )}
                                </div>
                            )}
                        </div>

                        {/* Submit */}
                        <div className="flex items-center justify-end gap-3">
                            <Button type="submit" size="lg" disabled={processing || !data.consent || (isMinor && !data.parental_consent)}>
                                {processing ? (
                                    <Loader2 className="h-4 w-4 animate-spin" />
                                ) : (
                                    <Send className="h-4 w-4" />
                                )}
                                Envoyer ma candidature
                            </Button>
                        </div>
                    </div>

                    {/* Sidebar */}
                    <motion.aside
                        initial={{ opacity: 0, y: 12 }}
                        animate={{ opacity: 1, y: 0 }}
                        transition={{ delay: 0.05 }}
                        className="space-y-6"
                    >
                        {/* CV */}
                        <div className="rounded-2xl border border-champagne/20 bg-card p-6">
                            <SectionTitle kicker="Étape 4" title="Ton CV" />
                            <p className="mt-2 text-xs text-muted-foreground">
                                PDF, JPG ou PNG · 5 Mo max
                            </p>

                            <label className="mt-4 flex cursor-pointer flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-border bg-background/50 px-4 py-8 text-center transition-colors hover:border-champagne/50 hover:bg-champagne/5">
                                {cvName ? (
                                    <>
                                        <div className="rounded-full border border-mint/30 bg-mint/10 p-3">
                                            <FileText className="h-6 w-6 text-mint" />
                                        </div>
                                        <div>
                                            <div className="text-sm font-semibold text-foreground">
                                                {cvName}
                                            </div>
                                            <div className="mt-1 font-mono text-[10px] uppercase tracking-widest text-champagne">
                                                Cliquer pour changer
                                            </div>
                                        </div>
                                    </>
                                ) : (
                                    <>
                                        <div className="rounded-full border border-border bg-card p-3">
                                            <Upload className="h-5 w-5 text-champagne" />
                                        </div>
                                        <div>
                                            <div className="text-sm font-semibold text-foreground">
                                                Téléverser un CV
                                            </div>
                                            <div className="mt-1 text-xs text-muted-foreground">
                                                Glisse un fichier ou clique ici
                                            </div>
                                        </div>
                                    </>
                                )}
                                <input
                                    type="file"
                                    accept=".pdf,.jpg,.jpeg,.png,.webp"
                                    onChange={onCvChange}
                                    className="hidden"
                                />
                            </label>

                            {cvName && (
                                <button
                                    type="button"
                                    onClick={() => {
                                        setData('cv', null);
                                        setCvName(null);
                                    }}
                                    className="mt-2 flex w-full items-center justify-center gap-1.5 rounded-lg py-2 text-xs text-plasma hover:bg-plasma/10"
                                >
                                    <Trash2 className="h-3.5 w-3.5" />
                                    Retirer le CV
                                </button>
                            )}

                            {progress && (
                                <div className="mt-3 h-1 overflow-hidden rounded-full bg-muted">
                                    <div
                                        className="h-full bg-champagne transition-all"
                                        style={{ width: `${progress.percentage}%` }}
                                    />
                                </div>
                            )}
                            {errors.cv && <p className="mt-2 text-xs text-plasma">{errors.cv}</p>}
                        </div>

                        {/* What happens next */}
                        <div className="rounded-2xl border border-border bg-card p-6">
                            <SectionTitle kicker="Après ton envoi" title="La suite" />
                            <ol className="mt-4 space-y-3 text-sm">
                                {[
                                    'Notre staff étudie ta candidature',
                                    'On te contacte par email sous 7 jours',
                                    'Convocation à un essai au complexe',
                                    'Retour et intégration si validé',
                                ].map((step, i) => (
                                    <li key={i} className="flex items-start gap-3">
                                        <span className="inline-flex h-6 w-6 shrink-0 items-center justify-center rounded-full bg-crimson/10 font-mono text-[10px] font-bold text-crimson">
                                            {i + 1}
                                        </span>
                                        <span className="text-muted-foreground">{step}</span>
                                    </li>
                                ))}
                            </ol>
                        </div>

                        <div className="rounded-2xl border border-border bg-card p-6 text-sm text-muted-foreground">
                            <div className="flex items-center gap-2 font-mono text-[10px] font-semibold uppercase tracking-widest text-champagne">
                                <User className="h-3 w-3" />
                                Loi 09-08 · CNDP
                            </div>
                            <p className="mt-2 leading-relaxed">
                                Tes données sont traitées conformément à la loi marocaine
                                sur la protection des données personnelles. Détails complets
                                dans notre{' '}
                                <a href="/confidentialite" className="text-crimson underline hover:no-underline">
                                    politique de confidentialité
                                </a>
                                .
                            </p>
                        </div>
                    </motion.aside>
                </form>
            </section>
        </SiteLayout>
    );
}

function SectionTitle({ kicker, title }: { kicker: string; title: string }) {
    return (
        <div>
            <div className="font-mono text-[10px] font-semibold uppercase tracking-[0.3em] text-champagne">
                {kicker}
            </div>
            <h2 className="mt-2 font-display text-xl font-bold">{title}</h2>
        </div>
    );
}
