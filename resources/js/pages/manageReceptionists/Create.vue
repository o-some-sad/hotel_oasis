<script setup>
import { useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    national_id: '',
    avatar_img: null,
    gender: '',
    mobile: '',
    country: '',
});

function submit() {
    form.post(route('receptionists.store'), {
        onSuccess: () => {
            window.location.href = '/receptionists';
        },
    });
}
</script>

<template>
    <div class="mx-auto max-w-xl space-y-4 rounded-xl bg-white p-6 shadow-lg">
        <h2 class="text-center text-2xl font-bold text-blue-600">Add Receptionist</h2>

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

            <!-- Password -->
            <div>
                <label class="block text-gray-700">Password</label>
                <input
                    autocomplete="new-password"
                    v-model="form.password"
                    type="password"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                    name="password"
                />
                <span v-if="form.errors.password" class="text-sm text-red-500">{{ form.errors.password }}</span>
            </div>

            <!-- National ID -->
            <div>
                <label class="block text-gray-700">National ID</label>
                <input
                    v-model="form.national_id"
                    type="text"
                    class="w-full rounded-lg border px-4 py-2 text-blue-950 focus:ring-2 focus:ring-blue-500 focus:outline-none"
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
                    class="rounded-lg bg-blue-600 px-6 py-2 text-white transition duration-300 hover:bg-blue-700"
                    :disabled="form.processing"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>
