export interface RowData {
    id: number;
    name: string;
    email: string;
    mobile: string;
    country: string;
    gender: string;
    national_id: string;
    is_approved: boolean;
    banned_at: string | null;
    avatar_img: string | null;
    created_at: string;
}

export interface PaginationData {
    data: RowData[];
    links: {
        url: string | null;
        label: string;
        active: boolean;
    }[];
    current_page: number;
    last_page: number;
    from: number | null;
    to: number | null;
    total: number;
}
