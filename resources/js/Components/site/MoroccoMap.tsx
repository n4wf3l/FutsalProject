import { motion } from 'framer-motion';
import { cn } from '@/lib/utils';

interface Props {
    className?: string;
    /** Pin color for city marker. */
    pinColor?: string;
    /** Country outline stroke color. */
    strokeColor?: string;
    /** Country subtle fill color (usually same as stroke, low opacity). */
    fillColor?: string;
    /** Fill opacity 0..1. Default 0.08. */
    fillOpacity?: number;
    /** Stroke width in viewBox units. Default 0.4. */
    strokeWidth?: number;
    /** Optional label under the pin. */
    label?: string;
}

/**
 * Decorative Morocco outline (incl. Western Sahara) with a pulsing pin on
 * Kenitra. Path is Natural Earth 50m projected via equirectangular so lng/lat
 * to SVG coordinates is linear:
 *   x = (lng + 17.05) * 6.005
 *   y = (35.9  - lat) * 6.610
 * viewBox is 0 0 96.39 100.
 *
 * Kenitra (-6.5802, 34.261) → approximately (62.87, 10.83).
 */

// Contour du Maroc incluant le Sahara occidental tel que revendiqué
// officiellement par le Royaume. La section berm du path Natural Earth
// 50m d'origine (qui longeait le mur des sables) a été remplacée par
// la frontière Maroc-Mauritanie réelle :
//   - Diagonale droite depuis le tripoint (27.28°N, -8.67°W) jusqu'au
//     bend de Choum (~21.33°N, -13.03°W)
//   - Segment horizontal sur le parallèle 21°20'N jusqu'à la côte
//   - Petit segment côtier jusqu'au Cap Blanc (20.77°N, -17.05°W)
const MOROCCO_PATH =
    'M89.40,5.70L89.58,6.21L89.93,6.62L91.21,7.55L91.97,8.12L91.99,8.33L91.73,8.79L91.64,9.12L91.84,9.46L92.30,9.88L92.34,10.09L92.23,10.33L91.99,10.77L92.49,12.10L92.58,13.38L92.45,14.29L92.45,14.81L92.52,15.25L92.95,16.30L92.67,18.01L92.99,18.94L93.45,19.69L93.69,21.05L94.06,21.68L94.65,22.24L94.98,22.43L95.63,22.90L96.11,23.28L96.39,23.86L95.80,24.34L95.32,24.77L95.19,25.22L95.41,25.95L95.41,26.35L95.11,26.48L93.89,26.44L92.93,26.41L91.84,26.37L90.30,26.30L89.34,26.25L88.01,26.19L87.57,26.23L86.36,26.43L85.51,26.57L85.38,26.62L85.09,26.80L84.92,27.34L84.74,27.96L84.57,28.24L82.02,29.12L81.02,29.25L80.46,29.16L80.04,29.23L79.69,29.42L79.56,29.71L79.54,30.08L79.63,30.45L79.87,30.96L79.91,31.49L79.76,31.85L79.72,32.22L79.65,32.62L79.78,32.83L80.02,32.87L80.26,33.05L80.61,33.21L80.91,33.52L80.89,33.98L80.65,34.23L80.43,34.36L79.48,34.48L78.71,34.58L77.73,35.30L76.69,36.06L75.43,36.56L74.88,36.71L73.93,37.07L72.77,37.66L72.21,38.62L71.51,39.73L70.81,40.48L69.88,41.17L69.01,41.45L67.90,41.78L66.52,42.03L65.54,42.13L65.24,42.18L64.39,42.20L63.96,42.14L63.65,42.12L63.52,42.19L63.48,42.37L63.45,42.76L63.39,43.22L63.13,43.60L62.93,43.78L62.69,43.85L61.97,43.74L61.37,43.62L59.93,43.46L59.65,43.49L59.54,43.54L59.08,43.80L58.38,44.35L57.90,44.84L57.56,45.06L56.73,45.18L56.36,45.36L54.79,46.57L54.46,46.85L52.85,47.91L52.40,48.25L52.03,48.59L51.07,49.37L50.46,49.70L50.35,49.90L50.31,50.38L50.31,51.42L50.31,52.43L50.31,53.89L50.31,55.35L50.31,57.02L45.08,64.88L39.84,72.74L34.61,80.59L29.37,88.45L24.14,96.31L20.00,96.40L15.00,96.55L10.00,96.80L6.00,97.30L3.30,97.90L1.50,98.90L0.50,99.60L0.00,100.00L0.46,96.70L1.28,94.92L1.94,94.13L2.96,93.71L3.90,91.91L4.24,90.26L4.85,89.50L5.05,88.90L4.81,88.44L5.40,87.54L6.10,86.18L6.42,85.31L7.25,83.96L7.36,83.66L7.27,83.31L6.94,83.61L6.60,84.10L6.18,84.49L6.36,84.02L6.68,83.30L7.42,82.56L8.58,81.73L10.97,78.93L11.89,78.44L12.69,77.27L13.00,76.21L13.08,73.82L13.37,72.55L13.89,71.56L14.52,69.77L15.00,68.95L15.32,67.32L15.67,66.69L16.28,66.40L17.15,65.58L18.46,65.08L20.01,64.01L20.72,63.38L21.22,62.43L21.75,60.54L22.66,58.55L23.14,57.06L23.16,57.04L23.97,56.25L24.53,55.25L25.47,54.81L27.43,54.59L30.35,53.77L32.96,52.52L33.70,52.02L34.50,51.03L35.81,49.74L38.29,48.18L39.42,47.32L41.14,45.14L42.30,43.35L43.25,42.19L43.91,41.16L44.36,40.12L44.63,38.44L44.45,37.78L43.73,36.72L43.23,36.43L43.10,35.93L43.36,35.03L43.36,33.50L43.51,31.06L44.32,29.09L46.30,26.49L46.67,25.44L46.89,23.74L46.91,23.14L49.39,20.75L50.85,18.90L51.35,18.46L52.64,17.62L57.10,15.78L59.62,14.48L61.10,13.52L61.97,12.40L64.41,7.96L66.81,1.72L67.00,0.99L68.07,0.79L68.83,0.71L69.44,0.48L70.18,0.00L70.90,0.19L70.55,0.51L70.55,1.28L71.05,2.18L71.94,3.19L73.58,4.47L74.84,4.99L76.65,5.30L78.74,4.74L79.91,4.73L80.50,4.49L81.11,4.85L82.31,4.95L83.44,4.76L84.31,4.22L84.85,3.61L84.94,3.91L84.96,4.25L85.14,4.44L85.46,5.23L85.66,5.53L86.31,5.48L86.88,5.64L88.16,5.56L89.40,5.70Z';

