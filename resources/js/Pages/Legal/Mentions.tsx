import { Link, usePage } from '@inertiajs/react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { SEO } from '@/Components/SEO';
import type { ClubInfoShared } from '@/types/models';

export default function Mentions() {
    const { props } = usePage<{ club: ClubInfoShared }>();
    const club = props.club;

    return (
        <SiteLayout>
            <SEO
                title="Mentions légales"
                description="Mentions légales de Dina Kenitra FC — éditeur, hébergement, contact, propriété intellectuelle."
            />

            <PageHeader
                kicker="Informations légales"
                title="Mentions légales"
                subtitle="Informations relatives à l'éditeur et à l'hébergement du site dinakenitrafc.ma."
                breadcrumb={[{ label: 'Accueil', href: '/' }, { label: 'Mentions légales' }]}
                variant="editorial"
            />

            <article className="mx-auto max-w-3xl px-4 pb-16">
                <div className="space-y-10 text-foreground/90">
                    <Section title="Éditeur du site">
                        <dl className="grid gap-2 sm:grid-cols-[140px_1fr]">
                            <Row label="Raison sociale" value="Dina Kenitra Futsal Club" />
                            <Row label="Forme" value="Association sportive de droit marocain" />
                            <Row label="Fondation" value="2011" />
                            <Row label="Siège" value={`${club?.location ?? 'Complexe Sportif Municipal'}, ${club?.city ?? 'Kénitra'}, Maroc`} />
                            {club?.president && <Row label="Président" value={club.president} />}
                            <Row
                                label="Email"
                                value={
                                    <a
                                        href={`mailto:${club?.email ?? 'contact@dinakenitrafc.ma'}`}
                                        className="text-crimson underline hover:no-underline"
                                    >
                                        {club?.email ?? 'contact@dinakenitrafc.ma'}
                                    </a>
                                }
                            />
                            {club?.phone && <Row label="Téléphone" value={club.phone} />}
                        </dl>
                        <p className="mt-4 text-sm text-muted-foreground">
                            Les mentions administratives complètes (RC, IF, ICE, numéro CNSS le
                            cas échéant) sont disponibles sur simple demande écrite au siège du
                            club.
                        </p>
                    </Section>

                    <Section title="Directeur de la publication">
                        <p>
                            Le directeur de la publication est le président en exercice de Dina
                            Kenitra FC{club?.president ? `, ${club.president}` : ''}.
                        </p>
                    </Section>

                    <Section title="Hébergement">
                        <p>
                            Le site est hébergé sur des serveurs situés au Maroc. Le nom de
                            domaine <strong>dinakenitrafc.ma</strong> est enregistré auprès
                            de l'ANRT (Agence Nationale de Réglementation des
                            Télécommunications).
                        </p>
                    </Section>

                    <Section title="Propriété intellectuelle">
                        <p>
                            L'ensemble du site (crest, textes, photographies, vidéos, articles,
                            interviews, code source) est protégé par le droit d'auteur marocain
                            et les conventions internationales. Toute reproduction, même
                            partielle, doit faire l'objet d'une autorisation écrite préalable
                            de Dina Kenitra FC.
                        </p>
                        <p>
                            La reprise de citations issues des articles ou interviews est
                            autorisée à condition d'indiquer clairement la source « Dina Kenitra
                            FC — La Voix du Futsal » et de fournir un lien vers l'article
                            original.
                        </p>
                    </Section>

                    <Section title="Données personnelles">
                        <p>
                            Le traitement des données personnelles est encadré par la{' '}
                            <strong>Loi n° 09-08</strong> relative à la protection des personnes
                            physiques à l'égard du traitement des données à caractère
                            personnel. Le détail figure dans notre{' '}
                            <Link
                                href="/confidentialite"
                                className="text-crimson underline hover:no-underline"
                            >
                                politique de confidentialité
                            </Link>
                            .
                        </p>
                    </Section>

                    <Section title="Cookies">
                        <p>
                            Le site utilise uniquement des cookies techniques nécessaires à son
                            fonctionnement (session, protection CSRF, préférence de thème
                            clair/sombre). Aucun cookie publicitaire ni de traçage tiers n'est
                            déposé.
                        </p>
                    </Section>

                    <Section title="Limitation de responsabilité">
                        <p>
                            Dina Kenitra FC met tout en œuvre pour que les informations
                            publiées soient exactes et à jour, sans garantie d'exhaustivité.
                            Les liens externes sont fournis à titre indicatif ; nous ne
                            saurions être tenus responsables du contenu des sites tiers.
                        </p>
                    </Section>

                    <Section title="Droit applicable">
                        <p>
                            Le présent site est régi par le droit marocain. Tout litige
                            relatif à son utilisation relève de la compétence des tribunaux
                            marocains.
                        </p>
                    </Section>

                    <p className="border-t border-border pt-6 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                        Dernière mise à jour : {new Date().toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })}
                    </p>
                </div>
            </article>
        </SiteLayout>
    );
}

function Section({ title, children }: { title: string; children: React.ReactNode }) {
    return (
        <section>
            <h2 className="mb-4 font-editorial text-2xl font-medium leading-tight text-foreground">
                {title}
            </h2>
            <div className="space-y-3 leading-relaxed text-muted-foreground">{children}</div>
        </section>
    );
}

function Row({ label, value }: { label: string; value: React.ReactNode }) {
    return (
        <>
            <dt className="font-mono text-[10px] font-semibold uppercase tracking-widest text-champagne">
                {label}
            </dt>
            <dd className="font-medium text-foreground">{value}</dd>
        </>
    );
}
