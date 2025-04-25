<script setup lang="ts">
import { Button } from '@/components/ui/button';
import { Card, CardContent, CardDescription, CardFooter, CardHeader, CardTitle } from '@/components/ui/card';
import AppLayout from '@/layouts/AppLayout.vue';
import { BreadcrumbItem } from '@/types';
import { Link } from '@inertiajs/vue3';

const props = defineProps<{
    reservation: any;
}>();

const currencyFormatter = new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD',
});

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: 'Clients Reservations',
        href: '/clients-reservations',
    },
    {
        title: `Reservation #${props.reservation.id}`,
        href: `/clients-reservations/${props.reservation.id}`,
    },
];
</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-bold">Reservation Details</h1>
                <Button variant="outline">
                    <Link :href="route('clients-reservations.index')">Back to All Reservations</Link>
                </Button>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <Card>
                    <CardHeader>
                        <CardTitle>Client Information</CardTitle>
                        <CardDescription>Details about the reservation client</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Name</span>
                                <span class="font-medium">{{ reservation.client.name }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Email</span>
                                <span class="font-medium">{{ reservation.client.email }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Phone</span>
                                <span class="font-medium">{{ reservation.client.phone || 'N/A' }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Accompany Number</span>
                                <span class="font-medium">{{ reservation.accompany_number }} person(s)</span>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="outline" class="w-full">
                            <Link :href="route('approved-clients.show', { id: reservation.client.id })">View Full Client Profile</Link>
                        </Button>
                    </CardFooter>
                </Card>

                <Card>
                    <CardHeader>
                        <CardTitle>Reservation Details</CardTitle>
                        <CardDescription>Information about this reservation</CardDescription>
                    </CardHeader>
                    <CardContent>
                        <div class="space-y-2">
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Room Number</span>
                                <span class="font-medium">{{ reservation.room.number }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Paid Price</span>
                                <span class="font-medium">{{ currencyFormatter.format(reservation.paid_price / 100) }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Payment Status</span>
                                <span class="font-medium">{{ reservation.payment_status }}</span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-muted-foreground text-sm">Created On</span>
                                <span class="font-medium">{{ new Date(reservation.created_at).toLocaleDateString() }}</span>
                            </div>
                        </div>
                    </CardContent>
                    <CardFooter>
                        <Button variant="outline" class="w-full">
                            <Link :href="route('rooms.show', { room: reservation.room.id })">View Room Details</Link>
                        </Button>
                    </CardFooter>
                </Card>
            </div>
        </div>
    </AppLayout>
</template>
