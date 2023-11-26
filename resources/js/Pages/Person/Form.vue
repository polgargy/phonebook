<script setup>
import { TrashIcon } from '@heroicons/vue/20/solid'
import { router } from '@inertiajs/vue3'
import { vMaska } from 'maska'
import { provide, reactive, ref, watch } from 'vue'

import Button from '@/Components/Button.vue'
import InputCheckbox from '@/Components/InputCheckbox.vue'
import InputError from '@/Components/InputError.vue'
import InputField from '@/Components/InputField.vue'
import InputFile from '@/Components/InputFile.vue'
import InputLabel from '@/Components/InputLabel.vue'
import DefaultLayout from '@/Layouts/DefaultLayout.vue'

const props = defineProps({
  title: {
    type: String,
    required: true
  },
  person: {
    type: [Object, null],
    required: true
  },
  errors: {
    type: Object,
    default: () => ({})
  }
})

provide('title', props.title)

const form = reactive({
  photo: null,
  delete_photo: false,
  last_name: props.person?.last_name || '',
  first_name: props.person?.first_name || '',
  emails: props.person?.emails_array || [''],
  phones: props.person?.phones.length
    ? props.person.phones
    : [
        {
          country_code: 36,
          number: ''
        }
      ],
  zip: props.person?.zip || '',
  city: props.person?.city || '',
  address: props.person?.address || '',
  post_zip: props.person?.post_zip || '',
  post_city: props.person?.post_city || '',
  post_address: props.person?.post_address || '',
  different_post_address: props.person ? true : false
})

function submit() {
  processPhones()

  if (props.person) {
    router.post(route('persons.update', props.person.id), {
      _method: 'put',
      ...form
    })
  } else {
    router.post(route('persons.store'), {
      ...form
    })
  }
}

let isPhotoVisible = ref(false)

watch(
  () => props.person?.photo_path,
  (newVal) => {
    isPhotoVisible.value = !!newVal
  },
  { immediate: true }
)

function removePhoto() {
  isPhotoVisible.value = false
  form.delete_photo = true
}

function addEmailRow() {
  form.emails.push('')
}

function addPhoneRow() {
  form.phones.push({
    country_code: 36,
    number: ''
  })
}

function removeRow(type) {
  form[`${type}s`].pop()
}

function processPhones() {
  // Some workarounds because of the maska plugin
  // Filter out any empty values
  form.phones = form.phones.filter(isNotEmpty)

  // Convert values to numbers
  form.phones = form.phones.map((phone) => {
    return {
      country_code: toNumber(phone.country_code),
      number: toNumber(phone.number)
    }
  })
}

function isNotEmpty(phone) {
  return String(phone.country_code).trim() !== '' && String(phone.number).trim() !== ''
}

function toNumber(value) {
  return Number(String(value).replace(/\D/g, ''))
}
</script>

