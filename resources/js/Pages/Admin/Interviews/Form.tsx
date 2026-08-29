import { useState, FormEventHandler } from 'react';
import { Head, Link, useForm } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { ArrowLeft, Camera, Eye, EyeOff, Loader2, Mic, Save, Trash2, User } from 'lucide-react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Button } from '@/Components/ui/Button';
import { Input, Textarea } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';
import type { Interview } from '@/types/models';

interface Props {
    interview: Interview | null;
    roles: string[];
}

export default function InterviewForm({ interview, roles }: Props) {
    const isEdit = !!interview;

    const [heroPreview, setHeroPreview] = useState<string | null>(
        interview?.hero_image ? `/storage/${interview.hero_image}` : null
    );
    const [portraitPreview, setPortraitPreview] = useState<string | null>(
        interview?.interviewee_photo ? `/storage/${interview.interviewee_photo}` : null
    );

    const { data, setData, post, processing, errors, progress } = useForm({
        _method: isEdit ? 'PATCH' : 'POST',
        title: interview?.title ?? '',
        interviewee_name: interview?.interviewee_name ?? '',
        interviewee_role: interview?.interviewee_role ?? '',
        interviewee_affiliation: interview?.interviewee_affiliation ?? '',
        hero_image: null as File | null,
        interviewee_photo: null as File | null,
        video_url: interview?.video_url ?? '',
        excerpt: interview?.excerpt ?? '',
        quote_highlight: interview?.quote_highlight ?? '',
        content: interview?.content ?? '',
        published_at: interview?.published_at
            ? new Date(interview.published_at).toISOString().slice(0, 16)
            : '',
    });

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        const url = isEdit
            ? route('admin.interviews.update', interview!.id)
            : route('admin.interviews.store');
        post(url, { forceFormData: true });
    };

    const onHeroChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('hero_image', file);
        if (file) {
            const reader = new FileReader();
            reader.onload = () => setHeroPreview(reader.result as string);
            reader.readAsDataURL(file);
        }
    };

    const onPortraitChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const file = e.target.files?.[0] ?? null;
        setData('interviewee_photo', file);
        if (file) {
            const reader = new FileReader();
            reader.onload = () => setPortraitPreview(reader.result as string);
            reader.readAsDataURL(file);
        }
    };

    const publishNow = () => {
        const now = new Date();
        const tzOffset = now.getTimezoneOffset() * 60000;
        setData('published_at', new Date(now.getTime() - tzOffset).toISOString().slice(0, 16));
    };

    const isPublished = !!data.published_at;

    return (
        <AdminLayout>
            <Head title={isEdit ? 'Modifier interview' : 'Nouvelle interview'} />

            <div className="mb-6">
                <Link
                    href={route('admin.interviews.index')}
                    className="inline-flex items-center gap-2 text-sm text-muted-foreground transition-colors hover:text-champagne"
                >
                    <ArrowLeft className="h-4 w-4" />
                    Retour aux interviews
                </Link>
                <div className="mt-3">
                    <div className="font-mono text-xs uppercase tracking-[0.3em] text-champagne">
                        {isEdit ? 'Édition' : 'Nouvelle interview'}
                    </div>
                    <h1 className="mt-1 font-display text-3xl font-bold">
                        {isEdit ? interview!.title : 'La Voix du Futsal'}
                    </h1>
                </div>
            </div>

            <form onSubmit={submit} className="grid gap-6 lg:grid-cols-[1fr_320px]">
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    className="space-y-6"
                >
                    {/* Interview */}
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Contenu principal" />
                        <div className="mt-4 space-y-5">
                            <Field label="Titre de l'interview" required error={errors.title}>
                                <Input
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    required
                                    placeholder="Ex : « Le futsal marocain n'a jamais été aussi ambitieux »"
                                />
                            </Field>

                            <Field label="Chapô / accroche (facultatif)" hint="Court paragraphe de mise en contexte" error={errors.excerpt}>
                                <Textarea
                                    value={data.excerpt}
                                    onChange={(e) => setData('excerpt', e.target.value)}
                                    rows={3}
                                    placeholder="En quelques lignes, le contexte et l'angle de l'interview…"
                                />
                            </Field>

                            <Field
                                label="Citation à mettre en valeur"
                                hint="Une phrase forte extraite de l'interview, affichée en gros dans la page"
                                error={errors.quote_highlight}
                            >
                                <Textarea
                                    value={data.quote_highlight}
                                    onChange={(e) => setData('quote_highlight', e.target.value)}
                                    rows={2}
                                    placeholder="La citation qui fera le buzz…"
                                />
                            </Field>

                            <Field
                                label="Contenu de l'interview"
                                required
                                hint="HTML supporté (<p>, <h2>, <strong>, <blockquote>…)"
                                error={errors.content}
                            >
                                <Textarea
                                    value={data.content}
                                    onChange={(e) => setData('content', e.target.value)}
                                    rows={16}
                                    required
                                    placeholder="<p><strong>Q :</strong> Question…</p><p><strong>R :</strong> Réponse…</p>"
                                    className="font-mono text-sm"
                                />
                            </Field>
                        </div>
                    </div>

                    {/* Interviewee */}
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Notre invité·e" />
                        <div className="mt-4 grid gap-5 sm:grid-cols-2">
                            <Field label="Nom" required error={errors.interviewee_name}>
                                <Input
                                    value={data.interviewee_name}
                                    onChange={(e) => setData('interviewee_name', e.target.value)}
                                    required
                                    placeholder="Hicham Dguig"
                                />
                            </Field>
                            <Field label="Rôle" required error={errors.interviewee_role}>
                                <select
                                    value={data.interviewee_role}
                                    onChange={(e) => setData('interviewee_role', e.target.value)}
                                    required
                                    className="flex h-10 w-full rounded-lg border border-input bg-card px-4 py-2 text-sm focus:border-champagne focus:outline-none focus:ring-2 focus:ring-champagne/20"
                                >
                                    <option value="">— Sélectionner —</option>
                                    {roles.map((r) => (
                                        <option key={r} value={r}>
                                            {r}
                                        </option>
                                    ))}
                                </select>
                            </Field>
                            <Field
                                label="Affiliation / équipe"
                                hint="Club, sélection, média…"
                                error={errors.interviewee_affiliation}
                                className="sm:col-span-2"
                            >
                                <Input
                                    value={data.interviewee_affiliation ?? ''}
                                    onChange={(e) => setData('interviewee_affiliation', e.target.value)}
                                    placeholder="Sélection nationale du Maroc"
                                />
                            </Field>
                        </div>
                    </div>

                    {/* Media */}
                    <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                        <SectionTitle title="Média" />
                        <div className="mt-4 space-y-5">
                            <Field
                                label="URL vidéo (YouTube / Vimeo)"
                                hint="Optionnel. Sera embarquée sous l'interview."
                                error={errors.video_url}
                            >
                                <Input
                                    type="url"
                                    value={data.video_url ?? ''}
                                    onChange={(e) => setData('video_url', e.target.value)}
                                    placeholder="https://www.youtube.com/watch?v=…"
                                />
                            </Field>
                        </div>
                    </div>

                    <div className="flex items-center justify-end gap-3">
                        <Button asChild variant="outline" type="button">
                            <Link href={route('admin.interviews.index')}>Annuler</Link>
                        </Button>
                        <Button type="submit" size="lg" disabled={processing}>
                            {processing ? (
                                <Loader2 className="h-4 w-4 animate-spin" />
                            ) : (
                                <Save className="h-4 w-4" />
                            )}
                            {isEdit ? 'Enregistrer' : 'Créer l\'interview'}
                        </Button>
                    </div>
                </motion.div>

                {/* Sidebar */}
                <motion.aside
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ delay: 0.05 }}
                    className="space-y-6"
                >
                    {/* Publication */}
                    <div className="rounded-2xl border border-champagne/20 bg-card p-6">
                        <SectionTitle title="Publication" icon={isPublished ? Eye : EyeOff} />
                        <div className="mt-4 space-y-3">
                            <Field label="Date de publication" hint="Vide = brouillon" error={errors.published_at}>
                                <Input
                                    type="datetime-local"
                                    value={data.published_at ?? ''}
                                    onChange={(e) => setData('published_at', e.target.value)}
                                />
                            </Field>
                            {!isPublished && (
                                <button
                                    type="button"
                                    onClick={publishNow}
                                    className="w-full rounded-lg border border-champagne/30 bg-champagne/10 py-2 text-xs font-semibold uppercase tracking-widest text-champagne hover:bg-champagne/20"
                                >
                                    Publier maintenant
                                </button>
                            )}
                            {isPublished && (
                                <button
                                    type="button"
                                    onClick={() => setData('published_at', '')}
                                    className="w-full rounded-lg border border-border py-2 text-xs font-semibold uppercase tracking-widest text-muted-foreground hover:bg-muted"
                                >
                                    Repasser en brouillon
                                </button>
                            )}
                        </div>
                    </div>

                    {/* Hero image */}
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <SectionTitle title="Image principale" />
                        <div className="mt-4">
                            <div className="aspect-video overflow-hidden rounded-lg border border-border bg-muted">
                                {heroPreview ? (
                                    <img src={heroPreview} alt="" className="h-full w-full object-cover" />
                                ) : (
                                    <div className="flex h-full w-full items-center justify-center">
                                        <Mic className="h-10 w-10 text-champagne/30" />
                                    </div>
                                )}
                            </div>
                            <label className="mt-3 flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm font-medium transition-colors hover:border-champagne/50 hover:text-champagne">
                                <Camera className="h-4 w-4" />
                                {heroPreview ? 'Changer' : 'Téléverser'}
                                <input type="file" accept="image/*" onChange={onHeroChange} className="hidden" />
                            </label>
                            {heroPreview && (
                                <button
                                    type="button"
                                    onClick={() => {
                                        setData('hero_image', null);
                                        setHeroPreview(null);
                                    }}
                                    className="mt-2 flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-xs text-plasma hover:bg-plasma/10"
                                >
                                    <Trash2 className="h-3.5 w-3.5" />
                                    Retirer
                                </button>
                            )}
                            {errors.hero_image && <p className="mt-2 text-xs text-plasma">{errors.hero_image}</p>}
                        </div>
                    </div>

                    {/* Portrait */}
                    <div className="rounded-2xl border border-border bg-card p-6">
                        <SectionTitle title="Portrait de l'invité·e" />
                        <div className="mt-4">
                            <div className="mx-auto aspect-square w-40 overflow-hidden rounded-full border border-border bg-muted">
                                {portraitPreview ? (
                                    <img src={portraitPreview} alt="" className="h-full w-full object-cover" />
                                ) : (
                                    <div className="flex h-full w-full items-center justify-center">
                                        <User className="h-10 w-10 text-muted-foreground/40" />
                                    </div>
                                )}
                            </div>
                            <label className="mt-3 flex w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-border bg-background px-3 py-2 text-sm font-medium transition-colors hover:border-champagne/50 hover:text-champagne">
                                <Camera className="h-4 w-4" />
                                {portraitPreview ? 'Changer' : 'Téléverser'}
                                <input type="file" accept="image/*" onChange={onPortraitChange} className="hidden" />
                            </label>
                            {portraitPreview && (
                                <button
                                    type="button"
                                    onClick={() => {
                                        setData('interviewee_photo', null);
                                        setPortraitPreview(null);
                                    }}
                                    className="mt-2 flex w-full items-center justify-center gap-2 rounded-lg px-3 py-2 text-xs text-plasma hover:bg-plasma/10"
                                >
                                    <Trash2 className="h-3.5 w-3.5" />
                                    Retirer
                                </button>
                            )}
                            {errors.interviewee_photo && (
                                <p className="mt-2 text-xs text-plasma">{errors.interviewee_photo}</p>
                            )}
                        </div>
                    </div>

                    {progress && (
                        <div className="h-1 overflow-hidden rounded-full bg-muted">
                            <div
                                className="h-full bg-champagne transition-all"
                                style={{ width: `${progress.percentage}%` }}
                            />
                        </div>
                    )}
                </motion.aside>
            </form>
        </AdminLayout>
    );
}

function SectionTitle({
    title,
    icon: Icon,
}: {
    title: string;
    icon?: React.ComponentType<{ className?: string }>;
}) {
    return (
        <div className="flex items-center gap-2 font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne">
            {Icon && <Icon className="h-3 w-3" />}
            {title}
        </div>
    );
}
