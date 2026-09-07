import { useState, FormEventHandler } from 'react';
import { Head, Link, router, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Image as ImageIcon, Loader2, Trash2, Upload } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { EmptyState } from '@/Components/site/EmptyState';
import { ConfirmDialog } from '@/Components/site/ConfirmDialog';
import type { Gallery, Photo } from '@/types/models';

interface Props { gallery: Gallery; photos: Photo[]; }

export default function GalleryPhotos({ gallery, photos }: Props) {
    const [toDelete, setToDelete] = useState<Photo | null>(null);
    const [files, setFiles] = useState<File[]>([]);
    const { post, processing, progress, errors, reset } = useForm({
        photos: [] as File[],
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        if (files.length === 0) return;
        const formData = new FormData();
        files.forEach((f) => formData.append('photos[]', f));
        router.post(`/galleries/${gallery.id}/photos/store-multiple`, formData, {
            forceFormData: true,
            onSuccess: () => { setFiles([]); reset(); },
        });
    };

    return (
        <AdminLayout>
            <Head title={`Photos - ${gallery.name}`} />
            <div className="mb-6">
                <Link href="/galleries" className="inline-flex items-center gap-2 text-sm text-muted-foreground hover:text-crimson"><ArrowLeft className="h-4 w-4" />Retour aux galeries</Link>
                <div className="mt-3 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">Galerie</div>
                        <h1 className="mt-1 font-display text-3xl font-bold">{gallery.name}</h1>
                        <div className="mt-1 text-sm text-muted-foreground">{photos.length} photo{photos.length > 1 ? 's' : ''}</div>
                    </div>
                </div>
            </div>

            <form onSubmit={submit} className="mb-8 rounded-2xl border border-champagne/20 bg-card p-6">
                <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">Ajouter des photos</div>
                <label className="mt-4 flex cursor-pointer flex-col items-center justify-center gap-3 rounded-xl border-2 border-dashed border-border bg-background/50 py-10 text-center transition-colors hover:border-champagne/50">
                    <Upload className="h-10 w-10 text-champagne" />
                    <div className="text-sm font-semibold">{files.length > 0 ? `${files.length} fichier${files.length > 1 ? 's' : ''} sélectionné${files.length > 1 ? 's' : ''}` : 'Sélectionner des images'}</div>
                    <div className="text-xs text-muted-foreground">Tu peux sélectionner plusieurs images d'un coup.</div>
                    <input type="file" multiple accept="image/*" onChange={(e) => setFiles(Array.from(e.target.files ?? []))} className="hidden" />
                </label>
                {errors['photos'] && <p className="mt-2 text-xs text-plasma">{errors['photos']}</p>}
                {progress && <div className="mt-4 h-1 overflow-hidden rounded-full bg-muted"><div className="h-full bg-crimson" style={{ width: `${progress.percentage}%` }} /></div>}
                <div className="mt-4 flex items-center justify-end gap-3">
                    {files.length > 0 && <Button type="button" variant="outline" onClick={() => setFiles([])}>Vider</Button>}
                    <Button type="submit" disabled={files.length === 0 || processing}>{processing ? <Loader2 className="h-4 w-4 animate-spin" /> : <Upload className="h-4 w-4" />}Uploader</Button>
                </div>
            </form>

            {photos.length === 0 ? (
                <EmptyState icon={ImageIcon} title="Aucune photo" description="Ajoute la première photo à cette galerie." />
            ) : (
                <div className="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    {photos.map((p, i) => (
                        <motion.div key={p.id} initial={{ opacity: 0, scale: 0.95 }} animate={{ opacity: 1, scale: 1 }} transition={{ delay: (i % 12) * 0.03 }} className="group relative aspect-square overflow-hidden rounded-xl border border-border bg-card">
                            <img src={`/storage/${p.image}`} alt={p.caption ?? ''} loading="lazy" className="h-full w-full object-cover" />
                            <button onClick={() => setToDelete(p)} className="absolute right-2 top-2 hidden h-8 w-8 items-center justify-center rounded-full bg-obsidian/80 text-bone opacity-0 backdrop-blur-sm transition-opacity hover:bg-plasma group-hover:flex group-hover:opacity-100"><Trash2 className="h-4 w-4" /></button>
                        </motion.div>
                    ))}
                </div>
            )}

            <ConfirmDialog open={!!toDelete} title="Supprimer cette photo ?" description="Le fichier sera définitivement supprimé." confirmLabel="Supprimer" variant="destructive" onCancel={() => setToDelete(null)} onConfirm={() => { if (!toDelete) return; router.delete(`/galleries/${gallery.id}/photos/${toDelete.id}`, { onSuccess: () => setToDelete(null) }); }} />
        </AdminLayout>
    );
}
