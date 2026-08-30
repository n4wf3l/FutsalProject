import { useTranslation } from 'react-i18next';
import { cn } from '@/lib/utils';

export function Logo({ className, showText = true }: { className?: string; showText?: boolean }) {
    const { t } = useTranslation('common');
    return (
        <div className={cn('flex items-center gap-3', className)}>
            <div className="relative">
                <div className="absolute inset-0 rounded-full bg-crimson/30 blur-xl" aria-hidden />
                <img
                    src="/logo-dinakenitra.png"
                    alt="Dina Kenitra FC"
                    className="relative h-10 w-10 object-contain drop-shadow-[0_2px_8px_rgba(168,26,31,0.35)]"
                />
            </div>
            {showText && (
                <div className="flex flex-col leading-none">
                    <span className="font-display text-sm font-bold tracking-widest text-foreground">
                        {t('brand.club_name').toUpperCase()}
                    </span>
                    <span className="font-mono text-[10px] font-medium uppercase tracking-[0.3em] text-champagne">
                        {t('brand.futsal_year')}
                    </span>
                </div>
            )}
        </div>
    );
}
