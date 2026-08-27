import { clsx, type ClassValue } from 'clsx';
import { twMerge } from 'tailwind-merge';

export function cn(...inputs: ClassValue[]) {
    return twMerge(clsx(inputs));
}

export function formatMatchDate(iso: string, locale = 'fr-FR') {
    const d = new Date(iso);
    return {
        day: d.toLocaleDateString(locale, { day: '2-digit' }),
        month: d.toLocaleDateString(locale, { month: 'short' }).toUpperCase().replace('.', ''),
        year: d.getFullYear(),
        time: d.toLocaleTimeString(locale, { hour: '2-digit', minute: '2-digit' }),
        weekday: d.toLocaleDateString(locale, { weekday: 'long' }),
    };
}
