import { useMemo } from 'react';
import { useTranslation } from 'react-i18next';
import { SEO } from '@/Components/SEO';
import { Users } from 'lucide-react';
import SiteLayout from '@/Layouts/SiteLayout';
import { PageHeader } from '@/Components/site/PageHeader';
import { EmptyState } from '@/Components/site/EmptyState';
import { PlayerCard } from '@/Components/site/PlayerCard';
import type { PlayerEspoir } from '@/types/models';

interface Props {
    players: PlayerEspoir[];
}

const POSITION_GROUPS = [
    { key: 'gk', match: (p: PlayerEspoir) => /gardien|goal|gk/i.test(p.position) },
    { key: 'fixo', match: (p: PlayerEspoir) => /fixe|fixo|d[éeè]f|defen/i.test(p.position) },
    { key: 'ala', match: (p: PlayerEspoir) => /ala|ail|winger/i.test(p.position) },
    { key: 'pivot', match: (p: PlayerEspoir) => /piv|attaqu|forward|target|striker/i.test(p.position) },
] as const;

export default function Espoirs({ players }: Props) {
    const { t, i18n } = useTranslation('pages');

    const sections = useMemo(() => {
        const sorted = [...players].sort((a, b) => a.number - b.number);
        return POSITION_GROUPS
            .map(({ key, match }) => ({ key, items: sorted.filter(match) }))
            .filter((s) => s.items.length > 0);
    }, [players]);

    return (
        <SiteLayout>
            <SEO
                title={t('espoirs.seo_title')}
                description={t('espoirs.seo_description')}
            />

            <PageHeader
                kicker={t('espoirs.kicker')}
                kickerRight={new Date().toLocaleDateString(i18n.language, { day: '2-digit', month: 'long', year: 'numeric' })}
                title={t('espoirs.title')}
                subtitle={t('espoirs.subtitle')}
                variant="editorial"
            />

            <section className="mx-auto max-w-7xl px-4 pb-12 pt-8 sm:pt-12">
                {sections.length === 0 ? (
                    <EmptyState
                        icon={Users}
                        title={t('espoirs.empty_title')}
                        description={t('espoirs.empty_description')}
                    />
                ) : (
                    <div className="space-y-10 sm:space-y-14">
                        {sections.map((section) => (
                            <div key={section.key}>
                                <div className="mb-5 flex items-baseline justify-between border-b border-border pb-3 sm:mb-6">
                                    <h2 className="font-mono text-xs font-semibold uppercase tracking-[0.3em] text-champagne sm:text-sm">
                                        {t(`teams.positions.${section.key}`)}
                                    </h2>
                                    <span className="font-mono text-[11px] text-muted-foreground">
                                        {section.items.length}
                                    </span>
                                </div>
                                <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                                    {section.items.map((p, i) => (
                                        <PlayerCard key={p.id} player={p} index={i} />
                                    ))}
                                </div>
                            </div>
                        ))}
                    </div>
                )}
            </section>
        </SiteLayout>
    );
}
