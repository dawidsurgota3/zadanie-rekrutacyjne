import { PetResponse } from '@/types/pets/pet-response';
import { Head } from '@inertiajs/react';
import AppLayout from '@/layouts/app-layout';
import type { BreadcrumbItem } from '@/types';
import { dashboard } from '@/routes';
import petController from '@/actions/App/Http/Controllers/PetController';
import { DataTable } from "@/components/data-table";
import { CreateModal } from '@/components/create-modal';
import { useState } from 'react';
import { EditModal } from "@/components/edit-modal";
import { DeleteModal } from '@/components/delete-modal';
import { Button } from '@/components/ui/button';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: dashboard().url,
    }, {
        title: 'Pets',
        href: petController.index().url,
    },
];




export default function Pets({ pets }: { pets: PetResponse[] }) {
    const [isOpen, setIsOpen] = useState(false);
    const [selectedPet, setSelectedPet] = useState<PetResponse | null>(null);
    const [deletePet, setDeletePet] = useState<PetResponse | null>(null);

    const columns: Column<PetResponse>[] = [
        { key: "id", label: "ID" },
        { key: "name", label: "Name" },
        {key:'category.name', label: "Category" },
        { key: "status", label: "Status" },
        {
            key: "actions",
            label: "Akcje",
            render: (row) => (

        <Button
            onClick={(e) => {
                e.stopPropagation();
                setDeletePet(row);
            }}
            variant="destructive"
            data-test="delete-user-button"
        >
            Delete
        </Button>
            ),
        },
    ];

    const emptyPet = {
        id: "",
        name: "",
        status: "available",
        category: {
            id: "",
            name: "",
        },
        photoUrls: [""],
    };

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pets" />
            <div className="flex h-full flex-1 flex-col gap-4 overflow-x-auto rounded-xl p-4">
                <div className="flex justify-end">
                    <button
                        onClick={() => {
                            setSelectedPet(null); // nowy
                            setIsOpen(true);
                        }}
                        className="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700"
                    >
                        Add Pet
                    </button>
                </div>

                <DataTable
                    columns={columns}
                    data={pets}
                    onRowClick={(row) => {
                        setSelectedPet(row);
                        setIsOpen(true);
                    }}
                />
            </div>

            {selectedPet ? (
                <EditModal
                    isOpen={isOpen}
                    onClose={() => {
                        setIsOpen(false);
                        setSelectedPet(null);
                    }}
                    action={petController.update(selectedPet.id).url}
                    method="put"
                    initialData={selectedPet}
                    fields={[
                        { name: "name", label: "Pet Name" },
                        { name: "status", label: "Status" },
                        { name: "category.id", label: "Category ID" },
                        { name: "category.name", label: "Category" },
                        { name: "photoUrls.0", label: "PhotoUrl" },
                    ]}
                />
            ) : (
                <CreateModal
                    action={petController.store().url}
                    method={petController.store().method}
                    isOpen={isOpen}
                    onClose={() => setIsOpen(false)}
                    emptyData={emptyPet}
                    fields={[
                        { name: "name", label: "Pet Name" },
                        { name: "status", label: "Status" },
                        { name: "category.id", label: "Category ID" },
                        { name: "category.name", label: "Category" },
                        { name: "photoUrls.0", label: "PhotoUrl" },
                    ]}
                />
            )}

            {deletePet && (
                <DeleteModal
                    isOpen={!!deletePet}
                    onClose={() => setDeletePet(null)}
                    action={petController.destroy(deletePet.id).url}
                />
            )}
        </AppLayout>
    );
}
