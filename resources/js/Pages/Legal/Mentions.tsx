import { Link, usePage } from '@inertiajs/react';
import { useTranslation } from 'react-i18next';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { SEO } from '@/Components/SEO';
import type { ClubInfoShared } from '@/types/models';

export default function Mentions() {
    const { t } = useTranslation(['legal', 'nav']);
    const { props } = usePage<{ club: ClubInfoShared }>();
    const club = props.club;

    return (
        <SiteLayout>
            <SEO
                title={t('legal:mentions.title')}
                description={t('legal:mentions.subtitle')}
            />

            <PageHeader
                kicker={t('legal:mentions.kicker')}
                title={t('legal:mentions.title')}
                subtitle={t('legal:mentions.subtitle')}
                breadcrumb={[{ label: t('nav:items.home'), href: '/' }, { label: t('legal:mentions.breadcrumb') }]}
                variant="editorial"
            />

            <article className="mx-auto max-w-3xl px-4 pb-16">
                <div className="space-y-10 text-foreground/90">
                    <Section title={t('legal:mentions.sections.editor')}>
                        <dl className="grid gap-2 sm:grid-cols-[140px_1fr]">
                            <Row label={t('legal:mentions.fields.raison_sociale')} value="Dina Kenitra Futsal Club" />
                            <Row label={t('legal:mentions.fields.forme')} value="Association sportive de droit marocain" />
                            <Row label={t('legal:mentions.fields.fondation')} value="2011" />
                            <Row label={t('legal:mentions.fields.siege')} value={`${club?.location ?? 'Complexe Sportif Municipal'}, ${club?.city ?? 'Kénitra'}, Maroc`} />
                            {club?.president && <Row label={t('legal:mentions.fields.president')} value={club.president} />}
                            <Row
                                label={t('legal:mentions.fields.email')}
                                value={
                                    <a
                                        href={`mailto:${club?.email ?? 'contact@dinakenitrafc.ma'}`}
                                        className="text-crimson underline hover:no-underline"
                                    >
                                        {club?.email ?? 'contact@dinakenitrafc.ma'}
                                    </a>
                                }
                            />
                            {club?.phone && <Row label={t('legal:mentions.fields.phone')} value={club.phone} />}
                        </dl>
                        <p className="mt-4 text-sm text-muted-foreground">
                            {t('legal:mentions.editor_note')}
                        </p>
                    </Section>

                    <Section title={t('legal:mentions.sections.director')}>
                        <p>
                            Le directeur de la publication est le président en exercice de Dina
                            Kenitra FC{club?.president ? `, ${club.president}` : ''}.
                        </p>
                    </Section>

                    <Section title={t('legal:mentions.sections.hosting')}>
                        <p>
                            Le site est hébergé sur des serveurs situés au Maroc. Le nom de
                            domaine <strong>dinakenitrafc.ma</strong> est enregistré auprès
                            de l'ANRT (Agence Nationale de Réglementation des
                            Télécommunications).
                        </p>
                    </Section>

                    <Section title={t('legal:mentions.sections.ip')}>
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

                    <Section title={t('legal:mentions.sections.data')}>
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

                    <Section title={t('legal:mentions.sections.cookies')}>
                        <p>
                            Le site utilise uniquement des cookies techniques nécessaires à son
                            fonctionnement (session, protection CSRF, préférence de thème
                            clair/sombre). Aucun cookie publicitaire ni de traçage tiers n'est
                            déposé.
                        </p>
                    </Section>

                    <Section title={t('legal:mentions.sections.liability')}>
                        <p>
                            Dina Kenitra FC met tout en œuvre pour que les informations
                            publiées soient exactes et à jour, sans garantie d'exhaustivité.
                            Les liens externes sont fournis à titre indicatif ; nous ne
                            saurions être tenus responsables du contenu des sites tiers.
                        </p>
                    </Section>

                    <Section title={t('legal:mentions.sections.law')}>
                        <p>
                            Le présent site est régi par le droit marocain. Tout litige
                            relatif à son utilisation relève de la compétence des tribunaux
                            marocains.
                        </p>
                    </Section>

                    <p className="border-t border-border pt-6 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                        {t('legal:mentions.last_update')} {new Date().toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })}
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
