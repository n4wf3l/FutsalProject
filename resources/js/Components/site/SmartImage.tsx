import { useState, forwardRef } from 'react';
import { cn } from '@/lib/utils';

interface Props extends React.ImgHTMLAttributes<HTMLImageElement> {
    src: string;
    alt: string;
    /** Extra classes appended to the <img> tag itself (object-fit, hover, etc.) */
    className?: string;
    /** Extra classes appended to the shimmer placeholder */
    placeholderClassName?: string;
    /** Disable the blur-up + shimmer effect (useful for small icons/avatars) */
    disableBlur?: boolean;
}

/**
 * Photo loader with a shimmer placeholder and a blur-up transition.
 * Must be placed inside a parent with `position: relative` and its own
 * aspect ratio, because the placeholder is absolutely positioned.
 *
 * Handles the cached-image case (onLoad doesn't fire) via a ref callback
 * that inspects `img.complete` on mount.
 */
export const SmartImage = forwardRef<HTMLImageElement, Props>(function SmartImage(
    { src, alt, className, placeholderClassName, disableBlur = false, loading, ...rest },
    _forwardedRef
) {
    const [loaded, setLoaded] = useState(false);

    return (
        <>
            {!disableBlur && (
                <div
                    aria-hidden
                    className={cn(
                        'pointer-events-none absolute inset-0 bg-gradient-to-br from-muted via-card to-muted transition-opacity duration-700',
                        loaded ? 'opacity-0' : 'animate-pulse opacity-100',
                        placeholderClassName
                    )}
                />
            )}
            <img
                ref={(node) => {
                    if (node?.complete && node.naturalHeight > 0) {
                        setLoaded(true);
                    }
                }}
                src={src}
                alt={alt}
                loading={loading ?? 'lazy'}
                decoding="async"
                onLoad={() => setLoaded(true)}
                className={cn(
                    'relative h-full w-full object-cover transition-[opacity,filter,transform] duration-700 ease-out',
                    !disableBlur && (loaded ? 'opacity-100 blur-0' : 'scale-[1.03] opacity-0 blur-md'),
                    className
                )}
                {...rest}
            />
        </>
    );
});
