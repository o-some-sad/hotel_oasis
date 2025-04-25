<template>
    <div class="min-h-screen text-gray-800">
      <!-- Hero Section -->
      <section class="relative h-[80vh] flex items-center justify-center overflow-hidden">
        <div class="absolute inset-0">
          <img 
            src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c" 
            alt="Hotel Oasis"
            class="w-full h-full object-cover object-center"
          />
          <div class="absolute inset-0 bg-black/40"></div>
        </div>
        
        <div class="relative text-center max-w-4xl px-4">
          <h1 class="text-5xl font-bold text-white mb-6 animate-fade-in-up">
            Welcome to Hotel Oasis
          </h1>
          <p class="text-xl text-gray-200 mb-8 max-w-2xl mx-auto">
            Where Oasis Meets Comfort in Perfect Harmony
          </p>
          <button 
            @click="goToBooking"
            class="bg-amber-600 text-white px-8 py-4 rounded-lg hover:bg-amber-700 transition-all duration-300 transform hover:scale-105"
          >
            Book Your Stay Now
          </button>
        </div>
      </section>
  
      <!-- Image Slider -->
      <section class="py-16 bg-gray-100">
        <div class="max-w-7xl mx-auto px-4">
          <h2 class="text-3xl font-bold text-center mb-12">Discover Our World</h2>
          <div class="relative h-[500px] overflow-hidden rounded-2xl shadow-xl">
            <div 
              v-for="(slide, index) in sliderImages" 
              :key="index"
              class="absolute inset-0 transition-opacity duration-1000"
              :class="{ 'opacity-100': activeSlide === index, 'opacity-0': activeSlide !== index }"
            >
              <img 
                :src="slide.image" 
                :alt="slide.title"
                class="w-full h-full object-cover"
              />
              <div class="absolute inset-0 bg-black/30 flex items-end p-8">
                <div class="text-white">
                  <h3 class="text-3xl font-bold mb-4">{{ slide.title }}</h3>
                  <p class="text-lg max-w-xl">{{ slide.description }}</p>
                </div>
              </div>
            </div>
            
            <!-- Slider Controls -->
            <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 flex space-x-2">
              <button 
                v-for="(_, index) in sliderImages"
                :key="index"
                @click="activeSlide = index"
                class="w-3 h-3 rounded-full transition-all duration-300"
                :class="activeSlide === index ? 'bg-amber-600 w-8' : 'bg-white/50'"
              ></button>
            </div>
          </div>
        </div>
      </section>
  
      <!-- Featured Rooms -->
      <section class="py-16 max-w-7xl mx-auto px-4">
        <h2 class="text-3xl font-bold text-center mb-12">Oasis Accommodations</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
          <div 
            v-for="(room, i) in rooms" 
            :key="i"
            class="group relative overflow-hidden rounded-xl shadow-lg hover:shadow-2xl transition-all duration-300"
          >
            <img 
              :src="room.image" 
              :alt="room.name"
              class="w-full h-64 object-cover transform group-hover:scale-105 transition-all duration-300"
            />
            <div class="absolute inset-0 bg-black/30 flex flex-col justify-end p-6">
              <div class="text-white">
                <h3 class="text-2xl font-bold mb-2">{{ room.name }}</h3>
                <p class="mb-4">{{ room.description }}</p>
                <button class="bg-amber-600 text-white px-6 py-3 rounded-lg hover:bg-amber-700 transition">
                  Explore Suite
                </button>
              </div>
            </div>
          </div>
        </div>
      </section>
  
      <!-- About Section -->
      <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 text-center">
          <h2 class="text-3xl font-bold mb-8">Redefining Hospitality</h2>
          <div class="relative before:absolute before:w-24 before:h-1 before:bg-amber-600 before:top-0 before:left-1/2 before:-translate-x-1/2">
            <p class="text-lg text-gray-600 leading-relaxed mb-8">
              At Hotel Oasis, we transcend conventional hospitality to craft experiences that linger in memory. 
              Our philosophy blends contemporary sophistication with timeless elegance, creating a sanctuary 
              where every detail is meticulously curated for your comfort.
            </p>
            <p class="text-lg text-gray-600 leading-relaxed">
              From our award-winning spa to our gourmet dining experiences, we invite you to indulge in 
              unparalleled Oasis. Our dedicated concierge team stands ready to transform your every 
              aspiration into reality, ensuring your stay becomes a cherished chapter in your life's journey.
            </p>
          </div>
        </div>
      </section>
    </div>
  </template>
  
  <script setup>
  import { ref, onMounted, onBeforeUnmount } from 'vue';
  import { router } from '@inertiajs/vue3';
  
  const goToBooking = () => {
    router.visit(route('booking'));
  };
  
  const activeSlide = ref(0);
  const sliderImages = ref([
    {
      image: 'https://images.unsplash.com/photo-1611892440504-42a792e24d32',
      title: 'Oasis Spa Experience',
      description: 'Rejuvenate your senses in our award-winning spa sanctuary'
    },
    {
      image: 'https://images.unsplash.com/photo-1571896349842-33c89424de2d',
      title: 'Gourmet Dining',
      description: 'Experience culinary excellence in our Michelin-starred restaurants'
    },
    {
      image: 'https://images.unsplash.com/photo-1566073771259-6a8506099945',
      title: 'Infinity Pool',
      description: 'Unwind in our panoramic rooftop pool with breathtaking views'
    }
  ]);
  
  let interval;
  const startSlider = () => {
    interval = setInterval(() => {
      activeSlide.value = (activeSlide.value + 1) % sliderImages.value.length;
    }, 5000);
  };
  
  onMounted(() => {
    startSlider();
  });
  
  onBeforeUnmount(() => {
    clearInterval(interval);
  });
  
  // Original rooms array remains the same
  </script>
  
  <style>
  .animate-fade-in-up {
    animation: fadeInUp 1s ease-out;
  }
  
  @keyframes fadeInUp {
    from {
      opacity: 0;
      transform: translateY(20px);
    }
    to {
      opacity: 1;
      transform: translateY(0);
    }
  }
  </style>