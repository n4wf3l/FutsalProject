import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Image as ImageIcon, Loader2, Save, Trash2 } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Gallery } from '@/types/models';

interface Props { gallery: Gallery | null; }

export default function GalleryForm({ gallery }: Props) {
    const isEdit = !!gallery;
    const [preview, setPreview] = useState<string | null>(gallery?.cover_image ? `/storage/${gallery.cover_image}` : null);
    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        name: gallery?.name ?? '',
        description: gallery?.description ?? '',
        cover_image: null as File | null,
    });
    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        post(isEdit ? `/galleries/${gallery!.id}` : '/galleries', { forceFormData: true });
    };
    const onCoverChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('cover_image', file);
        if (file) { const r = new FileReader(); r.onload = () => setPreview(r.result as string); r.readAsDataURL(file); }
    };
    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier galerie' : 'Nouvelle galerie'} />
            <div className="mb-6">
                <Link href="/galleries" className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-crimson"><ArrowLeft className="h-4 w-4" />Retour</Link>
                <h1 className="mt-3 font-display text-3xl font-bold">{isEdit ? gallery!.name : 'Nouvelle galerie'}</h1>
            </div>
            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[300px_1fr]">
                <motion.aside initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} className="space-y-4">
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <div className="font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">Image de couverture</div>
                        <div className="mt-4 aspect-video overflow-hidden rounded-xl border border-border bg-muted">
                            {preview ? <img src={preview} alt="" className="h-full w-full object-cover" /> : <div className="flex h-full w-full items-center justify-center"><ImageIcon className="h-12 w-12 text-muted-foreground/30" /></div>}
                        </div>
                        <label className="mt-4 flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm hover:border-crimson/50 hover:text-crimson">
                            <Camera className="h-4 w-4" />{preview ? 'Changer' : 'Téléverser'}
                            <input type="file" accept="image/*" onChange={onCoverChange} className="hidden" />
                        </label>
                        {preview && <button type="button" onClick={() => { setData('cover_image', null); setPreview(null); }} className="mt-2 flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-plasma hover:bg-plasma/10"><Trash2 className="h-4 w-4" />Retirer</button>}
                        {progress && <div className="mt-2 h-1 overflow-hidden rounded-full bg-muted"><div className="h-full bg-crimson" style={{ width: `${progress.percentage}%` }} /></div>}
                        {errors.cover_image && <p className="mt-2 text-xs text-plasma">{errors.cover_image}</p>}
                    </div>
                </motion.aside>
                <motion.div initial={{ opacity: 0, y: 12 }} animate={{ opacity: 1, y: 0 }} transition={{ delay: 0.05 }} className="space-y-6">
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">Métadonnées</div>
                        <div className="mt-4 space-y-5">
                            <Field label="Nom" required error={errors.name}><Input value={data.name} onChange={(e) => setData('name', e.target.value)} required placeholder="Match Dina Kenitra vs Wifaq Casablanca" /></Field>
                            <Field label="Description" error={errors.description}><Textarea value={data.description ?? ''} onChange={(e) => setData('description', e.target.value)} rows={5} /></Field>
                        </div>
                    </div>
                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button"><Link href="/galleries">Annuler</Link></Button>
                        <Button type="submit" size="lg" disabled={processing}>{processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Save className="h-4 w-4" />}{isEdit ? 'Enregistrer' : 'Créer'}</Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}
