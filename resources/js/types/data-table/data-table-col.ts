type Column<T> = {
    key: keyof T | string;
    label: string;
    render?: (row: T) => React.ReactNode;
};
