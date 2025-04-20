<script setup>
import { useForm } from '@inertiajs/vue3'

const form = useForm({
    name: '',
    email: '',
    password: '',
    img: null,
    gender: '',
    national_id: '',
})

function submit() {
    form.post('/receptionists')
}
</script>

<template>
    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-xl p-6 space-y-4">
        <h2 class="text-2xl font-bold text-center text-blue-600">Add Receptionist</h2>

        <form @submit.prevent="submit" class="space-y-4">
            <!-- Name -->
            <div>
                <label class="block text-gray-700">Name</label>
                <input
                    v-model="form.name"
                    type="text"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
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
                />
                <span v-if="form.errors.national_id" class="text-red-500 text-sm">{{ form.errors.national_id }}</span>
            </div>

            <!-- Gender -->
            <div>
                <label class="block text-gray-700">Gender</label>
                <select
                    v-model="form.gender"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                >
                    <option disabled value="">Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
                <span v-if="form.errors.gender" class="text-red-500 text-sm">{{ form.errors.gender }}</span>
            </div>

            <!-- Image -->
            <div>
                <label class="block text-gray-700">Profile Image</label>
                <input
                    type="file"
                    @change="e => form.img = e.target.files[0]"
                    class="w-full px-4 py-2 border rounded-lg focus:outline-none"
                />
                <span v-if="form.errors.img" class="text-red-500 text-sm">{{ form.errors.img }}</span>
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
