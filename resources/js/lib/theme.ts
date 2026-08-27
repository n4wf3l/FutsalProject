import { useCallback, useEffect, useState } from 'react';

export type Theme = 'dark' | 'light';

const STORAGE_KEY = 'theme';

function readInitial(): Theme {
    if (typeof window === 'undefined') return 'dark';
    const stored = localStorage.getItem(STORAGE_KEY) as Theme | null;
    if (stored) return stored;
    return document.documentElement.classList.contains('dark') ? 'dark' : 'light';
}

export function useTheme() {
    const [theme, setThemeState] = useState<Theme>(readInitial);

    useEffect(() => {
        const root = document.documentElement;
        root.classList.toggle('dark', theme === 'dark');
        try {
            localStorage.setItem(STORAGE_KEY, theme);
        } catch {}
    }, [theme]);

    const toggle = useCallback(() => {
        setThemeState((t) => (t === 'dark' ? 'light' : 'dark'));
    }, []);

    const setTheme = useCallback((t: Theme) => setThemeState(t), []);

    return { theme, toggle, setTheme };
}
