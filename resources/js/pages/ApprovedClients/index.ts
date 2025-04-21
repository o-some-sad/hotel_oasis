export interface RowData {
    id: number;
    name: string;
    email: string;

}

export interface PaginationData{
    current_page: number;
    data: RowData[];
    from: number;
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
}
