<script setup lang="ts">
import { SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { SharedData, type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

interface Props {
    items?: NavItem[];
}

defineProps<Props>();

const page = usePage<SharedData>();

const user = computed(() => page.props.auth.user);

function isAllowed(item: NavItem): boolean {
    if (!item.allowedRoles || !item.allowedRoles.length) {
        return true;
    }

    return item.allowedRoles.includes(user.value.role);
}
</script>

<template>
    <SidebarMenu>
        <SidebarMenuItem v-for="(item, index) in items" :key="index" v-show="isAllowed(item)">
            <SidebarMenuButton :as-child="!item.isActive">
                <component
                    :is="item.isActive ? 'div' : Link"
                    :href="item.isActive ? undefined : item.href"
                    :class="['flex items-center gap-3', item.isActive && 'bg-blue-50 text-blue-900 dark:bg-blue-900/20 dark:text-blue-50']"
                >
                    <component v-if="item.icon" :is="item.icon" class="h-4 w-4" />
                    {{ item.title }}
                </component>
            </SidebarMenuButton>
        </SidebarMenuItem>
    </SidebarMenu>
</template>
