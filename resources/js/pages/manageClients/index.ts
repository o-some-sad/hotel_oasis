export interface RowData {
  id: number;
  name: string;
  email: string;
  mobile: string;
  national_id: string;
  country: string;
  gender: string;
  avatar_img?: string;
  is_approved: boolean;
  created_at: string;
  updated_at: string;
  banned_at?: string;
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
  from: number;
  to: number;
  total: number;
}
