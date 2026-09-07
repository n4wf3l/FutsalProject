import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Loader2, Save, Trash2, UserCog } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Coach } from '@/types/models';

interface Props { coach: Coach | null; }

export default function CoachForm({ coach }: Props) {
    const isEdit = !!coach;
    const [preview, setPreview] = useState<string | null>(coach?.photo ? `/storage/${coach.photo}` : null);
    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        first_name: coach?.first_name ?? '',
        last_name: coach?.last_name ?? '',
        birth_date: coach?.birth_date ?? '',
        coaching_since: coach?.coaching_since ?? '',
        birth_city: coach?.birth_city ?? '',
        nationality: coach?.nationality ?? '',
        description: coach?.description ?? '',
        photo: null as File | null,
    });
    const submit: FormEventHandler = (e) => { e.preventDefault(); post(isEdit ? `/coaches/${coach!.id}` : '/coaches', { forceFormData: true }); };
    const onPhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('photo', file);
        if (file) { const r = new FileReader(); r.onload = () => setPreview(r.result as string); r.readAsDataURL(file); }
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier coach' : 'Nouveau coach'} />
            <div className="mb-6">
                <Link href="/coaches" className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-crimson"><ArrowLeft className="h-4 w-4" />Retour</Link>
                <h1 className="mt-3 font-display text-3xl font-bold">{isEdit ? `${coach!.first_name} ${coach!.last_name}` : 'Ajouter un coach'}</h1>
            </div>
            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[300px_1fr]">
                <motion.aside initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} className="space-y-4">
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <div className="font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">Photo</div>
                        <div className="mt-4 aspect-[3/4] overflow-hidden rounded-xl border border-border bg-muted">
                            {preview ? <img src={preview} alt="" className="h-full w-full object-cover" /> : <div className="flex h-full w-full items-center justify-center"><UserCog className="h-16 w-16 text-muted-foreground/30" /></div>}
                        </div>
                        <label className="mt-4 flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm hover:border-crimson/50 hover:text-crimson">
                            <Camera className="h-4 w-4" />{preview ? 'Changer' : 'Téléverser'}
                            <input type="file" accept="image/*" onChange={onPhotoChange} className="hidden" />
                        </label>
                        {preview && <button type="button" onClick={() => { setData('photo', null); setPreview(null); }} className="mt-2 flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-plasma hover:bg-plasma/10"><Trash2 className="h-4 w-4" />Retirer</button>}
                        {progress && <div className="mt-2 h-1 overflow-hidden rounded-full bg-muted"><div className="h-full bg-crimson" style={{ width: `${progress.percentage}%` }} /></div>}
                        {errors.photo && <p className="mt-2 text-xs text-plasma">{errors.photo}</p>}
                    </div>
                </motion.aside>
                <motion.div initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.05 }} className="space-y-6">
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">Identité</div>
                        <div className="mt-4 grid gap-5 sm:grid-cols-2">
                            <Field label="Prénom" required error={errors.first_name}><Input value={data.first_name} onChange={(e) => setData('first_name', e.target.value)} required /></Field>
                            <Field label="Nom" required error={errors.last_name}><Input value={data.last_name} onChange={(e) => setData('last_name', e.target.value)} required /></Field>
                            <Field label="Date de naissance" required error={errors.birth_date}><Input type="date" value={data.birth_date} onChange={(e) => setData('birth_date', e.target.value)} required /></Field>
                            <Field label="Coach depuis" required error={errors.coaching_since}><Input type="date" value={data.coaching_since} onChange={(e) => setData('coaching_since', e.target.value)} required /></Field>
                            <Field label="Ville de naissance" required error={errors.birth_city}><Input value={data.birth_city} onChange={(e) => setData('birth_city', e.target.value)} required /></Field>
                            <Field label="Nationalité" required error={errors.nationality}><Input value={data.nationality} onChange={(e) => setData('nationality', e.target.value)} required /></Field>
                        </div>
                    </div>
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">Biographie</div>
                        <Field label="Description" error={errors.description} hint="HTML autorisé." className="mt-4">
                            <Textarea value={data.description} onChange={(e) => setData('description', e.target.value)} rows={10} className="font-mono text-sm" />
                        </Field>
                    </div>
                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button"><Link href="/coaches">Annuler</Link></Button>
                        <Button type="submit" size="lg" disabled={processing}>{processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}{isEdit ? 'Enregistrer' : 'Créer le coach'}</Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}
