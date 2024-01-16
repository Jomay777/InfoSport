<script>
  export default {
    name: 'PlayerEdit'
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
  player: {
    type: Object,
    required: true
  },
  team: Object
});


const form = useForm({
  first_name: props.player?.first_name,
  second_name: props.player?.second_name,
  last_name: props.player?.last_name,
  mother_last_name: props.player?.mother_last_name,
  birth_date: "",
  c_i: props.player?.c_i,
  nacionality: props.player?.nacionality,
  country_birth: props.player?.country_birth,
  region_birth: props.player?.region_birth,
  state: "",
  team: null,
  _method: "put"
});

onMounted(() => {
  form.team = props.player?.team;
  form.state = props.player?.state == 1? { id: 1, name: 'inhabilitado' }: { id: 2, name: 'habilitado' };  
  form.birth_date = props.player.birth_date ? props.player.birth_date+'T13:10:00.000Z' :"";
});
const updatePlayer= () => {
  form.post(route('players.update', props.player?.id));
};


</script>

<template>
  <Head title="Actualizar jugador" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('players.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar Equipo</h1>
        <form @submit.prevent="updatePlayer">
          <div class="mt-4">
            <InputLabel for="first_name" value="Primer nombre" />
            <TextInput
              id="first_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.first_name"
              autofocus
              required
              autocomplete="teamfirst_name"
            />
            <InputError class="mt-2" :message="form.errors.first_name" />
          </div>
          <div class="mt-4">
            <InputLabel for="second_name" value="Segundo nombre" />
            <TextInput
              id="second_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.second_name"              
              autocomplete="teamsecond_name"
            />
            <InputError class="mt-2" :message="form.errors.second_name" />
          </div>
          <div class="mt-4">
            <InputLabel for="last_name" value="Apellido paterno" />
            <TextInput
              id="last_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.last_name"
              required              
              autocomplete="teamlast_name"
            />
            <InputError class="mt-2" :message="form.errors.last_name" />
          </div>
          <div class="mt-4">
            <InputLabel for="mother_last_name" value="Apellido materno" />
            <TextInput
              id="mother_last_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.mother_last_name"              
              autocomplete="teammother_last_name"
            />
            <InputError class="mt-2" :message="form.errors.mother_last_name" />
          </div>
          <div class="mt-4">
            <InputLabel for="birth_date" value="Fecha de nacimiento"/>  
            <VueDatePicker 
              v-model="form.birth_date" 
              format="dd-MM-yyyy" 
              locale="es" 
              id="birth_date"
              required
              >
            </VueDatePicker>
            <InputError class="mt-2" :message="form.errors.birth_date" />
          </div>
          <div class="mt-4">
            <InputLabel for="c_i" value="Numero de carnet de identidad" />
            <TextInput
              id="c_i"
              type="text"
              class="mt-1 block w-full"
              v-model="form.c_i" 
              required             
              autocomplete="teamc_i"
            />
            <InputError class="mt-2" :message="form.errors.c_i" />
          </div>        
          <div class="mt-4">
            <InputLabel for="nacionality" value="Nacionalidad" />
            <TextInput
              id="nacionality"
              type="text"
              class="mt-1 block w-full"
              v-model="form.nacionality"  
              required            
              autocomplete="teamnacionality"
            />
            <InputError class="mt-2" :message="form.errors.nacionality" />
          </div>
          <div class="mt-4">
            <InputLabel for="country_birth" value="Ciudad de nacimiento" />
            <TextInput
              id="country_birth"
              type="text"
              class="mt-1 block w-full"
              v-model="form.country_birth"  
              required            
              autocomplete="teamcountry_birth"
            />
            <InputError class="mt-2" :message="form.errors.country_birth" />
          </div>
          <div class="mt-4">
            <InputLabel for="region_birth" value="Región de nacimiento" />
            <TextInput
              id="region_birth"
              type="text"
              class="mt-1 block w-full"
              v-model="form.region_birth"  
              required            
              autocomplete="teamregion_birth"
            />
            <InputError class="mt-2" :message="form.errors.region_birth" />
          </div>
          <div class="mt-4">
            <InputLabel for="state" value="Estado de jugador" />
            <VueMultiselect
              id="state"
              v-model="form.state"
              :options="[{ id: 1, name: 'inhabilitado' }, { id: 2, name: 'habilitado' }]"
              :multiple="false"
              :close-on-select="true"
              placeholder="Elige el estado del jugador"
              label="name"
              track-by="id"
              required
            />
            <InputError class="mt-2" :message="form.errors.state" />
          </div>                    
          <div class="mt-4">
            <InputLabel for="team" value="Equipo" />
            <VueMultiselect
              id="team"
              v-model="form.team"
              :options="team"
              :multiple="false"
              :close-on-select="true"
              placeholder="Elige el equipo al que pertenece"
              label="name"
              track-by="id"
            />
          </div>
          <!-- <div class="mt-4">
            <InputLabel for="photo_player" value="Fotos de jugador" />
            <VueMultiselect
              id="photo_player"
              v-model="form.photo_player"
              :options="photo_player"
              :multiple="false"
              :close-on-select="true"
              placeholder="Elige las fotos del jugador"
              label="name"
              track-by="id"
            />
          </div> -->
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
