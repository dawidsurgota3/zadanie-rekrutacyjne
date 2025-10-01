import React from "react";
import { Form } from "@inertiajs/react";
import toast from "react-hot-toast";

type DeleteModalProps = {
    isOpen: boolean;
    onClose: () => void;
    action: string;
};

export function DeleteModal({ isOpen, onClose, action }: DeleteModalProps) {
    if (!isOpen) return null;

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div className="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    Confirmation
                </h2>
                <p className="text-sm text-gray-600 dark:text-gray-300 mb-6">
                    Do You sure you want to delete element?
                </p>

                <Form
                    action={action}
                    method="delete"
                    onSuccess={() => {
                        toast.success('Deleted success');
                        onClose();
                    }}
                    onError={() => {
                        toast.error('Error deleting element');
                    }}
                >
                    {({ processing }) => (
                        <div className="flex justify-end space-x-2">
                            <button
                                type="button"
                                onClick={onClose}
                                className="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                            >
                                Cancel
                            </button>
                            <button
                                type="submit"
                                disabled={processing}
                                className="px-4 py-2 rounded-md bg-red-600 text-white hover:bg-red-700"
                            >
                                Delete
                            </button>
                        </div>
                    )}
                </Form>
            </div>
        </div>
    );
}
