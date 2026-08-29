import { Head, usePage } from '@inertiajs/react';

const SITE_NAME = 'Dina Kenitra FC';
const DEFAULT_DESCRIPTION =
    'Dina Kenitra Futsal Club. Club de futsal de la ville de Kénitra depuis 2011. Calendrier, résultats, effectif et actualités.';
const DEFAULT_IMAGE = '/logo-dinakenitra.png';

interface Props {
    /** Page title (excluding the site name suffix, added automatically) */
    title?: string;
    /** Meta description (~150-160 chars ideal) */
    description?: string;
    /** OG image URL (absolute or root-relative /storage/... path) */
    image?: string | null;
    /** OG type: website | article | profile | video.other */
    type?: 'website' | 'article' | 'profile' | 'video.other';
    /** ISO 8601 date for og:article:published_time */
    publishedAt?: string | null;
    /** ISO 8601 date for og:article:modified_time */
    modifiedAt?: string | null;
    /** Author name for og:article:author */
    author?: string | null;
    /** Article section (category) for og:article:section */
    section?: string | null;
    /** Optional structured data to inject (already-serialized JSON object) */
    jsonLd?: object | null;
    /** Optional: mark as noindex (e.g. drafts, admin) */
    noindex?: boolean;
}

/**
 * Central SEO component. Overrides Blade defaults for the current page.
 * Handles title, description, canonical, Open Graph, Twitter Card,
 * article metadata and optional JSON-LD structured data.
 */
export function SEO({
    title,
    description,
    image,
    type = 'website',
    publishedAt,
    modifiedAt,
    author,
    section,
    jsonLd,
    noindex,
}: Props) {
    const { url } = usePage();
    const origin =
        typeof window !== 'undefined' ? window.location.origin : 'https://dinakenitrafc.ma';
    const canonical = `${origin}${url}`;

    const fullTitle = title ? `${title} · ${SITE_NAME}` : SITE_NAME;
    const desc = description ?? DEFAULT_DESCRIPTION;

    const resolvedImage = image
        ? image.startsWith('http')
            ? image
            : `${origin}${image.startsWith('/') ? image : `/storage/${image}`}`
        : `${origin}${DEFAULT_IMAGE}`;

    return (
        <Head title={fullTitle}>
            <meta name="description" content={desc} head-key="description" />
            <link rel="canonical" href={canonical} head-key="canonical" />

            {noindex && <meta name="robots" content="noindex, nofollow" head-key="robots" />}

            {/* Open Graph */}
            <meta property="og:title" content={fullTitle} head-key="og:title" />
            <meta property="og:description" content={desc} head-key="og:description" />
            <meta property="og:url" content={canonical} head-key="og:url" />
            <meta property="og:type" content={type} head-key="og:type" />
            <meta property="og:image" content={resolvedImage} head-key="og:image" />
            <meta property="og:image:alt" content={title ?? SITE_NAME} head-key="og:image:alt" />

            {/* Article-specific OG */}
            {type === 'article' && publishedAt && (
                <meta
                    property="article:published_time"
                    content={publishedAt}
                    head-key="article:published_time"
                />
            )}
            {type === 'article' && modifiedAt && (
                <meta
                    property="article:modified_time"
                    content={modifiedAt}
                    head-key="article:modified_time"
                />
            )}
            {type === 'article' && author && (
                <meta property="article:author" content={author} head-key="article:author" />
            )}
            {type === 'article' && section && (
                <meta property="article:section" content={section} head-key="article:section" />
            )}

            {/* Twitter Card */}
            <meta name="twitter:title" content={fullTitle} head-key="twitter:title" />
            <meta name="twitter:description" content={desc} head-key="twitter:description" />
            <meta name="twitter:image" content={resolvedImage} head-key="twitter:image" />

            {/* Structured data */}
            {jsonLd && (
                <script
                    type="application/ld+json"
                    // eslint-disable-next-line react/no-danger
                    dangerouslySetInnerHTML={{ __html: JSON.stringify(jsonLd) }}
                    head-key="ld+json"
                />
            )}
        </Head>
    );
}
