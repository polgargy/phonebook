<script setup>
import { EyeIcon, PencilIcon, TrashIcon } from '@heroicons/vue/20/solid'
import axios from 'axios'
import { provide, ref } from 'vue'

import Button from '@/Components/Button.vue'
import DefaultLayout from '@/Layouts/DefaultLayout.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  persons: {
    type: Array,
    required: false,
    default: () => []
  }
})

provide('title', props.title)

let listedPersons = ref([...props.persons])

function confirmDelete(id) {
  if (window.confirm('Biztosan törölni akarod?')) {
    axios.delete(`persons/${id}`).then(() => {
      listedPersons.value = listedPersons.value.filter((person) => person.id !== id)
    })
  }
}
</script>

<template>
  <DefaultLayout>
    <div class="flex items-center justify-end">
      <Button btn-type="link" :href="route('persons.create')">+</Button>
    </div>

    <div class="overflow-x-auto mt-4">
      <table v-if="listedPersons.length" class="min-w-full divide-y divide-gray-200 mt-4">
        <thead class="bg-gray-50">
          <tr>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Vezetéknév
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Keresztnév
            </th>
            <th
              class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
            >
              Létrehozva
            </th>
            <th />
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <tr v-for="person in listedPersons" :key="person.id" class="odd:bg-gray-100">
            <td class="px-6 py-4 whitespace-nowrap">{{ person.last_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ person.first_name }}</td>
            <td class="px-6 py-4 whitespace-nowrap">{{ person.created_at }}</td>
            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
              <Button class="mr-2" btn-type="link" :href="route('persons.show', person.id)">
                <EyeIcon class="h-3 w-3"
              /></Button>
              <Button class="mr-2" btn-type="link" :href="route('persons.edit', person.id)">
                <PencilIcon class="h-3 w-3"
              /></Button>
              <Button class="mr-2" btn-type="danger" @click="confirmDelete(person.id)">
                <TrashIcon class="h-3 w-3"
              /></Button>
            </td>
          </tr>
        </tbody>
      </table>

      <div v-else class="text-center mt-4">
        Nincs megjelenítendő elem. A gombra kattintva hozzá tudsz adni személyeket a listához.
      </div>
    </div>
  </DefaultLayout>
</template>
