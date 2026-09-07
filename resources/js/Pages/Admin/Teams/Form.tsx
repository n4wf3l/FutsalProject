import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Loader2, Save, Shield, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Team } from '@/types/models';

interface Props { team: Team | null; }

export default function TeamForm({ team }: Props) {
    const isEdit = !!team;
    const [preview, setPreview] = useState<string | null>(team?.logo_path ? `/storage/${team.logo_path}` : null);
    const { data, setData, post, put, processing, errors, progress } = useForm({
        _method: isEdit ? 'PUT' : 'POST',
        name: team?.name ?? '',
        logo: null as File | null,
    });
    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/manage-teams/${team!.id}` : '/manage-teams';
        post(url, { forceFormData: true });
    };
    const onLogoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('logo', file);
        if (file) { const r = new FileReader(); r.onload = () => setPreview(r.result as string); r.readAsDataURL(file); }
    };
    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier équipe' : 'Nouvelle équipe'} />
            <div className="mb-6">
                <Link href="/manage-teams" className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-crimson"><ArrowLeft className="h-4 w-4" />Retour</Link>
                <h1 className="mt-3 font-display text-3xl font-bold">{isEdit ? team!.name : 'Ajouter une équipe'}</h1>
            </div>
            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[300px_1fr]">
                <motion.aside initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} className="space-y-4">
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <div className="font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">Logo du club</div>
                        <div className="mt-4 flex aspect-square items-center justify-center overflow-hidden rounded-xl border border-border bg-background p-4">
                            {preview ? <img src={preview} alt="" className="h-full w-full object-contain" /> : <Shield className="h-16 w-16 text-muted-foreground/30" />}
                        </div>
                        <label className="mt-4 flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm hover:border-crimson/50 hover:text-crimson">
                            <Camera className="h-4 w-4" />{preview ? 'Changer' : 'Téléverser'}
                            <input type="file" accept="image/*" onChange={onLogoChange} className="hidden" />
                        </label>
                        {preview && <button type="button" onClick={() => { setData('logo', null); setPreview(null); }} className="mt-2 flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-plasma hover:bg-plasma/10"><Trash2 className="h-4 w-4" />Retirer</button>}
                        {progress && <div className="mt-2 h-1 overflow-hidden rounded-full bg-muted"><div className="h-full bg-crimson" style={{ width: `${progress.percentage}%` }} /></div>}
                        {errors.logo && <p className="mt-2 text-xs text-plasma">{errors.logo}</p>}
                    </div>
                </motion.aside>
                <motion.div initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.05 }} className="space-y-6">
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">Identité</div>
                        <Field label="Nom de l'équipe" required error={errors.name} className="mt-4">
                            <Input value={data.name} onChange={(e) => setData('name', e.target.value)} required placeholder="Dina Kenitra FC" />
                        </Field>
                    </div>
                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button"><Link href="/manage-teams">Annuler</Link></Button>
                        <Button type="submit" size="lg" disabled={processing}>{processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}{isEdit ? 'Enregistrer' : 'Créer'}</Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}
