<script>
  export default {
    name: 'ClubEdit'
  }
</script>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";

import { onMounted } from "vue";
import { usePermission } from "@/composables/permissions"

const { hasRole } = usePermission();
const props = defineProps({
  club: {
    type: Object,
    required: true
  },
  users: Array
});

const form = useForm({
  name: props.club?.name,
  coach: props.club?.coach,
  logo_path: null, // Cambiado a null para manejar la carga de nuevas imágenes
  users: [],
  _method: "put"
});

onMounted(() => {
  form.users = props.club?.users;
});

const updateClub = () => {
  form.post(route('clubs.update', props.club?.id));
};

const onLogoChange = (event) => {
  form.logo_path = event.target.files[0];
};
</script>

<template>
  <Head title="Actualizar club" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('clubs.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar Club</h1>
        <form @submit.prevent="updateClub" enctype="multipart/form-data">
          <div class="mt-4">
            <InputLabel for="name" value="Nombre" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              required
              autofocus
              autocomplete="clubname"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="mt-4">
            <InputLabel for="coach" value="Profesor" />
            <TextInput
              id="coach"
              type="text"
              class="mt-1 block w-full"
              v-model="form.coach"              
              autocomplete="clubcoach"
            />
            <InputError class="mt-2" :message="form.errors.coach" />
          </div>
          <div class="mt-4">
            <InputLabel for="logo_path" value="Logo" />
            <input type="file" id="logo_path"  class="form-control mb-2" @input="onLogoChange">                    
            <InputError class="mt-2" :message="form.errors.logo_path" />
          </div>

          <div class="mt-4">
            <InputLabel for="users" value="Delegado" />
            <VueMultiselect
              id="users"
              v-model="form.users"
              :options="users"
              :multiple="true"
              :close-on-select="true"
              placeholder="Escoge delegados"
              label="name"
              track-by="id"
              :disabled="hasRole('Administrador') || hasRole('Comité técnico') ? false : true"
            />
          </div>
          <div class="flex items-center mt-4">
            <PrimaryButton
              class="ml-4"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Actualizar
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
