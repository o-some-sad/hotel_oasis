import { Reservation } from "@/types";

export type RowData = Reservation;
  
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
  