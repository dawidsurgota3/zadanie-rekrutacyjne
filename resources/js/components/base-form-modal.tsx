import React from "react";
import { Form, useForm } from "@inertiajs/react";
import { Input } from "@/components/ui/input";
import InputError from "@/components/input-error";
import toast from "react-hot-toast";

type FieldConfig<T> = {
    name: keyof T & string | string;
    label: string;
    type?: string;
    component?: React.ComponentType<any>;
};

type BaseFormModalProps<T> = {
    isOpen: boolean;
    onClose: () => void;
    title: string;
    action: string;
    method: "post" | "put" | "patch" | "delete";
    initialData: T;
    fields: FieldConfig<T>[];
    successMessage: string;
    errorMessage?: string;
};

export function BaseFormModal<T extends Record<string, any>>({
                                                                 isOpen,
                                                                 onClose,
                                                                 title,
                                                                 action,
                                                                 method,
                                                                 initialData,
                                                                 fields,
                                                                 successMessage,
                                                                 errorMessage = 'Error',
                                                             }: BaseFormModalProps<T>) {
    const { data, setData, reset } = useForm<T>(initialData);

    if (!isOpen) return null;

    const splitPath = (path: string): string[] => {
        const keys: string[] = [];
        let buffer = "";
        for (const char of path) {
            if (char === "." || char === "[" || char === "]") {
                if (buffer) {
                    keys.push(buffer);
                    buffer = "";
                }
            } else {
                buffer += char;
            }
        }
        if (buffer) keys.push(buffer);
        return keys;
    };

    const getNested = (obj: any, path: string) =>
        splitPath(path).reduce((acc, key) => acc?.[key], obj);

    const handleChange = (name: string, value: any) => {
        setData(name as any, value);
    };

    return (
        <div className="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
            <div className="bg-white dark:bg-gray-900 rounded-lg shadow-lg w-full max-w-md p-6">
                <h2 className="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                    {title}
                </h2>

                <Form
                    action={action}
                    method={method}
                    className="space-y-4"
                    onSuccess={() => {
                        toast.success(successMessage);
                        reset();
                        onClose();
                    }}
                    onError={() => {
                        toast.error(errorMessage);
                    }}
                >
                    {({ errors, processing }) => (
                        <>
                            {fields.map((field) => {
                                const Component = field.component ?? Input;
                                return (
                                    <div key={field.name}>
                                        <label
                                            htmlFor={field.name}
                                            className="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                                        >
                                            {field.label}
                                        </label>
                                        <Component
                                            id={field.name}
                                            name={field.name}
                                            type={field.type || "text"}
                                            value={getNested(data, field.name) ?? ""}
                                            onChange={(e: any) =>
                                                handleChange(field.name, e.target?.value ?? e)
                                            }
                                            className="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-200 focus:ring-indigo-500 focus:border-indigo-500"
                                        />
                                        <InputError message={errors[field.name]} />
                                    </div>
                                );
                            })}

                            <div className="flex justify-end space-x-2">
                                <button
                                    type="button"
                                    onClick={() => {
                                        reset();
                                        onClose();
                                    }}
                                    className="px-4 py-2 rounded-md bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                                >
                                    Cancel
                                </button>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className={`px-4 py-2 rounded-md ${
                                        method === "post"
                                            ? "bg-green-600 hover:bg-green-700"
                                            : "bg-indigo-600 hover:bg-indigo-700"
                                    } text-white`}
                                >
                                    {method === "post" ? "Create" : "Save"}
                                </button>
                            </div>
                        </>
                    )}
                </Form>
            </div>
        </div>
    );
}
