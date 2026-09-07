import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Loader2, Save, Trash2, User } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Staff } from '@/types/models';

interface Props {
    staff: Staff | null;
}

export default function StaffForm({ staff }: Props) {
    const isEdit = !!staff;
    const [preview, setPreview] = useState<string | null>(
        staff?.photo ? `/storage/${staff.photo}` : null
    );

    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        first_name: staff?.first_name ?? '',
        last_name: staff?.last_name ?? '',
        position: staff?.position ?? '',
        photo: null as File | null,
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/staff/${staff!.id}` : '/staff';
        post(url, { forceFormData: true });
    };

    const onPhotoChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('photo', file);
        if (file) {
            const reader = new FileReader();
            reader.onload = () => setPreview(reader.result as string);
            reader.readAsDataURL(file);
        }
    };

    const removePhoto = () => {
        setData('photo', null);
        setPreview(null);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier membre' : 'Nouveau membre'} />

            <div className="mb-6">
                <Link
                    href="/staff"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour au staff
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouveau'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit
                            ? `${staff!.first_name} ${staff!.last_name}`
                            : 'Ajouter un membre'}
                    </h1>
                </div>
            </div>

            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[300px_1fr]">
                <motion.aside
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="space-y-4"
                >
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <div className="font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Photo
                        </div>
                        <div className="mt-4 aspect-[3/4] overflow-hidden rounded-xl border border-border bg-muted">
                            {preview ? (
                                <img
                                    src={preview}
                                    alt="Aperçu"
                                    className="h-full w-full object-cover"
                                />
                            ) : (
                                <div className="flex h-full w-full flex-col items-center justify-center gap-2 text-muted-foreground/60">
                                    <User className="h-16 w-16" strokeWidth={1} />
                                    <span className="text-xs">Aucune photo</span>
                                </div>
                            )}
                        </div>
                        <div className="mt-4 space-y-2">
                            <label className="flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm font-medium transition-colors hover:border-crimson/50 hover:text-crimson">
                                <Camera className="h-4 w-4" />
                                {preview ? 'Changer' : 'Téléverser'}
                                <input
                                    type="file"
                                    accept="image/*"
                                    onChange={onPhotoChange}
                                    className="hidden"
                                />
                            </label>
                            {preview && (
                                <button
                                    type="button"
                                    onClick={removePhoto}
                                    className="flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-sm text-plasma hover:bg-plasma/10"
                                >
                                    <Trash2 className="h-4 w-4" />
                                    Retirer
                                </button>
                            )}
                            {progress && (
                                <div className="h-1 overflow-hidden rounded-full bg-muted">
                                    <div
                                        className="h-full bg-crimson transition-all"
                                        style={{ width: `${progress.percentage}%` }}
                                    />
                                </div>
                            )}
                            {errors.photo && (
                                <p className="text-xs text-plasma">{errors.photo}</p>
                            )}
                        </div>
                    </div>
                </motion.aside>

                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.05 }}
                    className="space-y-6"
                >
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
                            Identité
                        </div>
                        <div className="mt-4 grid gap-5 sm:grid-cols-2">
                            <Field label="Prénom" required error={errors.first_name}>
                                <Input
                                    value={data.first_name}
                                    onChange={(e) => setData('first_name', e.target.value)}
                                    required
                                />
                            </Field>
                            <Field label="Nom" required error={errors.last_name}>
                                <Input
                                    value={data.last_name}
                                    onChange={(e) => setData('last_name', e.target.value)}
                                    required
                                />
                            </Field>
                            <Field
                                label="Poste"
                                required
                                error={errors.position}
                                className="sm:col-span-2"
                                hint="Ex : Kinésithérapeute, Manager général, Analyste vidéo."
                            >
                                <Input
                                    value={data.position}
                                    onChange={(e) => setData('position', e.target.value)}
                                    required
                                    placeholder="Poste au sein du club"
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button">
                            <Link href="/staff">Annuler</Link>
                        </Button>
                        <Button type="submit" size="lg" disabled={processing}>
                            {processing ? (
                                <Loader2 className="h-4 w-4 animate-spin" />
                            ) : (
                                <Save className="h-4 w-4" />
                            )}
                            {isEdit ? 'Enregistrer' : 'Créer le membre'}
                        </Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}
