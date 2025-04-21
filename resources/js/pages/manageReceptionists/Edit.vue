<script setup>
import { useForm } from '@inertiajs/vue3';
import { defineProps, onMounted } from 'vue';

const props = defineProps({
    receptionist: Object,
});

const form = useForm({
    _method: 'patch',
    name: null,
    email: null,
    national_id: null,
    avatar_img: null,
    gender: null,
    mobile: null,
    country: null,
});

onMounted(() => {
    form.name = props.receptionist.name;
    form.email = props.receptionist.email;
    form.national_id = props.receptionist.national_id;
    form.avatar_img = props.receptionist.avatar_img;
    form.gender = props.receptionist.gender;
    form.mobile = props.receptionist.mobile;
    form.country = props.receptionist.country;
});

function submit() {
    if (!form.avatar_img) {
        delete form.avatar_img;
    }
    form.post(route('receptionists.update', props.receptionist.id), {
        forceFormData: true,
        onSuccess: () => {
            window.location.href = '/receptionists';
        },
    });
}
</script>
<style>
input:disabled {
    color: #a1a09a;
}
</style>

<template>
    <div class="mx-auto max-w-xl space-y-4 rounded-xl bg-white p-6 shadow-lg">
        <h2 class="text-center text-2xl font-bold text-blue-600">Edit Receptionist</h2>

        <form @submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
            <!-- Name -->
            <div>
                <label class="block text-gray-700">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="name"
                />
                <span v-if="form.errors.name" class="text-sm text-red-500">{{ form.errors.name }}</span>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700">Email</label>
                <input
                    autocomplete="off"
                    v-model="form.email"
                    type="email"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="email"
                />
                <span v-if="form.errors.email" class="text-sm text-red-500">{{ form.errors.email }}</span>
            </div>

            <!-- National ID -->
            <div>
                <label class="block text-gray-700">National ID</label>
                <input
                    v-model="form.national_id"
                    type="text"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    :disabled="true"
                    name="national_id"
                />
                <span v-if="form.errors.national_id" class="text-sm text-red-500">{{ form.errors.national_id }}</span>
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-gray-700">Gender</label>
                <select
                    v-model="form.gender"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="gender"
                >
                    <option disabled value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <span v-if="form.errors.gender" class="text-sm text-red-500">{{ form.errors.gender }}</span>
            </div>

            <!-- Mobile -->
            <div>
                <label class="block text-gray-700">Mobile</label>
                <input
                    v-model="form.mobile"
                    type="text"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="mobile"
                />
                <span v-if="form.errors.mobile" class="text-sm text-red-500">{{ form.errors.mobile }}</span>
            </div>

            <!-- Country -->
            <div>
                <label class="block text-gray-700">Country</label>
                <input
                    v-model="form.country"
                    type="text"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="country"
                />
                <span v-if="form.errors.country" class="text-sm text-red-500">{{ form.errors.country }}</span>
            </div>

            <!-- Profile Image -->
            <div>
                <label class="block text-gray-700">Profile Image</label>
                <input
                    type="file"
                    @change="(e) => (form.avatar_img = e.target.files[0])"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:outline-none"
                    name="avatar_img"
                />
                <span v-if="form.errors.avatar_img" class="text-sm text-red-500">{{ form.errors.avatar_img }}</span>
            </div>

            <div class="text-center">
                <button
                    type="submit"
                    class="rounded-lg bg-green-600 px-6 py-2 text-white transition duration-300 hover:bg-green-700"
                    :disabled="form.processing"
                >
                    Update
                </button>
            </div>
        </form>
    </div>
</template>
