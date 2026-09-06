/**
 * SEO helpers for structured data (JSON-LD).
 * Used by the SEO component to enrich pages with schema.org markup that
 * Google surfaces as rich results (breadcrumbs, articles, etc).
 */

type BreadcrumbItem = { label: string; href?: string };

const DEFAULT_ORIGIN = 'https://dinakenitrafc.ma';

function absoluteUrl(pathOrUrl: string): string {
    if (pathOrUrl.startsWith('http://') || pathOrUrl.startsWith('https://')) {
        return pathOrUrl;
    }
    const origin = typeof window !== 'undefined' ? window.location.origin : DEFAULT_ORIGIN;
    return `${origin}${pathOrUrl.startsWith('/') ? pathOrUrl : `/${pathOrUrl}`}`;
}

/**
 * Build a schema.org BreadcrumbList JSON-LD object from a list of items.
 * Items without an href are still listed (typically the current page)
 * but the item URL points to the current page for consistency.
 */
export function breadcrumbLd(items: BreadcrumbItem[]): object {
    const currentUrl =
        typeof window !== 'undefined' ? window.location.href : DEFAULT_ORIGIN;
    return {
        '@context': 'https://schema.org',
        '@type': 'BreadcrumbList',
        itemListElement: items.map((item, index) => ({
            '@type': 'ListItem',
            position: index + 1,
            name: item.label,
            item: item.href ? absoluteUrl(item.href) : currentUrl,
        })),
    };
}

/**
 * Merge several JSON-LD objects into a single @graph payload.
 * Each object should be a standalone schema.org entity (BreadcrumbList,
 * ItemList, NewsArticle, etc.). The @context is hoisted to the outer wrapper
 * and stripped from children to avoid duplication.
 */
export function mergeLd(...items: object[]): object {
    return {
        '@context': 'https://schema.org',
        '@graph': items.map((item) => {
            const { ['@context']: _ignored, ...rest } = item as Record<string, unknown>;
            return rest;
        }),
    };
}
