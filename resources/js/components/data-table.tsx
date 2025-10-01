import * as React from "react";

type DataTableProps<T> = {
    columns: Column<T>[];
    data: T[];
    className?: string;
    onRowClick?: (row: T) => void;
};

export function TableWrapper({ children, className = "" }: { children: React.ReactNode; className?: string }) {
    return (
        <div
            className={`bg-white dark:bg-gray-900 rounded-lg shadow-sm border border-gray-100 dark:border-gray-800 p-4 ${className}`}
        >
            {children}
        </div>
    );
}

export function Table({ className = "", ...props }: React.HTMLAttributes<HTMLTableElement>) {
    return (
        <div className="overflow-x-auto">
            <table
                className={`w-full min-w-[640px] divide-y divide-gray-200 dark:divide-gray-700 ${className}`}
                {...props}
            />
        </div>
    );
}

export function TableHeader({ className = "", ...props }: React.HTMLAttributes<HTMLTableSectionElement>) {
    return <thead className={`bg-transparent ${className}`} {...props} />;
}

export function TableBody({ className = "", ...props }: React.HTMLAttributes<HTMLTableSectionElement>) {
    return <tbody className={`${className}`} {...props} />;
}

export function TableRow({ className = "", ...props }: React.HTMLAttributes<HTMLTableRowElement>) {
    return (
        <tr
            className={`last:border-0 hover:bg-gray-50 dark:hover:bg-gray-800 ${className}`}
            {...props}
        />
    );
}

export function TableHead({ className = "", ...props }: React.ThHTMLAttributes<HTMLTableCellElement>) {
    return (
        <th
            scope="col"
            className={`sticky top-0 z-10 bg-white/90 dark:bg-gray-900/90 px-3 py-2 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300 ${className}`}
            {...props}
        />
    );
}

export function TableCell({ className = "", ...props }: React.TdHTMLAttributes<HTMLTableCellElement>) {
    return (
        <td
            className={`px-3 py-2 text-sm text-gray-700 dark:text-gray-200 ${className}`}
            {...props}
        />
    );
}

function getValue(obj: any, path: string) {
    return path.split(".").reduce((acc, part) => acc?.[part], obj);
}

export function DataTable<T extends Record<string, any>>({
                                                             columns,
                                                             data,
                                                             className = "",
                                                             onRowClick
                                                         }: DataTableProps<T>) {
    return (
        <TableWrapper className={className}>
            <Table>
                <TableHeader>
                    <TableRow>
                        {columns.map((col) => (
                            <TableHead key={String(col.key)}>{col.label}</TableHead>
                        ))}
                    </TableRow>
                </TableHeader>
                <TableBody>
                    {data.map((row, i) => (
                        <TableRow
                            key={i}
                            onClick={() => onRowClick?.(row)}
                            className="cursor-pointer"
                        >
                            {columns.map((col) => (
                                <TableCell key={String(col.key)}>
                                    {col.render
                                        ? col.render(row)
                                        : String(getValue(row, String(col.key)) ?? "")}
                                </TableCell>
                            ))}
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </TableWrapper>
    );
}
