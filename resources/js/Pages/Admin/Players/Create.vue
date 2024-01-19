<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";
import VueDatePicker from '@vuepic/vue-datepicker';
import '@vuepic/vue-datepicker/dist/main.css';

import { ref } from "vue";


defineProps({
  team: Object,
  photo_player: Array
});
const form = useForm({
  first_name: "",
  second_name: "",
  last_name: "",
  mother_last_name: "",
  gender:"",
  birth_date: ref(""),
  c_i: "",
  nacionality: "",
  country_birth: "",
  region_birth: "",
  state: "",
  team: null,
  photo_player: {
    photo_path: null,
    photo_c_i: null,
    photo_birth_certificate: null,
    photo_parental_authorization: null,
  }
});
const storePlayer = () =>{
  form.post(route('players.store'));
}
const handleFileChangePP = (event) => {
  // Guarda el archivo directamente en el formulario
  if (event.target.files && event.target.files.length > 0) {
    form.photo_player.photo_path = event.target.files[0];
  }
};
const handleFileChangeCI = (event) => {
  // Guarda el archivo directamente en el formulario
  if (event.target.files && event.target.files.length > 0) {
    form.photo_player.photo_c_i = event.target.files[0];
  }
};
const handleFileChangeBC = (event) => {
  // Guarda el archivo directamente en el formulario
  if (event.target.files && event.target.files.length > 0) {
    form.photo_player.photo_birth_certificate = event.target.files[0];
  }
};
const handleFileChangePA = (event) => {
  // Guarda el archivo directamente en el formulario
  if (event.target.files && event.target.files.length > 0) {
    form.photo_player.photo_parental_authorization = event.target.files[0];
  }
};
</script>

<template>
  <Head title="Crear nuevo jugador" />

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
        <h1 class="text-2xl font-semibold text-indigo-700">Crear nuevo jugador</h1>
        <form @submit.prevent="storePlayer" enctype="multipart/form-data">
          <div class="mt-4">
            <InputLabel for="first_name" value="Primer nombre" />
            <TextInput
              id="first_name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.first_name"
              autofocus
              required
              autocomplete="playerfirst_name"
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
              autocomplete="playersecond_name"
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
              autocomplete="playerlast_name"
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
              autocomplete="playermother_last_name"
            />
            <InputError class="mt-2" :message="form.errors.mother_last_name" />
          </div>
          <div class="mt-4">
            <InputLabel for="gender" value="Género" />
            <VueMultiselect
              id="gender"
              v-model="form.gender"
              :options="[{ id: 1, name: 'Hombre' }, { id: 2, name: 'Mujer' }]"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true" 
              placeholder="Elige el estado del jugador"
              label="name"
              track-by="id"
              required
            />
            <InputError class="mt-2" :message="form.errors.gender" />
          </div>
          <div class="mt-4">
            <InputLabel for="birth_date" value="Fecha de nacimiento" />            
            <VueDatePicker 
              v-model="form.birth_date" 
              format="dd-MM-yyyy" 
              locale="es" 
              id="birth_date"
              required>
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
              autocomplete="playerc_i"
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
              autocomplete="playernacionality"
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
              autocomplete="playercountry_birth"
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
              autocomplete="playerregion_birth"
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
              :preselect-first="true"
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
              :preselect-first="true"
              placeholder="Elige el equipo al que pertenece"
              label="name"
              track-by="id"
            />
          </div>
          <div class="mt-4">
            <InputLabel for="photo_player.photo_path" value="Foto de jugador" />
            <input type="file" id="photo_player.photo_path" @change="handleFileChangePP" required/>
          </div>
          <div class="mt-4">
            <InputLabel for="photo_player.photo_c_i" value="Foto del carnet de identidad" />
            <input type="file" id="photo_player.photo_c_i" @change="handleFileChangeCI" required/>
          </div>
          <div class="mt-4">
            <InputLabel for="photo_player.photo_birth_certificate" value="Foto del certificado de nacimiento" />
            <input type="file" id="photo_player.photo_birth_certificate" @change="handleFileChangeBC" required/>
          </div>          
          <div class="mt-4">
            <InputLabel for="photo_player.photo_parental_authorization" value="Foto de autorización parental" />
            <input type="file" id="photo_player.photo_parental_authorization" @change="handleFileChangePA" />
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