<template>
  <DefaultLayout>
    <form class="mt-4" @submit.prevent="submit">
      <!-- Photo -->
      <div>
        <InputLabel for="photo" value="Fénykép" />
        <div v-if="isPhotoVisible" class="relative">
          <img :src="person.photo_path" alt="Fénykép" class="w-32 h-32 object-cover" />
          <Button
            class="absolute top-0 left-0"
            btn-type="danger"
            type="button"
            @click.stop.prevent="removePhoto()"
          >
            <TrashIcon class="h-3 w-3"
          /></Button>
        </div>
        <InputFile
          id="photo"
          accept="image/*"
          @update:model-value="(file) => (form.photo = file)"
        />
        <InputError class="mt-2" :message="errors.photo" />
      </div>

      <!-- Last name -->
      <div class="mt-4">
        <InputLabel for="last-name" value="Vezetéknév" />
        <InputField id="last-name" v-model="form.last_name" type="text" autofocus required />
        <InputError class="mt-2" :message="errors.last_name" />
      </div>

      <!-- First name -->
      <div class="mt-4">
        <InputLabel for="first-name" value="Keresztnév" />
        <InputField id="first-name" v-model="form.first_name" type="text" required />
        <InputError class="mt-2" :message="errors.first_name" />
      </div>

      <!-- Email(s) -->
      <div class="mt-4">
        <InputLabel for="email" value="Email cím(ek)" />
        <div v-for="(email, index) in form.emails" :key="index">
          <InputField :id="`email-${index}`" v-model="form.emails[index]" type="text" />
          <InputError class="mt-2" :message="errors[`emails.${index}`]" />
        </div>

        <div class="flex items-center justify-end mt-1">
          <Button
            v-if="form.emails.length > 1"
            btn-type="danger"
            @click.prevent="removeRow('email')"
            >-</Button
          >
          <Button btn-type="secondary" class="ms-1" @click.prevent="addEmailRow()">+</Button>
        </div>
      </div>

      <!-- Phone(s) -->
      <div class="mt-4">
        <InputLabel for="phone" value="Telefonszám(ok)" />
        <div v-for="(phone, index) in form.phones" :key="index">
          <div class="flex">
            <InputField
              :id="`phone-${index}`"
              v-model="form.phones[index].country_code"
              v-maska
              class="mr-2 w-1/5"
              type="text"
              placeholder="36"
              data-maska="####"
            />
            <InputField
              :id="`phone-${index}`"
              v-model="form.phones[index].number"
              v-maska
              class="w-4/5"
              type="text"
              placeholder="30 123 4567"
              data-maska="## ### #### ###"
            />
          </div>
          <InputError class="mt-2" :message="errors[`phones.${index}.number`]" />
        </div>

        <div class="flex items-center justify-end mt-1">
          <Button
            v-if="form.phones.length > 0"
            btn-type="danger"
            @click.prevent="removeRow('phone')"
            >-</Button
          >
          <Button btn-type="secondary" class="ms-1" @click.prevent="addPhoneRow()">+</Button>
        </div>
      </div>

      <!-- Zip -->
      <div class="mt-4">
        <InputLabel for="zip" value="Irányítószám" />
        <InputField
          id="zip"
          v-model="form.zip"
          v-maska
          type="text"
          placeholder="1111"
          data-maska="####"
        />
        <InputError class="mt-2" :message="errors.zip" />
      </div>

      <!-- City -->
      <div class="mt-4">
        <InputLabel for="city" value="Város" />
        <InputField id="city" v-model="form.city" type="text" />
        <InputError class="mt-2" :message="errors.city" />
      </div>

      <!-- Address -->
      <div class="mt-4">
        <InputLabel for="address" value="Cím" />
        <InputField id="address" v-model="form.address" type="text" />
        <InputError class="mt-2" :message="errors.address" />
      </div>

      <div class="block mt-4">
        <InputCheckbox v-model:checked="form.different_post_address" name="remember">
          A levelezési cím eltér a lakcímtől
        </InputCheckbox>
      </div>

      <div v-if="form.different_post_address">
        <!-- Post zip -->
        <div class="mt-4">
          <InputLabel for="post-zip" value="Levelezési irányítószám" />
          <InputField
            id="post-zip"
            v-model="form.post_zip"
            v-maska
            type="text"
            placeholder="1111"
            data-maska="####"
          />
          <InputError class="mt-2" :message="errors.post_zip" />
        </div>

        <!-- Post city -->
        <div class="mt-4">
          <InputLabel for="city" value="Levelezési város" />
          <InputField id="city" v-model="form.post_city" type="text" />
          <InputError class="mt-2" :message="errors.post_city" />
        </div>

        <!-- Post address -->
        <div class="mt-4">
          <InputLabel for="address" value="Levelezési cím" />
          <InputField id="address" v-model="form.post_address" type="text" />
          <InputError class="mt-2" :message="errors.post_address" />
        </div>
      </div>

      <!-- Buttons -->
      <div class="flex items-center justify-end mt-4">
        <Button btn-type="link" :href="route('persons.index')">Vissza</Button>
        <Button
          btn-type="primary"
          class="ms-4"
          :class="{ 'opacity-25': form.processing }"
          :disabled="form.processing"
        >
          Mentés
        </Button>
      </div>
    </form>
  </DefaultLayout>
</template>
