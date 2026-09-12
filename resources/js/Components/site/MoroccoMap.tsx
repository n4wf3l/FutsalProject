import { useMemo } from 'react';
import { motion } from 'framer-motion';
import { geoMercator, geoPath } from 'd3-geo';
import type { Feature, Polygon } from 'geojson';
import moroccoData from '@/data/morocco.json';
import { cn } from '@/lib/utils';

// Kingdom of Morocco POV (Natural Earth Morocco + Western Sahara unioned via
// @turf/union at build time in scripts). One continuous silhouette, no
// internal border. See resources/js/data/morocco.json.
const moroccoFeature = moroccoData as Feature<Polygon, { name: string; pov?: string }>;

// GPS coordinates are stored as [longitude, latitude] to match GeoJSON spec.
type City = { name: string; lngLat: [number, number] };

const KENITRA: City = { name: 'Kénitra', lngLat: [-6.580203, 34.261008] };

interface Props {
    className?: string;
    /** Optional additional cities to plot. */
    cities?: City[];
    pinColor?: string;
    strokeColor?: string;
    fillColor?: string;
    fillOpacity?: number;
    strokeWidth?: number;
    /** viewBox width in SVG units (aspect ratio is derived from projection). */
    width?: number;
    /** viewBox height in SVG units. */
    height?: number;
    /** Inner padding so the country doesn't touch the SVG edge. */
    padding?: number;
}

export function MoroccoMap({
    className,
    cities = [],
    pinColor = '#DC2626',
    strokeColor = 'currentColor',
    fillColor,
    fillOpacity = 0.16,
    strokeWidth = 1.4,
    width = 600,
    height = 720,
    padding = 12,
}: Props) {
    // Single projection is used for both the country outline and every marker.
    // fitExtent guarantees the silhouette fills the box exactly.
    const { projection, pathD } = useMemo(() => {
        const proj = geoMercator().fitExtent(
            [
                [padding, padding],
                [width - padding, height - padding],
            ],
            moroccoFeature,
        );
        const pathGen = geoPath(proj);
        return { projection: proj, pathD: pathGen(moroccoFeature) ?? '' };
    }, [width, height, padding]);

    const allCities: City[] = useMemo(() => [KENITRA, ...cities], [cities]);

    const fill = fillColor ?? strokeColor;

    return (
        <svg
            viewBox={`0 0 ${width} ${height}`}
            preserveAspectRatio="xMidYMid meet"
            className={cn('block', className)}
            xmlns="http://www.w3.org/2000/svg"
            role="img"
            aria-label="Kénitra sur la carte du Maroc"
        >
            {/* Fill fades in after the outline finishes drawing */}
            <motion.path
                d={pathD}
                fill={fill}
                stroke="none"
                initial={{ fillOpacity: 0 }}
                animate={{ fillOpacity }}
                transition={{ duration: 0.6, delay: 2.4, ease: 'easeOut' }}
            />

            {/* Pencil-stroke outline drawn on mount */}
            <motion.path
                d={pathD}
                fill="none"
                stroke={strokeColor}
                strokeOpacity={0.95}
                strokeWidth={strokeWidth}
                strokeLinejoin="round"
                strokeLinecap="round"
                vectorEffect="non-scaling-stroke"
                initial={{ pathLength: 0 }}
                animate={{ pathLength: 1 }}
                transition={{ duration: 2.6, ease: [0.65, 0, 0.35, 1] }}
            />

            {allCities.map((city, i) => {
                const projected = projection(city.lngLat);
                if (!projected) return null;
                const [cx, cy] = projected;
                return (
                    <motion.g
                        key={city.name}
                        initial={{ opacity: 0, scale: 0.4 }}
                        animate={{ opacity: 1, scale: 1 }}
                        transition={{ duration: 0.5, delay: 2.7 + i * 0.1, ease: [0.22, 1, 0.36, 1] }}
                        style={{ transformOrigin: `${cx}px ${cy}px` }}
                    >
                        <motion.circle
                            cx={cx}
                            cy={cy}
                            r={16}
                            fill={pinColor}
                            initial={{ opacity: 0.4, scale: 0.5 }}
                            animate={{ opacity: [0.4, 0, 0.4], scale: [0.5, 1.8, 0.5] }}
                            transition={{
                                duration: 2.4,
                                repeat: Infinity,
                                ease: 'easeInOut',
                                delay: 2.9 + i * 0.1,
                            }}
                        />
                        <circle cx={cx} cy={cy} r={8} fill={pinColor} />
                        <circle cx={cx} cy={cy} r={3} fill="#fff" fillOpacity={0.9} />
                    </motion.g>
                );
            })}
        </svg>
    );
}
