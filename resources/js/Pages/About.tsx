import { usePage } from '@inertiajs/react';
import { SEO } from '@/Components/SEO';
import { motion } from 'framer-motion';
import { Download, FileText, MapPin, Trophy, Users } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { Badge } from '@/Components/ui/Badge';
import type { AboutSection, ClubInfoShared, Regulation } from '@/types/models';

interface Props {
    regulations: Regulation[];
    sections: AboutSection[];
}

export default function About({ regulations, sections }: Props) {
    const { props } = usePage<{ club: ClubInfoShared }>();
    const club = props.club;

    return (
        <SiteLayout>
            <SEO
                title="Le club"
                description="L'histoire de Dina Kenitra Futsal Club depuis sa fondation en 2011. Formation, ambitions et parcours d'un club engagé à Kénitra."
            />

            <PageHeader
                kicker="Fondé à Kénitra · 2011"
                title="L'histoire d'un club"
                subtitle="Comment un projet local est devenu une école, une famille, une ambition. Quinze ans en quelques chapitres."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Le club' }]}
                variant="editorial"
            />

            {/* Identity block */}
            <section className="mx-auto max-w-7xl px-4 pb-8">
                <div className="relative overflow-hidden rounded-3xl border border-champagne/20 bg-card p-8 lg:p-12">
                    <div className="absolute -right-16 -top-16 h-64 w-64 rounded-full bg-crimson/20 blur-3xl" aria-hidden />
                    <div className="absolute inset-0 bg-noise opacity-[0.04]" aria-hidden />

                    <div className="relative grid gap-8 lg:grid-cols-[auto_1fr] lg:items-center">
                        <img
                            src="/logo-dinakenitra.png"
                            alt="Dina Kenitra FC"
                            className="h-40 w-40 drop-shadow-[0_8px_24px_rgba(168,26,31,0.4)]"
                        />
                        <div>
                            <Badge variant="champagne" className="mb-4">
                                <Trophy className="h-3 w-3" />
                                Fondé en 2011
                            </Badge>
                            <h2 className="text-foreground">
                                <span className="block font-display text-display-lg">Dina Kenitra</span>
                                <span className="block font-editorial text-4xl italic text-champagne sm:text-5xl">
                                    Futsal Club
                                </span>
                            </h2>
                            <p className="mt-4 max-w-2xl leading-relaxed text-muted-foreground">
                                Club de futsal basé à {club?.city ?? 'Kénitra'}, engagé dans le championnat marocain
                                depuis plus d'une décennie. Une équipe, une famille, une identité forte.
                            </p>

                            <div className="mt-6 grid gap-3 sm:grid-cols-3">
                                {club?.president && (
                                    <InfoStrip icon={Users} label="Président" value={club.president} />
                                )}
                                <InfoStrip
                                    icon={MapPin}
                                    label="Ville"
                                    value={club?.city ?? 'Kénitra, Maroc'}
                                />
                                <InfoStrip icon={Trophy} label="Depuis" value="2011" />
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {/* About sections */}
            {sections.length > 0 && (
                <section className="mx-auto max-w-4xl px-4 py-16">
                    <div className="space-y-14">
                        {sections.map((section, i) => (
                            <motion.article
                                key={section.id}
                                initial={{ opacity: 0, y: 20 }}
                                whileInView={{ opacity: 1, y: 0 }}
                                viewport={{ once: true, margin: '-40px' }}
                                transition={{ duration: 0.5, delay: (i % 4) * 0.06 }}
                            >
                                <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                                    Chapitre {String(i + 1).padStart(2, '0')}
                                </div>
                                <h3 className="mt-3 font-display text-display-lg text-foreground">
                                    {section.title}
                                </h3>
                                <div
                                    className="prose-content mt-6 text-lg leading-relaxed text-foreground/80 [&>p]:mb-4"
                                    dangerouslySetInnerHTML={{ __html: section.content }}
                                />
                            </motion.article>
                        ))}
                    </div>
                </section>
            )}

            {/* Regulations */}
            <section className="mx-auto max-w-4xl px-4 pb-16">
                <div className="mb-8">
                    <div className="font-mono text-[11px] uppercase tracking-[0.18em] text-champagne">
                        Documents officiels
                    </div>
                    <h2 className="mt-3 font-display text-display-lg">Règlements</h2>
                </div>

                {regulations.length === 0 ? (
                    <EmptyState
                        icon={FileText}
                        title="Aucun document disponible"
                        description="Les règlements du club seront publiés ici."
                    />
                ) : (
                    <ul className="grid gap-3 sm:grid-cols-2">
                        {regulations.map((reg) => (
                            <li key={reg.id}>
                                <a
                                    href={`/storage/${reg.pdf_path}`}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="group flex items-center gap-4 rounded-2xl border border-border bg-card p-5 transition-all hover:border-crimson/40"
                                >
                                    <div className="rounded-full border border-border bg-background p-3">
                                        <FileText className="h-4 w-4 text-crimson" />
                                    </div>
                                    <div className="min-w-0 flex-1">
                                        <div className="font-mono text-[10px] uppercase tracking-widest text-champagne">
                                            PDF
                                        </div>
                                        <div className="truncate font-display font-semibold transition-colors group-hover:text-crimson">
                                            {reg.title}
                                        </div>
                                    </div>
                                    <Download className="h-4 w-4 text-muted-foreground transition-colors group-hover:text-crimson" />
                                </a>
                            </li>
                        ))}
                    </ul>
                )}
            </section>
        </SiteLayout>
    );
}

function InfoStrip({
    icon: Icon,
    label,
    value,
}: {
    icon: React.ComponentType<{ className?: string }>;
    label: string;
    value: string;
}) {
    return (
        <div className="rounded-xl border border-border bg-background/40 p-3">
            <div className="flex items-center gap-2 font-mono text-[10px] uppercase tracking-widest text-muted-foreground">
                <Icon className="h-3 w-3 text-champagne" />
                {label}
            </div>
            <div className="mt-1 font-display text-sm font-semibold">{value}</div>
        </div>
    );
}
