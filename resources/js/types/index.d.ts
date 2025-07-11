import type { PageProps } from '@inertiajs/core';
import type { LucideIcon } from 'lucide-vue-next';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon;
    isActive?: boolean;
    allowedRoles?: User['role'][];
}

export interface SharedData extends PageProps {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    ziggy: Config & { location: string };
    sidebarOpen: boolean;
}

export interface User {
    id: number;
    name: string;
    email: string;
    avatar_img: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    role: 'client' | 'manager' | 'receptionist' | 'admin';
    gender: 'female' | 'male';
    national_id: string;
    mobile: string;
    country: string;
    banned_at: string | null;
}

export interface Floor {
    id: number;
    name: string;
    number: string;
    created_by: number;
    creator?: User;
    created_at: string;
    updated_at: string;
    action?: boolean; // Whether the current user can edit/delete this floor
}

export interface Room {
    id: number;
    name: string;
    floor_id: number;
    floor: Floor;
    created_by: number;
    creator?: User;
    created_at: string;
    updated_at: string;
}

export interface Reservation {
    id: number;
    client_id: number;
    client: User;
    room_id: number;
    room: Room;
    paid_price: number;
    created_at: string;
    updated_at: string;
}

export type BreadcrumbItemType = BreadcrumbItem;

export interface Receptionist extends User {
    role: "receptionist";
}