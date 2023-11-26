<script setup>
import { ref, onMounted } from 'vue'

const MSG_TIMEOUT = 2000

const props = defineProps({
  message: {
    type: String,
    required: false,
    default: ''
  }
})

let msg = ref(props.message)

onMounted(() => {
  if (msg.value) {
    setTimeout(() => {
      msg.value = ''
    }, MSG_TIMEOUT)
  }
})
</script>

<template>
  <transition name="fade" mode="out-in">
    <div
      v-if="msg"
      class="fixed top-0 right-0 bg-green-500 text-white p-4 rounded-lg m-4 shadow-lg transition-opacity duration-500"
    >
      {{ msg }}
    </div>
  </transition>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
