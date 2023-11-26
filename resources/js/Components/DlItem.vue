<script setup>
defineProps({
  label: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'text'
  },
  value: {
    type: [String, Array],
    required: true
  }
})
</script>

<template>
  <div class="px-4 py-6 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-0">
    <dt class="text-sm font-medium leading-6 text-gray-900">{{ label }}</dt>
    <dd class="mt-1 text-sm leading-6 text-gray-700 sm:col-span-2 sm:mt-0">
      <!-- List -->
      <div v-if="type === 'list'">
        <ul role="list">
          <li v-for="item in value" :key="item">
            {{ item }}
          </li>
        </ul>
      </div>
      <!-- Image -->
      <div v-else-if="type === 'img'">
        <img :src="value" alt="{{ label }}" class="w-32 h-32 object-cover" />
      </div>
      <!-- Text -->
      <div v-else>
        {{ value }}
      </div>
    </dd>
  </div>
</template>
