import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import animate from 'tailwindcss-animate';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.{ts,tsx}',
    ],
    theme: {
        container: {
            center: true,
            padding: '1.5rem',
            screens: { '2xl': '1400px' },
        },
        extend: {
            colors: {
                border: 'hsl(var(--border) / <alpha-value>)',
                input: 'hsl(var(--input) / <alpha-value>)',
                ring: 'hsl(var(--ring) / <alpha-value>)',
                background: 'hsl(var(--background) / <alpha-value>)',
                foreground: 'hsl(var(--foreground) / <alpha-value>)',
                muted: {
                    DEFAULT: 'hsl(var(--muted) / <alpha-value>)',
                    foreground: 'hsl(var(--muted-foreground) / <alpha-value>)',
                },
                card: {
                    DEFAULT: 'hsl(var(--card) / <alpha-value>)',
                    foreground: 'hsl(var(--card-foreground) / <alpha-value>)',
                },
                popover: {
                    DEFAULT: 'hsl(var(--popover) / <alpha-value>)',
                    foreground: 'hsl(var(--popover-foreground) / <alpha-value>)',
                },
                // Club identity
                crimson: {
                    DEFAULT: 'hsl(var(--crimson) / <alpha-value>)',
                    foreground: 'hsl(var(--crimson-foreground) / <alpha-value>)',
                    50: '#fdf3f3',
                    100: '#fce4e4',
                    200: '#facdce',
                    300: '#f5a9ab',
                    400: '#ec777a',
                    500: '#dd484d',
                    600: '#c92a30',
                    700: '#a81a1f',
                    800: '#8b1a1e',
                    900: '#741b1e',
                    950: '#3f0a0c',
                },
                champagne: {
                    DEFAULT: 'hsl(var(--champagne) / <alpha-value>)',
                    foreground: 'hsl(var(--champagne-foreground) / <alpha-value>)',
                    50: '#fbf8ef',
                    100: '#f5edd0',
                    200: '#ecda9d',
                    300: '#e0c069',
                    400: '#d6a944',
                    500: '#c8a25c',
                    600: '#a97a2d',
                    700: '#875c28',
                    800: '#714a26',
                    900: '#603e25',
                    950: '#372010',
                },
                // Signature accents
                plasma: 'hsl(var(--plasma) / <alpha-value>)',
                mint: 'hsl(var(--mint) / <alpha-value>)',
                amber: 'hsl(var(--amber) / <alpha-value>)',
                // Semantic
                primary: {
                    DEFAULT: 'hsl(var(--primary) / <alpha-value>)',
                    foreground: 'hsl(var(--primary-foreground) / <alpha-value>)',
                },
                secondary: {
                    DEFAULT: 'hsl(var(--secondary) / <alpha-value>)',
                    foreground: 'hsl(var(--secondary-foreground) / <alpha-value>)',
                },
                accent: {
                    DEFAULT: 'hsl(var(--accent) / <alpha-value>)',
                    foreground: 'hsl(var(--accent-foreground) / <alpha-value>)',
                },
                destructive: {
                    DEFAULT: 'hsl(var(--destructive) / <alpha-value>)',
                    foreground: 'hsl(var(--destructive-foreground) / <alpha-value>)',
                },
            },
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
                display: ['"Space Grotesk"', ...defaultTheme.fontFamily.sans],
                mono: ['"JetBrains Mono"', ...defaultTheme.fontFamily.mono],
            },
            fontSize: {
                'display-2xl': ['clamp(3rem, 7vw, 6rem)', { lineHeight: '0.95', letterSpacing: '-0.04em', fontWeight: '700' }],
                'display-xl': ['clamp(2.25rem, 5vw, 4.5rem)', { lineHeight: '1', letterSpacing: '-0.03em', fontWeight: '700' }],
                'display-lg': ['clamp(1.75rem, 3.5vw, 3rem)', { lineHeight: '1.05', letterSpacing: '-0.02em', fontWeight: '600' }],
            },
            borderRadius: {
                lg: 'var(--radius)',
                md: 'calc(var(--radius) - 4px)',
                sm: 'calc(var(--radius) - 8px)',
            },
            boxShadow: {
                'glow-crimson': '0 0 40px -8px hsl(var(--crimson) / 0.6)',
                'glow-champagne': '0 0 32px -6px hsl(var(--champagne) / 0.5)',
                'glow-plasma': '0 0 32px -6px hsl(var(--plasma) / 0.7)',
                'ring-inset': 'inset 0 0 0 1px hsl(var(--border) / 0.6)',
            },
            backgroundImage: {
                'grid-fade': 'radial-gradient(ellipse at top, hsl(var(--crimson) / 0.15), transparent 60%)',
                'noise': "url(\"data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.35'/%3E%3C/svg%3E\")",
            },
            keyframes: {
                'fade-up': {
                    '0%': { opacity: '0', transform: 'translateY(24px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                'scoreboard-flip': {
                    '0%': { transform: 'rotateX(0deg)' },
                    '50%': { transform: 'rotateX(90deg)' },
                    '100%': { transform: 'rotateX(0deg)' },
                },
                'ticker': {
                    '0%': { transform: 'translateX(0)' },
                    '100%': { transform: 'translateX(-50%)' },
                },
                'pulse-plasma': {
                    '0%, 100%': { boxShadow: '0 0 0 0 hsl(var(--plasma) / 0.6)' },
                    '50%': { boxShadow: '0 0 0 12px hsl(var(--plasma) / 0)' },
                },
            },
            animation: {
                'fade-up': 'fade-up 0.6s cubic-bezier(0.22, 1, 0.36, 1) both',
                'ticker': 'ticker 40s linear infinite',
                'pulse-plasma': 'pulse-plasma 2s ease-in-out infinite',
            },
        },
    },
    plugins: [forms, animate],
};
