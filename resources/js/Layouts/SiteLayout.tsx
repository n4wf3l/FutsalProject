import { PropsWithChildren } from 'react';
import { Navbar } from '@/Components/site/Navbar';
import { Footer } from '@/Components/site/Footer';

export default function SiteLayout({ children }: PropsWithChildren) {
    return (
        <div className="relative min-h-screen bg-background text-foreground">
            <div className="pointer-events-none fixed inset-0 -z-10">
                <div className="absolute inset-0 bg-grid-fade opacity-70" />
                <div className="absolute inset-x-0 top-0 h-[520px] grid-bg opacity-40" />
            </div>

            <Navbar />

            <main className="relative pt-24">
                {children}
            </main>

            <Footer />
        </div>
    );
}
