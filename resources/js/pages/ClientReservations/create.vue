<script setup lang="ts">
import ScrollArea from '@/components/ui/scroll-area/ScrollArea.vue';
import Select from '@/components/ui/select/Select.vue';
import SelectContent from '@/components/ui/select/SelectContent.vue';
import SelectGroup from '@/components/ui/select/SelectGroup.vue';
import SelectItem from '@/components/ui/select/SelectItem.vue';
import SelectLabel from '@/components/ui/select/SelectLabel.vue';
import SelectTrigger from '@/components/ui/select/SelectTrigger.vue';
import SelectValue from '@/components/ui/select/SelectValue.vue';
import Separator from '@/components/ui/separator/Separator.vue';
import AppLayout from '@/layouts/AppLayout.vue';
import {
    Card,
    CardContent,
    CardDescription,
    CardFooter,
    CardHeader,
    CardTitle,
} from '@/components/ui/card'
import { BreadcrumbItem } from '@/types';
import Button from '@/components/ui/button/Button.vue';
import { ref } from 'vue';
import RadioGroup from '@/components/ui/radio-group/RadioGroup.vue';
import RadioGroupItem from '@/components/ui/radio-group/RadioGroupItem.vue';
import Label from '@/components/ui/label/Label.vue';
import { AcceptableValue } from 'reka-ui';
import { router, Link, useForm } from '@inertiajs/vue3';
import Slider from '@/components/ui/slider/Slider.vue';
import NumberField from '@/components/ui/number-field/NumberField.vue';
import NumberFieldContent from '@/components/ui/number-field/NumberFieldContent.vue';
import NumberFieldDecrement from '@/components/ui/number-field/NumberFieldDecrement.vue';
import NumberFieldInput from '@/components/ui/number-field/NumberFieldInput.vue';
import NumberFieldIncrement from '@/components/ui/number-field/NumberFieldIncrement.vue';
import { Equal, X } from 'lucide-vue-next';
const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
    {
        title: "My Reservations",
        href: "/my-reservations",
    },
    {
        title: "Create Reservation",
        href: "/my-reservations/create",
    }
]

const props = defineProps<{
    availableCapacities: number[]
    availableRooms: any[];
}>()

const currentCapacity = new URLSearchParams(window.location.search).get('capacity') || undefined

const currentRoom = ref<any | null>(null)

const form = useForm<{
    room: any | null,
    duration: number
}>({
    room: null,
    duration: 2    
})

function selectCapacity(capacity: AcceptableValue) {
    router.visit(route('my-reservations.create', { capacity: capacity }))
}

function checkout(){
    console.log(form.data());
    
    // const paymentLink = route('createPaymentLink')
    form.post(route('my-reservations.store'))
}

</script>

<template>
    <AppLayout :breadcrumbs="breadcrumbs" title="Client Reservations">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Card class="">
                <CardHeader>
                    <CardTitle class="text-3xl">Step 1: How many people?</CardTitle>
                </CardHeader>
                <CardContent>
                    <form>
                        <div class="grid items-center w-full gap-4">
                            <div class="flex flex-col space-y-1.5">
                                <Label for="framework">Capacity</Label>
                                <Select @update:model-value="selectCapacity" :default-value="currentCapacity">
                                    <SelectTrigger class="w-[210px]">
                                        <SelectValue placeholder="Select room capacity" />
                                    </SelectTrigger>
                                    <SelectContent>
                                        <SelectGroup>
                                            <SelectItem v-for="capacity in props.availableCapacities"
                                                :value="capacity.toString()">
                                                {{ capacity }} people
                                            </SelectItem>
                                        </SelectGroup>
                                    </SelectContent>
                                </Select>
                            </div>
                        </div>
                    </form>
                </CardContent>
            </Card>
            <Card class="" v-if="props.availableRooms.length">
                <CardHeader>
                    <CardTitle class="text-3xl">Step 2: Select Room</CardTitle>
                </CardHeader>
                <CardContent>
                    <ScrollArea class="max-h-72">
                        <RadioGroup default-value="option-one"
                            @update:model-value="currentRoom = availableRooms.find(room => room.id === $event); form.room = $event">
                            <Label v-for="room in props.availableRooms" :class="{
                                'border-indigo-500': room.id === currentRoom?.id
                            }" class="flex items-center justify-between max-w-sm space-x-2 border p-4 rounded-lg">
                                <div class="flex flex-col">
                                    <div>
                                        <RadioGroupItem class="opacity-0" id="option-one" :value="room.id" />
                                        <span>
                                            Room {{ room.number }} at floor {{ room.floor.name }}
                                        </span>
                                    </div>
                                    <p>
                                        Suitable for {{ room.capacity }} people
                                    </p>
                                </div>
                                <div>
                                    <p> ${{ room.price }}</p>
                                    <p><i>Per night</i></p>
                                </div>

                            </Label>
                        </RadioGroup>

                    </ScrollArea>
                </CardContent>
            </Card>
            <Card class="" v-if="currentRoom">
                <CardHeader>
                    <CardTitle class="text-3xl">Step 3: Select Duration & Checkout</CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="flex flex-col space-y-1.5">
                        <Label for="duration">Duration</Label>
                        <div class="flex items-center gap-3">
                            <NumberField v-model="form.duration" id="duration" :default-value="2" :min="1" :max="30" class="w-[180px]">
                                <NumberFieldContent>
                                    <NumberFieldDecrement />
                                    <NumberFieldInput />
                                    <NumberFieldIncrement />
                                </NumberFieldContent>
                            </NumberField>
                            Day(s)
                            <X />
                            <span>
                                ${{ currentRoom.price }}
                            </span>
                            <Equal />
                            <span class="font-bold">
                                ${{ currentRoom.price * form.duration }}
                            </span>
                        </div>
                    </div>
                    <Separator class="my-4" />
                    <div class="flex gap-4 space-y-1.5">
                        <Button @click="checkout" size="lg" class="w-fit">Book Now</Button>
                        <Link :href="route('my-reservations.index')" class="w-fit">
                            <Button size="lg" class="w-fit" variant="outline">Cancel</Button>
                        </Link>
                    </div>
                </CardContent>
            </Card>
        </div>
    </AppLayout>
</template>