import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Loader2, Save, Trash2, User } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Player } from '@/types/models';

interface Props {
    player: Player | null;
}

const POSITIONS = [
    'Gardien',
    'Défenseur',
    'Milieu',
    'Ailier',
    'Pivot',
    'Attaquant',
];

export default function PlayerForm({ player }: Props) {
    const isEdit = !!player;
    const [preview, setPreview] = useState<string | null>(
        player?.photo ? `/storage/${player.photo}` : null
    );

    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        first_name: player?.first_name ?? '',
        last_name: player?.last_name ?? '',
        photo: null as File | null,
        birthdate: player?.birthdate ?? '',
        position: player?.position ?? '',
        number: player?.number ?? 0,
        nationality: player?.nationality ?? '',
        height: player?.height ?? 0,
        contract_until: player?.contract_until ?? '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/players/${player!.id}` : '/players';
        post(url, {
            forceFormData: true,
        });
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
            <Head title={isEdit ? 'Modifier joueur' : 'Nouveau joueur'} />

            <div className="mb-6">
                <Link
                    href="/players"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux joueurs
                </Link>
                <div className="mt-3 flex flex-wrap items-end justify-between gap-4">
                    <div>
                        <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                            {isEdit ? 'Édition' : 'Nouveau'}
                        </div>
                        <h1 className="mt-1 font-display text-3xl font-bold">
                            {isEdit
                                ? `${player!.first_name} ${player!.last_name}`
                                : 'Ajouter un joueur'}
                        </h1>
                    </div>
                </div>
            </div>

            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[300px_1fr]">
                {/* Photo panel */}
                <motion.aside
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="space-y-4"
                >
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <div className="font-mono text-[10px] font-semibold uppercase tracking-widest text-muted-foreground">
                            Photo du joueur
                        </div>
                        <div className="mt-4 aspect-[3/4] overflow-hidden rounded-xl border border-border bg-muted">
                            {preview ? (
                                <img src={preview} alt="Aperçu" className="h-full w-full object-cover" />
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
                            {errors.photo && <p className="text-xs text-plasma">{errors.photo}</p>}
                        </div>
                    </div>
                </motion.aside>

                {/* Fields panel */}
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.05 }}
                    className="space-y-6"
                >
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Identité" />
                        <div className="mt-4 grid gap-5 sm:grid-cols-2">
                            <Field label="Prénom" required error={errors.first_name}>
                                <Input
                                    value={data.first_name}
                                    onChange={(e) => setData('first_name', e.target.value)}
                                    required
                                    placeholder="Youssef"
                                />
                            </Field>
                            <Field label="Nom" required error={errors.last_name}>
                                <Input
                                    value={data.last_name}
                                    onChange={(e) => setData('last_name', e.target.value)}
                                    required
                                    placeholder="Amrani"
                                />
                            </Field>
                            <Field label="Date de naissance" required error={errors.birthdate}>
                                <Input
                                    type="date"
                                    value={data.birthdate}
                                    onChange={(e) => setData('birthdate', e.target.value)}
                                    required
                                />
                            </Field>
                            <Field label="Nationalité" required error={errors.nationality}>
                                <Input
                                    value={data.nationality}
                                    onChange={(e) => setData('nationality', e.target.value)}
                                    required
                                    placeholder="Marocaine"
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Poste et physique" />
                        <div className="mt-4 grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                            <Field label="Poste" required error={errors.position}>
                                <select
                                    value={data.position}
                                    onChange={(e) => setData('position', e.target.value)}
                                    required
                                    className="flex h-10 w-full rounded-lg border border-input bg-card px-4 py-2 text-sm text-foreground shadow-sm focus:border-crimson focus:outline-none focus:ring-2 focus:ring-crimson/20"
                                >
                                    <option value="">— Sélectionner —</option>
                                    {POSITIONS.map((p) => (
                                        <option key={p} value={p}>
                                            {p}
                                        </option>
                                    ))}
                                </select>
                            </Field>
                            <Field label="Numéro" required error={errors.number}>
                                <Input
                                    type="number"
                                    min="1"
                                    max="99"
                                    value={data.number}
                                    onChange={(e) => setData('number', parseInt(e.target.value) || 0)}
                                    required
                                />
                            </Field>
                            <Field label="Taille (cm)" required error={errors.height}>
                                <Input
                                    type="number"
                                    min="140"
                                    max="220"
                                    value={data.height}
                                    onChange={(e) => setData('height', parseInt(e.target.value) || 0)}
                                    required
                                />
                            </Field>
                            <Field label="Contrat jusqu'à" required error={errors.contract_until}>
                                <Input
                                    type="date"
                                    value={data.contract_until}
                                    onChange={(e) => setData('contract_until', e.target.value)}
                                    required
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button">
                            <Link href="/players">Annuler</Link>
                        </Button>
                        <Button type="submit" size="lg" disabled={processing}>
                            {processing ? (
                                <Loader2 className="h-4 w-4 animate-spin" />
                            ) : (
                                <Save className="h-4 w-4" />
                            )}
                            {isEdit ? 'Enregistrer' : 'Créer le joueur'}
                        </Button>
                    </div>
                </motion.div>
            </form>
        </AdminLayout>
    );
}

function SectionTitle({ title }: { title: string }) {
    return (
        <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
            {title}
        </div>
    );
}
