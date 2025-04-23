<script setup lang="ts">
import NavFooter from '@/components/NavFooter.vue';
import NavMain from '@/components/NavMain.vue';
import NavUser from '@/components/NavUser.vue';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { SharedData, User, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { BookOpen, Building, Folder, LayoutGrid, PersonStanding, SquareUserRound, UsersRound } from 'lucide-vue-next';
import AppLogo from './AppLogo.vue';

const page = usePage<SharedData>();
const user = page.props.auth.user as User;

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
        allowedRoles: ['client', 'admin', 'manager', 'receptionist'],
    },
    {
        title: 'My Reservations',
        href: '/my-reservations',
        icon: BookOpen,
        allowedRoles: ['client'],
    },
    {
        title: 'Receptionists',
        href: '/receptionists',
        icon: SquareUserRound,
        allowedRoles: ['admin', 'manager'],
    },
    {
        title: 'Manage Clients',
        href: '/clients',
        icon: UsersRound,
        allowedRoles: ['admin', 'manager'],
    },
    {
        title: 'Manage Floors',
        href: '/floors',
        icon: Building,
        allowedRoles: ['admin', 'manager'],
    },
    {
        title: 'Approved clients',
        href: '/approved-clients',
        icon: UsersRound,
        allowedRoles: ['admin', 'manager', 'receptionist'],
    },
    {
        title: 'Pending Approval',
        href: '/pending-approvals',
        icon: PersonStanding,
        allowedRoles: ['admin', 'manager', 'receptionist'],
    },
];

const footerNavItems: NavItem[] = [
    
];
</script>

<template>
    <Sidebar collapsible="icon" variant="inset">
        <SidebarHeader>
            <SidebarMenu>
                <SidebarMenuItem>
                    <SidebarMenuButton size="lg" as-child>
                        <Link :href="route('dashboard')">
                            <AppLogo />
                        </Link>
                    </SidebarMenuButton>
                </SidebarMenuItem>
            </SidebarMenu>
        </SidebarHeader>
        <SidebarContent>
            <NavMain :items="mainNavItems" />
        </SidebarContent>

        <SidebarFooter>
            <NavFooter :items="footerNavItems" />
            <NavUser />
        </SidebarFooter>
    </Sidebar>
    <slot />
</template>
