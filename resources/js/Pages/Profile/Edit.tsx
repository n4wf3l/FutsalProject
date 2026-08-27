import { Head } from '@inertiajs/react';
import AdminLayout from '@/Layouts/AdminLayout';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import DeleteUserForm from './Partials/DeleteUserForm';

interface Props {
    mustVerifyEmail: boolean;
    status?: string;
}

export default function Edit({ mustVerifyEmail, status }: Props) {
    return (
        <AdminLayout title="Mon profil">
            <Head title="Profil" />

            <div className="mx-auto max-w-3xl space-y-6">
                <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                    <UpdateProfileInformationForm mustVerifyEmail={mustVerifyEmail} status={status} />
                </div>

                <div className="rounded-2xl border border-border bg-card p-6 sm:p-8">
                    <UpdatePasswordForm />
                </div>

                <div className="rounded-2xl border border-plasma/20 bg-card p-6 sm:p-8">
                    <DeleteUserForm />
                </div>
            </div>
        </AdminLayout>
    );
}
