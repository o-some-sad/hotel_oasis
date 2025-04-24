<script setup lang="ts">
import { router, useForm, Link } from '@inertiajs/vue3';
import { Input } from '@/components/ui/input';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue
} from '@/components/ui/select';
import { Button } from '@/components/ui/button';
import { User } from '@/types';
import {
    Form,
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage
} from '@/components/ui/form';

const props = defineProps<{
    receptionist: User;
}>();

const form = useForm({
    name: props.receptionist.name,
    email: props.receptionist.email,
    avatar_img: null,
    gender: props.receptionist.gender,
    mobile: props.receptionist.mobile,
    country: props.receptionist.country,
    national_id: props.receptionist.national_id
});

const submit = () => {
    console.log(form.data());
    router.post(route('receptionists.update', props.receptionist.id), {
        _method: 'put',
        ...form.data()
    });
};
</script>

<template>
    <div class="container mx-auto my-8 p-6 bg-white dark:bg-gray-900 rounded-md shadow-md">
        <div class="mb-6 flex justify-between items-center">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Edit Receptionist</h2>
            <Link :href="route('receptionists.index')" class="bg-gray-200 px-4 py-2 rounded-md text-sm font-medium text-gray-800 hover:bg-gray-300 dark:bg-gray-800 dark:text-gray-200 dark:hover:bg-gray-700">
                Back to List
            </Link>
        </div>
        <Form>
            <form @submit.prevent="submit" class="space-y-6">
                <FormField name="name">
                    <FormItem>
                        <FormLabel>Name</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.name" class="rounded-md" />
                        </FormControl>
                        <FormMessage>{{ form.errors.name }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input type="email" v-model="form.email" class="rounded-md" />
                        </FormControl>
                        <FormMessage>{{ form.errors.email }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="gender">
                    <FormItem>
                        <FormLabel>Gender</FormLabel>
                        <FormControl>
                            <Select v-model="form.gender" class="rounded-md">
                                <SelectTrigger class="w-full">
                                    <SelectValue placeholder="Select Gender" />
                                </SelectTrigger>
                                <SelectContent>
                                    <SelectItem value="male">Male</SelectItem>
                                    <SelectItem value="female">Female</SelectItem>
                                </SelectContent>
                            </Select>
                        </FormControl>
                        <FormMessage>{{ form.errors.gender }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="mobile">
                    <FormItem>
                        <FormLabel>Mobile</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.mobile" class="rounded-md" />
                        </FormControl>
                        <FormMessage>{{ form.errors.mobile }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="country">
                    <FormItem>
                        <FormLabel>Country</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.country" class="rounded-md" />
                        </FormControl>
                        <FormMessage>{{ form.errors.country }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="national_id">
                    <FormItem>
                        <FormLabel>National ID</FormLabel>
                        <FormControl>
                            <Input type="text" v-model="form.national_id" class="rounded-md" disabled />
                        </FormControl>
                        <FormMessage>{{ form.errors.national_id }}</FormMessage>
                    </FormItem>
                </FormField>

                <FormField name="avatar_img">
                    <FormItem>
                        <FormLabel>Profile Image</FormLabel>
                        <FormControl>
                            <input
                                type="file"
                                @change="(e) => (form.avatar_img = e.target.files[0])"
                                class="input input-bordered w-full rounded-md"
                                name="avatar_img"
                            />
                        </FormControl>
                        <FormMessage>{{ form.errors.avatar_img }}</FormMessage>
                    </FormItem>
                </FormField>

                <div>
                    <Button type="submit" :disabled="form.processing" class="w-full py-2 rounded-md font-semibold">Update Receptionist</Button>
                </div>
            </form>
        </Form>
    </div>
</template>
