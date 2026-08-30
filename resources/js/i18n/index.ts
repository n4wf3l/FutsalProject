import i18n from 'i18next';
import { initReactI18next } from 'react-i18next';

// Namespaces: split by feature so we can lazy-load later if needed.
// For now everything is eagerly imported (small enough).
import frCommon from './locales/fr/common.json';
import frNav from './locales/fr/nav.json';
import frHome from './locales/fr/home.json';
import frAuth from './locales/fr/auth.json';
import frJoin from './locales/fr/join.json';
import frLegal from './locales/fr/legal.json';
import frContact from './locales/fr/contact.json';
import frPages from './locales/fr/pages.json';

import enCommon from './locales/en/common.json';
import enNav from './locales/en/nav.json';
import enHome from './locales/en/home.json';
import enAuth from './locales/en/auth.json';
import enJoin from './locales/en/join.json';
import enLegal from './locales/en/legal.json';
import enContact from './locales/en/contact.json';
import enPages from './locales/en/pages.json';

import arCommon from './locales/ar/common.json';
import arNav from './locales/ar/nav.json';
import arHome from './locales/ar/home.json';
import arAuth from './locales/ar/auth.json';
import arJoin from './locales/ar/join.json';
import arLegal from './locales/ar/legal.json';
import arContact from './locales/ar/contact.json';
import arPages from './locales/ar/pages.json';

export const SUPPORTED_LOCALES = ['fr', 'en', 'ar'] as const;
export type Locale = (typeof SUPPORTED_LOCALES)[number];

export const LOCALE_META: Record<Locale, {
    label: string;
    native: string;
    dir: 'ltr' | 'rtl';
    greeting: string;
    tagline: string;
}> = {
    fr: {
        label: 'Français',
        native: 'FR',
        dir: 'ltr',
        greeting: 'Bonjour',
        tagline: 'Le club en français',
    },
    en: {
        label: 'English',
        native: 'EN',
        dir: 'ltr',
        greeting: 'Hello',
        tagline: 'The club in English',
    },
    ar: {
        label: 'العربية',
        native: 'AR',
        dir: 'rtl',
        greeting: 'مرحبا',
        tagline: 'النادي بالعربية',
    },
};

export function isRtl(locale: string): boolean {
    return LOCALE_META[locale as Locale]?.dir === 'rtl';
}

const resources = {
    fr: {
        common: frCommon,
        nav: frNav,
        home: frHome,
        auth: frAuth,
        join: frJoin,
        legal: frLegal,
        contact: frContact,
        pages: frPages,
    },
    en: {
        common: enCommon,
        nav: enNav,
        home: enHome,
        auth: enAuth,
        join: enJoin,
        legal: enLegal,
        contact: enContact,
        pages: enPages,
    },
    ar: {
        common: arCommon,
        nav: arNav,
        home: arHome,
        auth: arAuth,
        join: arJoin,
        legal: arLegal,
        contact: arContact,
        pages: arPages,
    },
};

// Detect initial locale.
// Server-side (SSR): global.__initialLocale is populated from Inertia shared props.
// Client-side: document has data-locale set from the same source.
function detectInitialLocale(): Locale {
    if (typeof document !== 'undefined') {
        const fromDoc = document.documentElement.getAttribute('data-locale');
        if (fromDoc && SUPPORTED_LOCALES.includes(fromDoc as Locale)) {
            return fromDoc as Locale;
        }
    }
    return 'fr';
}

i18n.use(initReactI18next).init({
    resources,
    lng: detectInitialLocale(),
    fallbackLng: 'fr',
    defaultNS: 'common',
    ns: ['common', 'nav', 'home', 'auth', 'join', 'legal', 'contact', 'pages'],
    interpolation: { escapeValue: false },
    react: { useSuspense: false },
});

export default i18n;

/**
 * Switch language on both i18next and the server (via cookie POST).
 * Also updates <html lang> and dir attributes immediately.
 */
export async function switchLocale(locale: Locale) {
    if (typeof document !== 'undefined') {
        document.documentElement.setAttribute('lang', locale);
        document.documentElement.setAttribute('dir', LOCALE_META[locale].dir);
        document.documentElement.setAttribute('data-locale', locale);
    }
    await i18n.changeLanguage(locale);

    if (typeof window !== 'undefined') {
        // Persist server-side so future SSR responses use it.
        try {
            const csrf = document
                .querySelector('meta[name="csrf-token"]')
                ?.getAttribute('content');
            await fetch('/locale', {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Content-Type': 'application/json',
                    Accept: 'application/json',
                    ...(csrf ? { 'X-CSRF-TOKEN': csrf } : {}),
                },
                body: JSON.stringify({ locale }),
            });
        } catch {
            /* silent, cookie will be set on next full nav */
        }
    }
}
