<template>
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-blue-50 py-12 px-4 sm:px-6 lg:px-8">
      <div class="max-w-3xl mx-auto">
        <div class="text-center mb-8">
          <h1 class="text-3xl font-bold text-gray-900 mb-2">Create New Manager</h1>
          <p class="text-gray-600">Fill in the details below to register a new manager</p>
        </div>
  
        <div class="bg-white shadow-xl rounded-2xl overflow-hidden p-6 sm:p-8">
          <form @submit.prevent="submitForm" class="space-y-6" enctype="multipart/form-data">
  
            <div class="grid grid-cols-1 gap-6">
              <div class="flex flex-col gap-1">
                <label for="name" class="font-medium text-gray-700">Full Name</label>
                <input id="name" v-model="form.name" type="text" placeholder="John Doe" class="rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
              </div>
  
              <div class="flex flex-col gap-1">
                <label for="email" class="font-medium text-gray-700">Email Address</label>
                <input id="email" v-model="form.email" type="email" placeholder="john@example.com" class="rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
              </div>
  
              <div class="flex flex-col gap-1">
                <label for="password" class="font-medium text-gray-700">Password</label>
                <input id="password" v-model="form.password" type="password" placeholder="••••••••" class="rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
              </div>
  
              <div class="flex flex-col gap-1">
                <label for="national_id" class="font-medium text-gray-700">National ID</label>
                <input id="national_id" v-model="form.national_id" type="text" placeholder="1234567890" class="rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
              </div>
  
              <div class="flex flex-col gap-1">
                <label class="font-medium text-gray-700">Profile Photo</label>
                <div class="flex items-center gap-4 mt-1">
                  <div class="h-16 w-16 rounded-full bg-gray-100 overflow-hidden">
                    <img v-if="avatarPreview" :src="avatarPreview" class="h-full w-full object-cover" />
                    <div v-else class="flex items-center justify-center h-full w-full text-gray-400">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4a2 2 0 002 2h2l2 3 2-3h2a2 2 0 002-2V3M5 21h14" />
                      </svg>
                    </div>
                  </div>
                  <label class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium bg-white hover:bg-gray-50 cursor-pointer">
                    Upload Photo
                    <input id="avatar_img" type="file" accept="image/*" class="hidden" @change="handleAvatarUpload">
                  </label>
                </div>
              </div>
  
              <div class="flex flex-col gap-1">
                <label for="mobile" class="font-medium text-gray-700">Mobile Number</label>
                <input id="mobile" v-model="form.mobile" type="tel" placeholder="+1234567890" class="rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
              </div>
  
              <div class="flex flex-col gap-1">
                <label for="country" class="font-medium text-gray-700">Country</label>
                <select id="country" v-model="form.country" class="rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200" required>
                  <option value="" disabled>Select country</option>
                  <option value="USA">United States</option>
                  <option value="UK">United Kingdom</option>
                  <option value="CA">Canada</option>
                  <option value="AU">Australia</option>
                </select>
              </div>
  
              <div class="flex flex-col gap-1">
                <label class="font-medium text-gray-700">Gender</label>
                <div class="flex gap-4">
                  <label class="flex items-center gap-2">
                    <input type="radio" value="male" v-model="form.gender" class="text-blue-600 focus:ring-blue-500">
                    <span>Male</span>
                  </label>
                  <label class="flex items-center gap-2">
                    <input type="radio" value="female" v-model="form.gender" class="text-blue-600 focus:ring-blue-500">
                    <span>Female</span>
                  </label>
                </div>
              </div>
            </div>
  
            <div class="flex justify-end gap-3 pt-6">
              <button type="button" @click="resetForm" class="inline-flex justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                Cancel
              </button>
              <button type="submit" class="inline-flex justify-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-blue-700">
                Create Manager
              </button>
            </div>
  
          </form>
        </div>
      </div>
    </div>
  </template>
  
  <script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  
  const form = ref({
    name: '',
    email: '',
    password: '',
    national_id: '',
    avatar_img: null,
    mobile: '',
    country: '',
    gender: 'male'
  })
  
  const avatarPreview = ref(null)
  
  const handleAvatarUpload = (event) => {
    const file = event.target.files[0]
    if (file) {
      form.value.avatar_img = file
      avatarPreview.value = URL.createObjectURL(file)
    }
  }
  
  const resetForm = () => {
    form.value = {
      name: '',
      email: '',
      password: '',
      national_id: '',
      avatar_img: null,
      mobile: '',
      country: '',
      gender: 'male'
    }
    avatarPreview.value = null
  }
  
  const submitForm = async () => {
    try {
      const formData = new FormData()
      for (const key in form.value) {
        if (form.value[key] !== null) {
          formData.append(key, form.value[key])
        }
      }
  
      await axios.post('/Managers/Store', formData, {
        headers: { 'Content-Type': 'multipart/form-data' }
      })
  
      alert('Manager created successfully!')
      resetForm()
    } catch (error) {
      console.error('Error creating manager:', error)
      alert('Something went wrong while creating the manager.')
    }
  }
  </script>
  