<script setup>
import { Head } from '@inertiajs/vue3'
import { inject } from 'vue'

import FlashMessage from '@/Components/FlashMessage.vue'

const title = inject('title')
</script>

<template>
  <Head :title="title" />

  <div class="h-screen">
    <FlashMessage :message="$page.props.flash.success" />

    <main class="bg-white min-h-full mx-auto max-w-4xl py-6 sm:px-6 lg:px-8">
      <h1 class="text-3xl font-bold tracking-tight text-gray-900">{{ title }}</h1>
      <slot />
    </main>
  </div>
</template>
