<script setup>
defineProps({
  value: {
    type: String,
    required: true
  }
})
</script>

<template>
  <label class="block font-medium text-sm text-gray-700">
    {{ value }}
  </label>
</template>
