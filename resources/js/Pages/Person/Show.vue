<script setup>
import { provide } from 'vue'

import Button from '@/Components/Button.vue'
import DlItem from '@/Components/DlItem.vue'
import DefaultLayout from '@/Layouts/DefaultLayout.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  person: {
    type: Object,
    required: true
  }
})

provide('title', props.title)
</script>

<template>
  <DefaultLayout>
    <div class="mt-4">
      <dl class="divide-y divide-gray-100">
        <!-- Photo -->
        <DlItem v-if="person.photo_path" label="Fénykép" type="img" :value="person.photo_path" />
        <!-- Last name -->
        <DlItem label="Vezetéknév" :value="person.last_name" />
        <!-- First name -->
        <DlItem label="Keresztnév" :value="person.first_name" />
        <!-- Emails -->
        <DlItem label="Email cím(ek)" type="list" :value="person.emails_array" />
        <!-- Phones -->
        <DlItem
          v-if="person.phones_array.length"
          label="Telefonszám(ok)"
          type="list"
          :value="person.phones_array"
        />
        <!-- Address -->
        <DlItem label="Cím" :value="person.full_address" />
        <!-- Post address -->
        <DlItem label="Levelezési cím" :value="person.full_post_address" />
      </dl>
    </div>

    <!-- Buttons -->
    <div class="flex items-center justify-end mt-4">
      <Button btn-type="link" :href="route('persons.index')">Vissza</Button>
      <Button btn-type="link" class="ms-4" :href="route('persons.edit', person.id)">
        Szerkesztés
      </Button>
    </div>
  </DefaultLayout>
</template>
