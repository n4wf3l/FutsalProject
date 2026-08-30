import { Link } from '@inertiajs/react';
import { motion } from 'framer-motion';
import { CheckCircle2, ShieldCheck } from 'lucide-react';
import { useTranslation } from 'react-i18next';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { SEO } from '@/Components/SEO';

export default function Privacy() {
    const { t } = useTranslation(['legal', 'nav']);
    return (
        <SiteLayout>
            <SEO
                title={t('legal:privacy.title')}
                description={t('legal:privacy.subtitle')}
            />

            <PageHeader
                kicker={t('legal:privacy.kicker')}
                title={t('legal:privacy.title')}
                subtitle={t('legal:privacy.subtitle')}
                breadcrumb={[{ label: t('nav:items.home'), href: '/' }, { label: t('legal:privacy.breadcrumb') }]}
                variant="editorial"
            />

            <article className="mx-auto max-w-3xl px-4 pb-16">
                <motion.div
                    initial={{ opacity: 0, y: 12 }}
                    animate={{ opacity: 1, y: 0 }}
                    transition={{ duration: 0.5 }}
                    className="mb-8 flex items-start gap-3 rounded-2xl border border-champagne/30 bg-card p-5"
                >
                    <ShieldCheck className="mt-0.5 h-5 w-5 shrink-0 text-champagne" />
                    <p className="text-sm leading-relaxed text-muted-foreground">
                        {t('legal:privacy.intro', {
                            law: t('legal:privacy.intro_law'),
                            authority: t('legal:privacy.intro_authority'),
                        })}
                    </p>
                </motion.div>

                <div className="space-y-10 text-foreground/90">
                    <Section title={t('legal:privacy.sections.responsible')}>
                        <p>
                            Le responsable du traitement est <strong>Dina Kenitra Futsal Club</strong>,
                            association sportive de droit marocain établie à Kénitra, joignable à{' '}
                            <a href="mailto:contact@dinakenitrafc.ma" className="text-crimson underline hover:no-underline">
                                contact@dinakenitrafc.ma
                            </a>
                            . Pour toute question sur cette politique, écris-nous en précisant
                            « Données personnelles » en objet.
                        </p>
                    </Section>

                    <Section title={t('legal:privacy.sections.collected')}>
                        <ul className="ml-5 list-disc space-y-2">
                            <li>
                                <strong>Formulaire de contact</strong> : prénom, nom, email,
                                téléphone, message.
                            </li>
                            <li>
                                <strong>Formulaire de candidature (« Rejoindre le club »)</strong> :
                                identité, coordonnées, date de naissance, nationalité, ville,
                                catégorie (junior / féminine / senior masculine), poste préféré,
                                club actuel, années d'expérience, message libre, et un CV
                                (PDF, JPG ou PNG).
                            </li>
                            <li>
                                <strong>Espace staff / admin</strong> : email et mot de passe
                                haché avec bcrypt (jamais stocké en clair, jamais réversible),
                                réservés au personnel du club.
                            </li>
                            <li>
                                <strong>Billetterie</strong> : les données de paiement sont
                                traitées par Stripe (jamais stockées sur nos serveurs).
                            </li>
                            <li>
                                <strong>Cookies techniques</strong> : uniquement session Laravel
                                et protection CSRF, aucun cookie publicitaire ni de traçage.
                            </li>
                        </ul>
                    </Section>

                    <Section title={t('legal:privacy.sections.purposes')}>
                        <ul className="ml-5 list-disc space-y-2">
                            <li>Répondre aux messages envoyés via le formulaire de contact.</li>
                            <li>
                                Évaluer les candidatures des joueurs et coordonner les essais
                                sportifs.
                            </li>
                            <li>Gérer les comptes du staff et l'administration du site.</li>
                            <li>Fournir les tickets de match achetés en billetterie.</li>
                            <li>
                                Assurer la sécurité technique du site (prévention de la fraude,
                                protection anti-spam).
                            </li>
                        </ul>
                    </Section>

                    <Section title={t('legal:privacy.sections.basis')}>
                        <p>
                            La base légale du traitement est ton <strong>consentement libre,
                            spécifique et éclairé</strong>, matérialisé par la case à cocher
                            présente dans chaque formulaire. Pour les mineurs, le consentement
                            d'un parent ou tuteur légal est également requis.
                        </p>
                    </Section>

                    <Section title={t('legal:privacy.sections.retention')}>
                        <ul className="ml-5 list-disc space-y-2">
                            <li>
                                <strong>Candidatures joueurs non retenues</strong> : 6 mois à
                                compter de la décision, puis suppression automatique du dossier
                                et du CV.
                            </li>
                            <li>
                                <strong>Candidatures retenues devenues joueurs du club</strong> :
                                conservées pendant la durée du contrat, plus 3 ans (obligations
                                administratives).
                            </li>
                            <li>
                                <strong>Messages de contact</strong> : non archivés dans notre
                                base de données. Ils sont transmis par email au staff, qui peut
                                les conserver dans sa boîte de réception selon ses besoins
                                opérationnels et sa propre politique de rétention.
                            </li>
                            <li>
                                <strong>Comptes staff</strong> : durée de la mission au sein du
                                club.
                            </li>
                            <li>
                                <strong>Logs techniques et cookies de session</strong> : 30 jours
                                maximum.
                            </li>
                        </ul>
                    </Section>

                    <Section title={t('legal:privacy.sections.access')}>
                        <p>
                            Uniquement le staff sportif et administratif de Dina Kenitra FC,
                            dûment autorisé. Nous <strong>ne vendons ni ne partageons jamais</strong>{' '}
                            tes données avec des tiers à des fins commerciales. Les seuls
                            sous-traitants techniques (hébergement du site, service email,
                            plateforme de paiement Stripe) sont soumis à des engagements de
                            confidentialité et de sécurité.
                        </p>
                    </Section>

                    <Section title={t('legal:privacy.sections.security')}>
                        <ul className="ml-5 list-disc space-y-2">
                            <li>Transport HTTPS (chiffrement TLS) sur tout le site.</li>
                            <li>Mots de passe stockés avec bcrypt (jamais en clair).</li>
                            <li>
                                Accès aux CV et candidatures restreint aux comptes staff
                                authentifiés.
                            </li>
                            <li>
                                Sauvegardes régulières de la base de données côté serveur.
                            </li>
                        </ul>
                    </Section>

                    <Section title={t('legal:privacy.sections.rights')}>
                        <p className="mb-3">
                            Conformément à la Loi 09-08, tu disposes à tout moment des droits
                            suivants :
                        </p>
                        <ul className="ml-5 list-disc space-y-2">
                            <li>
                                <strong>Droit d'accès</strong> : savoir quelles données te
                                concernent.
                            </li>
                            <li>
                                <strong>Droit de rectification</strong> : corriger une donnée
                                inexacte.
                            </li>
                            <li>
                                <strong>Droit d'opposition</strong> : t'opposer, pour motif
                                légitime, au traitement.
                            </li>
                            <li>
                                <strong>Droit à la suppression</strong> : demander l'effacement
                                définitif de tes données.
                            </li>
                            <li>
                                <strong>Droit de retrait du consentement</strong> à tout moment,
                                sans effet rétroactif.
                            </li>
                        </ul>
                        <div className="mt-6 rounded-2xl border border-crimson/20 bg-card p-5">
                            <div className="flex items-start gap-3">
                                <CheckCircle2 className="mt-0.5 h-5 w-5 shrink-0 text-crimson" />
                                <div className="text-sm">
                                    <p className="font-semibold text-foreground">
                                        {t('legal:privacy.how_to_exercise')}
                                    </p>
                                    <p className="mt-2 text-muted-foreground">
                                        {t('legal:privacy.how_body_prefix')}{' '}
                                        <a
                                            href="mailto:contact@dinakenitrafc.ma?subject=Donn%C3%A9es%20personnelles"
                                            className="text-crimson underline hover:no-underline"
                                        >
                                            contact@dinakenitrafc.ma
                                        </a>
                                        {' '}{t('legal:privacy.how_body_middle')}
                                    </p>
                                    <p className="mt-3 text-muted-foreground">
                                        {t('legal:privacy.how_body_suffix_prefix')}{' '}
                                        <Link
                                            href="/candidature/supprimer"
                                            className="text-crimson underline hover:no-underline"
                                        >
                                            {t('legal:privacy.how_body_link')}
                                        </Link>{' '}
                                        {t('legal:privacy.how_body_suffix_end')}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </Section>

                    <Section title={t('legal:privacy.sections.cndp')}>
                        <p>
                            Si tu estimes que tes droits ne sont pas respectés, tu peux
                            introduire une réclamation auprès de la Commission Nationale de
                            contrôle de la protection des Données à caractère Personnel :{' '}
                            <a
                                href="https://www.cndp.ma"
                                target="_blank"
                                rel="noopener noreferrer"
                                className="text-crimson underline hover:no-underline"
                            >
                                cndp.ma
                            </a>
                            .
                        </p>
                    </Section>

                    <Section title={t('legal:privacy.sections.minors')}>
                        <p>
                            Pour les candidats de moins de 18 ans, nous demandons le consentement
                            d'un parent ou tuteur légal au moment de l'envoi du formulaire de
                            candidature. Aucune donnée d'un mineur n'est traitée sans cet accord.
                            Les parents peuvent à tout moment consulter, corriger ou supprimer
                            les données de leur enfant.
                        </p>
                    </Section>

                    <Section title={t('legal:privacy.sections.transfers')}>
                        <p>
                            Nos serveurs et sauvegardes sont hébergés au Maroc. Certains outils
                            techniques (envoi d'email, paiement) peuvent transiter par des
                            infrastructures situées dans l'Union européenne, sous protection
                            équivalente à la Loi 09-08.
                        </p>
                    </Section>

                    <Section title={t('legal:privacy.sections.updates')}>
                        <p>
                            Cette politique peut évoluer pour rester conforme à la
                            réglementation ou refléter des changements dans nos traitements.
                            La date de dernière mise à jour figure ci-dessous. Aucun
                            changement majeur ne sera appliqué sans t'en informer par email
                            si tu as un compte ou une candidature active.
                        </p>
                    </Section>

                    <p className="border-t border-border pt-6 font-mono text-xs uppercase tracking-widest text-muted-foreground">
                        {t('legal:privacy.last_update')} {new Date().toLocaleDateString('fr-FR', { day: '2-digit', month: 'long', year: 'numeric' })}
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
