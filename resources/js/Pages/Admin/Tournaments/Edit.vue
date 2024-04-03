<script>
  export default {
    name: 'TournamentEdit'
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

const props = defineProps({
  tournament: {
    type: Object,
    required: true
  },
  category: Object
});

const form = useForm({
  name: props.tournament?.name,
  description: props.tournament?.description,
  state:null,
  category: null,
  _method: "put"
});

onMounted(() => {
  form.category = props.tournament?.category;
  form.state = (props.tournament?.state === 'No publicado' ? { id: 1 , name:props.tournament?.state}: '') || (props.tournament?.state === 'Publicado' ? { id: 2 , name:props.tournament?.state}: '');
});

const updateTournament= () => {
  form.post(route('tournaments.update', props.tournament?.id));
};

</script>

<template>
  <Head title="Actualizar torneo" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('tournaments.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar Torneo</h1>
        <form @submit.prevent="updateTournament">
          <div class="mt-4">
            <InputLabel for="name" value="Nombre" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              required
              autofocus
              autocomplete="tournamentname"
            />
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div class="mt-4">
            <InputLabel for="category" value="Categoría" />
            <VueMultiselect
              id="category"
              v-model="form.category"
              :options="category"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige la categoría"
              label="name"
              track-by="id"
              required
            />
            <InputError class="mt-2" :message="form.errors.category" />
          </div>
          <div class="mt-4">
            <InputLabel for="state" value="Estado" />
            <VueMultiselect
              id="state"
              v-model="form.state"
              :options="[{ id: 1, name: 'No publicado' }, { id: 2, name: 'Publicado' }]"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige el estado del torneo"
              label="name"
              track-by="id"
              required
            />
            <InputError class="mt-2" :message="form.errors.state" />
          </div>
          <div class="mt-4">
            <InputLabel for="description" value="Descripción" />
            <textarea
              id="description"
              type="text"
              class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
              v-model="form.description"              
              autocomplete="clubdescription"
            />
            <InputError class="mt-2" :message="form.errors.description" />
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
