<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";


defineProps({
  category: Object,
});
const form = useForm({
  name: "",
  description: "",
  category: null
});
const storeTournament = () =>{
  form.post(route('tournaments.store'));
}

</script>

<template>
  <Head title="Crear nuevo torneo" />

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
        <h1 class="text-2xl font-semibold text-indigo-700">Crear nuevo torneo</h1>
        <form @submit.prevent="storeTournament">
          <div class="mt-4">
            <InputLabel for="name" value="Nombre" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              autofocus
              autocomplete="tournamentname"
            />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="description" value="Descripción" />
            <TextInput
              id="description"
              type="text"
              class="mt-1 block w-full"
              v-model="form.description"              
              autocomplete="tournamentdescription"
            />

            <InputError class="mt-2" :message="form.errors.description" />
          </div>
          
          <div class="mt-4">
            <InputLabel for="category" value="Categoría" />
            <VueMultiselect
              id="category"
              v-model="form.category"
              :options="category"
              :multiple="false"
              :close-on-select="true"
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
    </div>
  </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>