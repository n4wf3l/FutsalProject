import { useRef, useState, FormEventHandler } from 'react';
import { useForm } from '@inertiajs/react';
import { AnimatePresence, motion } from 'framer-motion';
import { AlertTriangle, Loader2, Trash2, X } from 'lucide-react';
import { Button } from '@/Components/ui/Button';
import { Input } from '@/Components/ui/Input';
import { Field } from '@/Components/ui/Field';

export default function DeleteUserForm({ className }: { className?: string }) {
    const [open, setOpen] = useState(false);
    const passwordInput = useRef<HTMLInputElement>(null);

    const {
        data,
        setData,
        delete: destroy,
        processing,
        reset,
        errors,
    } = useForm({
        password: '',
    });

    const close = () => {
        setOpen(false);
        reset();
    };

    const submit: FormEventHandler = (e) => {
        e.preventDefault();
        destroy(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => close(),
            onError: () => passwordInput.current?.focus(),
            onFinish: () => reset(),
        });
    };

    return (
        <section className={className}>
            <header>
                <div className="flex items-center gap-2 font-mono text-xs uppercase tracking-[0.3em] text-plasma">
                    <AlertTriangle className="h-3 w-3" />
                    Zone dangereuse
                </div>
                <h2 className="mt-2 font-display text-xl font-bold">Supprimer le compte</h2>
                <p className="mt-1 text-sm text-muted-foreground">
                    Une fois ton compte supprimé, toutes tes données seront perdues. Cette action est
                    irréversible. Sauvegarde ce qui compte avant.
                </p>
            </header>

            <div className="mt-6">
                <Button variant="destructive" onClick={() => setOpen(true)}>
                    <Trash2 className="h-4 w-4" />
                    Supprimer mon compte
                </Button>
            </div>

            <AnimatePresence>
                {open && (
                    <motion.div
                        initial={{ opacity: 0 }}
                        animate={{ opacity: 1 }}
                        exit={{ opacity: 0 }}
                        onClick={close}
                        className="fixed inset-0 z-50 flex items-center justify-center bg-obsidian/80 p-4 backdrop-blur-sm"
                    >
                        <motion.form
                            onSubmit={submit}
                            onClick={(e) => e.stopPropagation()}
                            initial={{ scale: 0.95, opacity: 0 }}
                            animate={{ scale: 1, opacity: 1 }}
                            exit={{ scale: 0.95, opacity: 0 }}
                            transition={{ duration: 0.2 }}
                            className="relative w-full max-w-md overflow-hidden rounded-2xl border border-plasma/30 bg-card p-6 shadow-2xl"
                        >
                            <button
                                type="button"
                                onClick={close}
                                className="absolute right-4 top-4 rounded-lg p-1.5 text-muted-foreground hover:bg-muted hover:text-foreground"
                            >
                                <X className="h-4 w-4" />
                            </button>

                            <div className="flex items-start gap-3">
                                <div className="rounded-full border border-plasma/30 bg-plasma/10 p-2.5">
                                    <AlertTriangle className="h-4 w-4 text-plasma" />
                                </div>
                                <div>
                                    <h3 className="font-display text-lg font-bold">
                                        Confirmer la suppression ?
                                    </h3>
                                    <p className="mt-1 text-sm text-muted-foreground">
                                        Ton mot de passe est nécessaire pour confirmer cette action définitive.
                                    </p>
                                </div>
                            </div>

                            <div className="mt-6">
                                <Field label="Mot de passe" required error={errors.password}>
                                    <Input
                                        ref={passwordInput}
                                        type="password"
                                        value={data.password}
                                        onChange={(e) => setData('password', e.target.value)}
                                        autoFocus
                                        required
                                        placeholder="••••••••"
                                    />
                                </Field>
                            </div>

                            <div className="mt-6 flex justify-end gap-2">
                                <Button type="button" variant="outline" onClick={close}>
                                    Annuler
                                </Button>
                                <Button type="submit" variant="destructive" disabled={processing}>
                                    {processing ? (
                                        <Loader2 className="h-4 w-4 animate-spin" />
                                    ) : (
                                        <Trash2 className="h-4 w-4" />
                                    )}
                                    Supprimer définitivement
                                </Button>
                            </div>
                        </motion.form>
                    </motion.div>
                )}
            </AnimatePresence>
        </section>
    );
}
