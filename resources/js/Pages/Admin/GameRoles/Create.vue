<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import { ref } from "vue";

defineProps({
  tournament: Object,
  pitch: Object,

});
const form = useForm({
  name: "",
  date: ref(""),
  tournament: null,
  pitch:null
});
const storeGameRole = () =>{
  form.post(route('game_roles.store'));
}

</script>

<template>
  <Head title="Crear nuevo rol de partido" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="h-10"></div>

      <div class="flex justify-between">
        <Link
          :href="route('game_roles.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Crear nuevo rol de partido</h1>
        <form @submit.prevent="storeGameRole">
          <div class="mt-4">
            <InputLabel for="name" value="Nombre" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              autofocus
              autocomplete="game_rolename"
              required
            />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="mt-4">
            <InputLabel for="date" value="Fecha de los partidos" />            
            <VueDatePicker 
              v-model="form.date" 
              format="dd-MM-yyyy" 
              locale="es" 
              id="date"
              :enable-time-picker="false"
              required>
            </VueDatePicker>
            <InputError class="mt-2" :message="form.errors.date" />
          </div>
          
          <div class="mt-4">
            <InputLabel for="tournament" value="Torneo" />
            <VueMultiselect
              id="tournament"
              v-model="form.tournament"
              :options="tournament"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige la categoría"
              label="name"
              track-by="id"          
            />
            <InputError class="mt-2" :message="form.errors.tournament" />
          </div>
          <div class="mt-4">
            <InputLabel for="pitch" value="Cancha" />
            <VueMultiselect
              id="pitch"
              v-model="form.pitch"
              :options="pitch"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige la categoría"
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
      <div class="h-20"></div>
    </div>
  </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>