import AppLayoutTemplate from '@/layouts/app/app-sidebar-layout';
import { type BreadcrumbItem } from '@/types';
import { type ReactNode, useEffect } from 'react';
import { Toaster, toast } from 'react-hot-toast';
import { usePage } from '@inertiajs/react';

interface AppLayoutProps {
    children: ReactNode;
    breadcrumbs?: BreadcrumbItem[];
}

export default function AppLayout({ children, breadcrumbs, ...props }: AppLayoutProps) {
    const { errors } = usePage().props;

    useEffect(() => {
        if (errors.server) {
            const info = JSON.parse(errors.server);
            toast.error(`${info.code} - ${info.message}`);
        }
    }, [errors]);

    return (
        <AppLayoutTemplate breadcrumbs={breadcrumbs} {...props}>
            {children}
            <Toaster position="top-right" />
        </AppLayoutTemplate>
    );
}
