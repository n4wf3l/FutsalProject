import { FormEventHandler } from 'react';
import { Head, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { Building2, Camera, Loader2, Mail, MapPin, MessageSquare, Phone, Save, Trash2, User } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { FlashMessage } from '@/types/models';

interface ClubInfo {
    id: number;
    sportcomplex_location: string;
    city: string;
    phone: string;
    email: string;
    president: string;
    facebook: string | null;
    instagram: string | null;
    latitude: number;
    longitude: number;
    federation_logo: string | null;
    organization_logo: string | null;
}

interface Props {
    clubInfo: ClubInfo | null;
    flashMessage: FlashMessage | null;
}

export default function SettingsIndex({ clubInfo, flashMessage }: Props) {
    return (
        <AdminLayout title="Réglages">
            <Head title="Réglages" />

            <div className="mb-6">
                <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">Configuration</div>
                <h1 className="mt-1 font-display text-3xl font-bold">Réglages du club</h1>
                <p className="mt-2 text-sm text-muted-foreground">Informations affichées dans le footer, sur la page contact et dans le hero.</p>
            </div>

            <div className="grid gap-6 lg:grid-cols-2">
                <ClubInfoCard clubInfo={clubInfo} />
                <FlashMessageCard flashMessage={flashMessage} />
            </div>
        </AdminLayout>
    );
}

function ClubInfoCard({ clubInfo }: { clubInfo: ClubInfo | null }) {
    const { data, setData, post, processing, errors, progress } = useForm({
        _method: 'POST',
        sportcomplex_location: clubInfo?.sportcomplex_location ?? '',
        city: clubInfo?.city ?? '',
        phone: clubInfo?.phone ?? '',
        email: clubInfo?.email ?? '',
        president: clubInfo?.president ?? '',
        facebook: clubInfo?.facebook ?? '',
        instagram: clubInfo?.instagram ?? '',
        latitude: clubInfo?.latitude ?? 0,
        longitude: clubInfo?.longitude ?? 0,
        federation_logo: null as File | null,
        organization_logo: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post('/dashboard/club-info', { forceFormData: true });
    };

    return (
        <motion.form
            onSubmit={submit}
            initial={{ opacity: 0, y: 12 }}
            animate={{ opacity: 1, y: 0 }}
            className="rounded-2xl border border-border bg-card p-6 sm:p-8"
        >
            <div className="mb-4 flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
                <Building2 className="h-3.5 w-3.5" />
                Informations du club
            </div>

            <div className="space-y-5">
                <div className="grid gap-5 sm:grid-cols-2">
                    <Field label="Salle / adresse" error={errors.sportcomplex_location}>
                        <Input value={data.sportcomplex_location} onChange={(e) => setData('sportcomplex_location', e.target.value)} placeholder="Salle Al Wahda" />
                    </Field>
                    <Field label="Ville" error={errors.city}>
                        <Input value={data.city} onChange={(e) => setData('city', e.target.value)} placeholder="Kénitra" />
                    </Field>
                </div>
                <div className="grid gap-5 sm:grid-cols-2">
                    <Field label="Email" error={errors.email}>
                        <div className="relative">
                            <Mail className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} className="pl-10" />
                        </div>
                    </Field>
                    <Field label="Téléphone" error={errors.phone}>
                        <div className="relative">
                            <Phone className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input type="tel" value={data.phone} onChange={(e) => setData('phone', e.target.value)} className="pl-10" />
                        </div>
                    </Field>
                </div>
                <Field label="Président" error={errors.president}>
                    <div className="relative">
                        <User className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                        <Input value={data.president} onChange={(e) => setData('president', e.target.value)} className="pl-10" />
                    </div>
                </Field>
                <div className="grid gap-5 sm:grid-cols-2">
                    <Field label="Facebook" error={errors.facebook}>
                        <div className="relative">
                            <MessageSquare className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input value={data.facebook ?? ''} onChange={(e) => setData('facebook', e.target.value)} className="pl-10" placeholder="https://..." />
                        </div>
                    </Field>
                    <Field label="Instagram" error={errors.instagram}>
                        <div className="relative">
                            <Camera className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input value={data.instagram ?? ''} onChange={(e) => setData('instagram', e.target.value)} className="pl-10" placeholder="https://..." />
                        </div>
                    </Field>
                </div>
                <div className="grid gap-5 sm:grid-cols-2">
                    <Field label="Latitude" error={errors.latitude}>
                        <div className="relative">
                            <MapPin className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input type="number" step="0.0000001" value={data.latitude} onChange={(e) => setData('latitude', parseFloat(e.target.value) || 0)} className="pl-10" />
                        </div>
                    </Field>
                    <Field label="Longitude" error={errors.longitude}>
                        <div className="relative">
                            <MapPin className="pointer-events-none absolute left-3 top-1/2 h-4 w-4 -translate-y-1/2 text-muted-foreground" />
                            <Input type="number" step="0.0000001" value={data.longitude} onChange={(e) => setData('longitude', parseFloat(e.target.value) || 0)} className="pl-10" />
                        </div>
                    </Field>
                </div>

                <div className="grid gap-5 border-t border-border pt-5 sm:grid-cols-2">
                    <LogoUpload
                        label="Logo fédération (FRMF)"
                        currentUrl={clubInfo?.federation_logo ? `/storage/${clubInfo.federation_logo}` : null}
                        onChange={(f) => setData('federation_logo', f)}
                    />
                    <LogoUpload
                        label="Logo ligue / organisation"
                        currentUrl={clubInfo?.organization_logo ? `/storage/${clubInfo.organization_logo}` : null}
                        onChange={(f) => setData('organization_logo', f)}
                    />
                </div>
                {progress && <div className="h-1 overflow-hidden rounded-full bg-muted"><div className="h-full bg-crimson" style={{ width: `${progress.percentage}%` }} /></div>}
            </div>

            <div className="mt-6 flex items-center justify-end">
                <Button type="submit" size="lg" disabled={processing}>
                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}
                    Enregistrer
                </Button>
            </div>
        </motion.form>
    );
}

