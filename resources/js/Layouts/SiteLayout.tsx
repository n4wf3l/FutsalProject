import { PropsWithChildren } from 'react';
import { Navbar } from '@/Components/site/Navbar';
import { Footer } from '@/Components/site/Footer';
import { LocaleSwitchToast } from '@/Components/site/LocaleSwitchToast';
import { SearchButton } from '@/Components/site/SearchButton';
import { Toaster } from '@/Components/site/Toaster';

export default function SiteLayout({ children }: PropsWithChildren) {
    return (
        <div className="relative min-h-screen bg-background text-foreground">
            <Navbar />

            <main className="relative pt-24">
                {children}
            </main>

            <Footer />
            <LocaleSwitchToast />
            <SearchButton />
            <Toaster />
        </div>
    );
}