// Kenitra pre-computed from the same equirectangular projection.
const KENITRA_X = 62.87;
const KENITRA_Y = 10.83;

export function MoroccoMap({
    className,
    pinColor = '#DC2626',
    strokeColor = 'currentColor',
    fillColor,
    fillOpacity = 0.14,
    strokeWidth = 0.6,
    label,
}: Props) {
    const fill = fillColor ?? strokeColor;

    return (
        <svg
            viewBox="0 0 96.39 100"
            className={cn('block', className)}
            xmlns="http://www.w3.org/2000/svg"
            role="img"
            aria-label="Kénitra sur la carte du Maroc"
        >
            {/* Fill fades in after the outline is fully drawn */}
            <motion.path
                d={MOROCCO_PATH}
                fill={fill}
                stroke="none"
                initial={{ fillOpacity: 0 }}
                animate={{ fillOpacity }}
                transition={{ duration: 0.6, delay: 2.4, ease: 'easeOut' }}
            />

            {/* Outline drawn like a pencil stroke on mount */}
            <motion.path
                d={MOROCCO_PATH}
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

            {/* Pin appears once the outline is drawn */}
            <motion.g
                initial={{ opacity: 0, scale: 0.4 }}
                animate={{ opacity: 1, scale: 1 }}
                transition={{ duration: 0.5, delay: 2.7, ease: [0.22, 1, 0.36, 1] }}
                style={{ transformOrigin: `${KENITRA_X}px ${KENITRA_Y}px` }}
            >
                {/* Pulsing halo */}
                <motion.circle
                    cx={KENITRA_X}
                    cy={KENITRA_Y}
                    r={2.6}
                    fill={pinColor}
                    initial={{ opacity: 0.4, scale: 0.5 }}
                    animate={{ opacity: [0.4, 0, 0.4], scale: [0.5, 1.8, 0.5] }}
                    transition={{ duration: 2.4, repeat: Infinity, ease: 'easeInOut', delay: 2.9 }}
                />
                <circle cx={KENITRA_X} cy={KENITRA_Y} r={1.4} fill={pinColor} />
                <circle cx={KENITRA_X} cy={KENITRA_Y} r={0.55} fill="#fff" fillOpacity={0.9} />
            </motion.g>

            {label && (
                <motion.text
                    x={KENITRA_X + 2.5}
                    y={KENITRA_Y + 0.6}
                    fontSize={2.4}
                    fontFamily="system-ui, sans-serif"
                    fontWeight={600}
                    fill={pinColor}
                    initial={{ opacity: 0 }}
                    animate={{ opacity: 1 }}
                    transition={{ duration: 0.5, delay: 3.0 }}
                >
                    {label}
                </motion.text>
            )}
        </svg>
    );
}
