import { motion } from 'framer-motion';
import { cn } from '@/lib/utils';

interface Props {
    className?: string;
    /** Optional: override pin color (default champagne accent). */
    pinColor?: string;
    /** Optional: override stroke color for the country outline. */
    strokeColor?: string;
    /** Optional: pin coordinates (lng, lat). Default = Kenitra. */
    pinLngLat?: [number, number];
    /** Optional: pin size in viewBox units. Default 1.6. */
    pinSize?: number;
    /** Optional label under the pin (city name). */
    label?: string;
}

/**
 * Decorative outline of Morocco with a pulsing pin at a given city.
 * viewBox is aligned to lng/lat so the pin position stays accurate.
 *
 * Bounds used to compute viewBox:
 *   lng: -17.1 (west) → -1.0 (east)
 *   lat:  20.7 (south) → 35.9 (north)
 */

const BOUNDS = {
    minLng: -17.1,
    maxLng: -1.0,
    minLat: 20.7,
    maxLat: 35.9,
};

const W = 100;
const H = 100;

function project(lng: number, lat: number): [number, number] {
    const x = ((lng - BOUNDS.minLng) / (BOUNDS.maxLng - BOUNDS.minLng)) * W;
    const y = ((BOUNDS.maxLat - lat) / (BOUNDS.maxLat - BOUNDS.minLat)) * H;
    return [x, y];
}

// Simplified Morocco outline (incl. Western Sahara), clockwise from Cap Spartel.
// Enough points to be recognisable, few enough to stay light.
const OUTLINE: Array<[number, number]> = [
    [-5.90, 35.80], // Cap Spartel
    [-5.35, 35.90], // Tangier
    [-4.90, 35.85], // Sebta
    [-4.35, 35.35], // Al Hoceima
    [-3.60, 35.40], // Cote nord
    [-2.93, 35.30], // Nador
    [-2.24, 35.09], // Saidia
    [-1.90, 34.80], // Oujda north
    [-1.70, 34.15], //
    [-1.55, 33.20], //
    [-1.20, 32.10], // Figuig
    [-2.20, 32.10],
    [-3.30, 31.70],
    [-4.00, 30.70],
    [-5.50, 29.60],
    [-7.40, 29.40],
    [-8.70, 27.50],
    [-8.70, 27.00],
    [-12.00, 26.00],
    [-13.00, 21.30], // SE corner
    [-13.00, 20.90],
    [-17.05, 20.77], // Cap Blanc
    [-16.90, 21.10],
    [-16.00, 22.00],
    [-15.00, 22.90],
    [-14.50, 23.50],
    [-13.90, 24.50], // Dakhla area
    [-13.60, 26.00],
    [-13.20, 27.20], // Laâyoune
    [-12.40, 28.00],
    [-11.60, 28.30], // Tarfaya
    [-10.40, 29.60], // Guelmim
    [-9.90, 30.40], // Agadir
    [-9.80, 31.50], // Essaouira
    [-9.30, 32.30], // Safi
    [-8.50, 33.20], // El Jadida
    [-7.60, 33.60], // Casablanca
    [-6.60, 34.00], // Rabat
    [-6.30, 34.70], // Kenitra coast
    [-5.90, 35.80], // back to Cap Spartel
];

function outlinePath(): string {
    return OUTLINE.map(([lng, lat], i) => {
        const [x, y] = project(lng, lat);
        return `${i === 0 ? 'M' : 'L'}${x.toFixed(2)},${y.toFixed(2)}`;
    }).join(' ') + ' Z';
}

export function MoroccoMap({
    className,
    pinColor = 'currentColor',
    strokeColor = 'currentColor',
    pinLngLat = [-6.5802, 34.261],
    pinSize = 1.6,
    label,
}: Props) {
    const [pinX, pinY] = project(pinLngLat[0], pinLngLat[1]);

    return (
        <svg
            viewBox={`0 0 ${W} ${H}`}
            className={cn('block', className)}
            role="img"
            aria-label="Kénitra sur la carte du Maroc"
        >
            {/* Country outline */}
            <path
                d={outlinePath()}
                fill="none"
                stroke={strokeColor}
                strokeWidth={0.5}
                strokeLinejoin="round"
                strokeLinecap="round"
                vectorEffect="non-scaling-stroke"
            />

            {/* Pulsing halo behind the pin */}
            <motion.circle
                cx={pinX}
                cy={pinY}
                r={pinSize * 2.4}
                fill={pinColor}
                initial={{ opacity: 0.35, scale: 0.6 }}
                animate={{ opacity: [0.35, 0, 0.35], scale: [0.6, 1.4, 0.6] }}
                transition={{ duration: 2.4, repeat: Infinity, ease: 'easeInOut' }}
            />

            {/* Pin core */}
            <circle cx={pinX} cy={pinY} r={pinSize} fill={pinColor} />
            <circle cx={pinX} cy={pinY} r={pinSize * 0.4} fill="#fff" opacity={0.85} />

            {label && (
                <text
                    x={pinX + pinSize * 1.8}
                    y={pinY + 0.6}
                    fontSize={2.6}
                    fontFamily="system-ui, sans-serif"
                    fontWeight={600}
                    fill={pinColor}
                >
                    {label}
                </text>
            )}
        </svg>
    );
}
