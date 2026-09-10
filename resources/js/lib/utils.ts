import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';
import i18n from '@/i18n';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

const BCP47: Record<string, string> = {
    fr: 'fr-FR',
    en: 'en-US',
    ar: 'ar-MA',
};

function currentBcp47(): string {
    const short = (i18n.language ?? 'fr').split('-')[0];
    return BCP47[short] ?? 'fr-FR';
}

export function formatMatchDate(iso: string, locale?: string) {
    const bcp = locale ?? currentBcp47();
    const d = new Date(iso);
    return {
        day: d.toLocaleDateString(bcp, { day: '2-digit' }),
        month: d.toLocaleDateString(bcp, { month: 'short' }).toUpperCase().replace('.', ''),
        year: d.getFullYear(),
        time: d.toLocaleTimeString(bcp, { hour: '2-digit', minute: '2-digit' }),
        weekday: d.toLocaleDateString(bcp, { weekday: 'long' }),
    };
}
