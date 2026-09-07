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

export default function Espoirs({ players }: Props) {
    const { t, i18n } = useTranslation('pages');

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

            <section className="mx-auto max-w-7xl px-4 py-12">
                {players.length > 0 ? (
                    <div className="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4">
                        {players.map((p, i) => (
                            <PlayerCard key={p.id} player={p} index={i} />
                        ))}
                    </div>
                ) : (
                    <EmptyState
                        icon={Users}
                        title={t('espoirs.empty_title')}
                        description={t('espoirs.empty_description')}
                    />
                )}
            </section>
        </SiteLayout>
    );
}
