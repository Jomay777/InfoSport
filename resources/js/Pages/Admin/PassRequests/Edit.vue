<script>
  export default {
    name: 'PassRequestEdit'
  }
</script>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import TableDataCell from "@/Components/TableDataCell.vue"
import VueMultiselect from "vue-multiselect";

import { onMounted, ref } from "vue";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';
import { parseISO, format } from 'date-fns';

const props = defineProps({
  pass_request: {
    type: Object,
    required: true
  },
  player: Array
});


const form = useForm({  
  request_photo_path: "",
  player: null,
  _method: "put"
});

onMounted(() => {
  form.player = {
    id: props.pass_request?.player?.id,
    name: `${props.pass_request?.player?.first_name} ${props.pass_request?.player?.second_name ? props.pass_request?.player?.second_name : ''} ${props.pass_request?.player?.last_name} ${props.pass_request?.player?.mother_last_name ? props.pass_request?.player?.mother_last_name : ''}`
  };
});
const updatePassRequest= () => {
  form.post(route('pass_requests.update', props.pass_request?.id));
};

const handleFileChange = (event) => {
  // Guarda el archivo directamente en el formulario
  if (event.target.files && event.target.files.length > 0) {
    form.request_photo_path = event.target.files[0];
  }
};
</script>

<template>
  <Head title="Actualizar solicitud de pase de jugador" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('pass_requests.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar Solicitud</h1>
        <form @submit.prevent="updatePassRequest" enctype="multipart/form-data">
          <div class="mt-4">
            <InputLabel for="player" value="Jugador" />
            <VueMultiselect
              id="player"
              v-model="form.player"
              :options="player
              .map(item => ({
                id: item.id,
                name: `${item.first_name} ${item.second_name ? item.second_name : ''} ${item.last_name} ${item.mother_last_name ? item.mother_last_name : ''}`
              }))"
              :multiple="false"
              :close-on-select="true"
              placeholder="Elige el jugador"
              label= "name"
              track-by="id"
            />
            <InputError class="mt-2" :message="form.errors.player" />
          </div>
          <div class="mt-4">
            <InputLabel for="request_photo_path" value="Foto de pase de jugador" />
            <input type="file" id="request_photo_path" @change="handleFileChange" />
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
