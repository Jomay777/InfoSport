<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import { ref } from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Checkbox from "@/Components/Checkbox.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";

import '@vuepic/vue-datepicker/dist/main.css';

import VueTimepicker from 'vue3-timepicker'
import 'vue3-timepicker/dist/VueTimepicker.css'
defineProps({
  teams: Array,
  game_role: Object
});
const form = useForm({
  time: "", 
  teams: [],
  game_role: "",
});

const dispatchActionTeamATeamB = () => {
  form.teams = '';
}
</script>

<template>
  <Head title="Crear nueva programación de partido" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('game_schedulings.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Crear nueva programación de partido</h1>
        <form @submit.prevent="form.post(route('game_schedulings.store'))" >
          <div class="mt-4">
            <InputLabel for="time" value="Hora" />
            <vue-timepicker 
              id="time"
              v-model="form.time"
              format="HH:mm"              
              :second-interval="60"
              :minute-interval="5"
              label="name"
              track-by="id"
              required
            />
            <InputError class="mt-2" :message="form.errors.time" />
          </div> 
          <div class="mt-4">
            <InputLabel for="game_role" value="Rol de partido" />
            <VueMultiselect
              id="game_role"
              v-model="form.game_role"
              :options="game_role"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              @select="dispatchActionTeamATeamB"
              placeholder="Escoge el rol de partido"
              label="name"
              track-by="id"
            />
            <InputError class="mt-2" :message="form.errors.game_role" />
          </div>
          <div class="mt-4 text-gray-700" v-if="form.game_role">
            <InputLabel for="tournament" value="Torneo - Categoría" />
            <span  id="tournament">{{  game_role.find(role => role.id === form.game_role.id)?.tournament?.name }} - </span>
            <span id="tournament">{{  game_role.find(role => role.id === form.game_role.id)?.tournament?.category?.name }}</span>
          </div> 
          <div class="mt-4">
              <InputLabel for="teams" value="Equipos" />
              <VueMultiselect
                  id="teams"
                  v-model="form.teams"
                  :options="form.game_role ? teams.filter(item => item.category?.id === game_role.find(role => role.id === form.game_role.id)?.tournament?.category?.id)
                    .map(item =>({
                      id: item.id,
                      name: `${item.name} - ${item.category?.name}`
                    }))
                  : []"
                  :multiple="true"            
                  :close-on-select="true"
                  :max="2"
                  placeholder="Escoge equipos"
                  label="name"
                  track-by="id"
              />
              <InputError class="mt-2" :message="form.errors.teams" />
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