function FlashMessageCard({ flashMessage }: { flashMessage: FlashMessage | null }) {
    const { data, setData, put, processing, errors } = useForm({
        message: flashMessage?.message ?? '',
        homemessage: flashMessage?.homemessage ?? '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        put('/flashmessage/update');
    };

    return (
        <motion.form
            onSubmit={submit}
            initial={{ opacity: 0, y: 12 }}
            animate={{ opacity: 1, y: 0 }}
            transition={{ delay: 0.05 }}
            className="rounded-2xl border border-border bg-card p-6 sm:p-8"
        >
            <div className="mb-4 flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
                <MessageSquare className="h-3.5 w-3.5" />
                Messages du site
            </div>

            <div className="space-y-5">
                <Field label="Slogan (footer)" error={errors.homemessage} hint="Phrase affichée en italique champagne au-dessus du tagline.">
                    <Textarea value={data.homemessage ?? ''} onChange={(e) => setData('homemessage', e.target.value)} rows={2} />
                </Field>
                <Field label="Message d'annonce" error={errors.message} hint="Peut être affiché en bannière selon la page.">
                    <Textarea value={data.message} onChange={(e) => setData('message', e.target.value)} rows={4} />
                </Field>
            </div>

            <div className="mt-6 flex items-center justify-end">
                <Button type="submit" size="lg" disabled={processing}>
                    {processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}
                    Enregistrer
                </Button>
            </div>
        </motion.form>
    );
}

function LogoUpload({ label, currentUrl, onChange }: { label: string; currentUrl: string | null; onChange: (f: File | null) => void }) {
    return (
        <div>
            <div className="mb-2 font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">{label}</div>
            <div className="flex items-center gap-3 rounded-xl border border-border bg-background p-3">
                <div className="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-lg border border-border bg-card p-1">
                    {currentUrl ? <img src={currentUrl} alt="" className="h-full w-full object-contain" /> : <Camera className="h-6 w-6 text-muted-foreground/40" />}
                </div>
                <label className="flex-1 cursor-pointer rounded-lg border border-border bg-card px-3 py-2 text-center text-xs hover:border-crimson/50 hover:text-crimson">
                    Changer
                    <input type="file" accept="image/*" onChange={(e) => onChange(e.target.files?.[0] ?? null)} className="hidden" />
                </label>
            </div>
        </div>
    );
}
