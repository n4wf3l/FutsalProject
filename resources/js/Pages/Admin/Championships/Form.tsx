import { FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Loader2, Save } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Championship } from '@/types/models';

interface Props {
    championship: Championship | null;
}

export default function ChampionshipForm({ championship }: Props) {
    const isEdit = !!championship;

    const { data, setData, post, processing, errors } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        name: championship?.name ?? '',
        season: championship?.season ?? '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit ? `/championships/${championship!.id}` : '/championships';
        post(url);
    };

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier championnat' : 'Nouveau championnat'} />

            <div className="mb-6">
                <Link
                    href="/championships"
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-crimson"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux championnats
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouveau'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit ? championship!.name : 'Créer un championnat'}
                    </h1>
                </div>
            </div>

            <form onSubmit={submit} className="space-y-6">
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="rounded-2xl border border-border bg-card p-6 sm:p-8"
                >
                    <div className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
                        Informations
                    </div>
                    <div className="mt-4 grid gap-5 sm:grid-cols-2">
                        <Field label="Nom" required error={errors.name}>
                            <Input
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                                placeholder="Championnat national D1"
                            />
                        </Field>
                        <Field label="Saison" required error={errors.season}>
                            <Input
                                value={data.season}
                                onChange={(e) => setData('season', e.target.value)}
                                required
                                placeholder="2025/2026"
                            />
                        </Field>
                    </div>
                </motion.div>

                <div className="flex items-center justify-end gap-3">
                    <Button asChild variant="outline" type="button">
                        <Link href="/championships">Annuler</Link>
                    </Button>
                    <Button type="submit" size="lg" disabled={processing}>
                        {processing ? (
                            <Loader2 className="h-4 w-4 animate-spin" />
                        ) : (
                            <Save className="h-4 w-4" />
                        )}
                        {isEdit ? 'Enregistrer' : 'Créer le championnat'}
                    </Button>
                </div>
            </form>
        </AdminLayout>
    );
}
