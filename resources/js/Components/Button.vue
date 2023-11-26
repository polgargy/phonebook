<script setup>
import { Link } from '@inertiajs/vue3'
import { computed } from 'vue'

const props = defineProps({
  btnType: {
    type: String,
    default: 'primary'
  }
})

const baseClasses =
  'inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
const colorClasses = {
  primary: 'bg-green-800 hover:bg-green-700 focus:bg-green-700 active:bg-green-900',
  secondary: 'bg-indigo-800 hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900',
  danger: 'bg-red-800 hover:bg-red-700 focus:bg-red-700 active:bg-red-900',
  link: 'bg-gray-800 hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900'
}

const classes = computed(() => {
  const colorClass = colorClasses[props.btnType] || colorClasses.primary
  return `${baseClasses} ${colorClass}`
})
</script>

<template>
  <component :is="btnType === 'link' ? Link : 'button'" :class="classes">
    <slot />
  </component>
</template>
