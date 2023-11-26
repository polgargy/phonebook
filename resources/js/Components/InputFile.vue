<script setup>
import { ref } from 'vue'

defineProps({
  modelValue: {
    type: File,
    default: null
  }
})

defineEmits(['update:modelValue'])

const input = ref(null)
</script>

<template>
  <input
    ref="input"
    type="file"
    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
    :value="modelValue"
    @change="$emit('update:modelValue', $event.target.files[0])"
  />
</template>
