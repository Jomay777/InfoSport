<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";


defineProps({
  users: Array
});
const form = useForm({
  name: "",
  coach: "",
  logo_path: "",
  users: [],
});
</script>

<template>
  <Head title="Crear nuevo club" />

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
        <h1 class="text-2xl font-semibold text-indigo-700">Crear nuevo club</h1>
        <form @submit.prevent="form.post(route('clubs.store'))">
          <div class="mt-4">
            <InputLabel for="name" value="Nombre" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              autofocus
              autocomplete="clubname"
            />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="coach" value="Profesor" />
            <TextInput
              id="coach"
              type="text"
              class="mt-1 block w-full"
              v-model="form.coach"
              autofocus
              autocomplete="clubcoach"
            />

            <InputError class="mt-2" :message="form.errors.coach" />
          </div>
          <div class="mt-4">
            <InputLabel for="logo_path" value="Logo" />
            <TextInput
              id="logo_path"
              type="text"
              class="mt-1 block w-full"
              v-model="form.logo_path"
              autofocus
              autocomplete="clublogo_path"
            />

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
            />
          </div>
          <div class="flex items-center mt-4">
            <PrimaryButton
              class="ml-4"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Crear
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>