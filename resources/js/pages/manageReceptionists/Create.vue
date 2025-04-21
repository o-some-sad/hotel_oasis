<script setup lang="ts">
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    national_id: '',
    avatar_img: null,
    gender: '',
    mobile: '',
    country: '',
})

function submit() {
    form.post(route('receptionists.store'), {
        onSuccess: () => {
            window.location.href = '/receptionists';
        }
    });
}
</script>

<template>
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-xl p-6 space-y-4">
        <h2 class="text-2xl font-bold text-center text-blue-600">Add Receptionist</h2>

        <form @submit.prevent="submit" class="space-y-4" enctype="multipart/form-data">
            <!-- Name -->
            <div>
                <label class="block text-gray-700">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    name="name"
                />
                <span v-if="form.errors.name" class="text-red-500 text-sm">{{ form.errors.name }}</span>
            </div>

            <!-- Email -->
            <div>
                <label class="block text-gray-700">Email</label>
                <input
                    autocomplete="off"
                    v-model="form.email"
                    type="email"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    name="email"
                />
                <span v-if="form.errors.email" class="text-red-500 text-sm">{{ form.errors.email }}</span>
            </div>

            <!-- Password -->
            <div>
                <label class="block text-gray-700">Password</label>
                <input
                    autocomplete="new-password"
                    v-model="form.password"
                    type="password"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    name="password"
                />
                <span v-if="form.errors.password" class="text-red-500 text-sm">{{ form.errors.password }}</span>
            </div>

            <!-- National ID -->
            <div>
                <label class="block text-gray-700">National ID</label>
                <input
                    v-model="form.national_id"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    name="national_id"
                />
                <span v-if="form.errors.national_id" class="text-red-500 text-sm">{{ form.errors.national_id }}</span>
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-gray-700">Gender</label>
                <select
                    v-model="form.gender"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    name="gender"
                >
                    <option disabled value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                </select>
                <span v-if="form.errors.gender" class="text-red-500 text-sm">{{ form.errors.gender }}</span>
            </div>

            <!-- Mobile -->
            <div>
                <label class="block text-gray-700">Mobile</label>
                <input
                    v-model="form.mobile"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    name="mobile"
                />
                <span v-if="form.errors.mobile" class="text-red-500 text-sm">{{ form.errors.mobile }}</span>
            </div>

            <!-- Country -->
            <div>
                <label class="block text-gray-700">Country</label>
                <input
                    v-model="form.country"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                    name="country"
                />
                <span v-if="form.errors.country" class="text-red-500 text-sm">{{ form.errors.country }}</span>
            </div>

            <!-- Profile Image -->
            <div>
                <label class="block text-gray-700">Profile Image</label>
                <input
                    type="file"
                    @change="e => form.avatar_img = e.target.files[0]"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none"
                    name="avatar_img"
                />
                <span v-if="form.errors.avatar_img" class="text-red-500 text-sm">{{ form.errors.avatar_img }}</span>
            </div>

            <div class="text-center">
                <button
                    type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition duration-300"
                    :disabled="form.processing"
                >
                    Submit
                </button>
            </div>
        </form>
    </div>
</template>
