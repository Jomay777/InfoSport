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
  game_scheduling: Array,
  game_statistic: Array
});
const form = useForm({
  result: "",
  observation: "",
  //game_scheduling_id: null,
  game_scheduling: null,
  game_statistic: {
    goals_team_a: "",
    goals_team_b: "",
    yellow_cards_a: "",
    yellow_cards_b: "",
    red_cards_a: "",
    red_cards_b: ""
  }
});
const storeGame = () =>{
//  form.game_scheduling_id = form.game_scheduling ? form.game_scheduling.id : null;

  form.post(route('games.store'));
}
</script>

<template>
  <Head title="Crear nuevo partido" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('games.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Crear nuevo partido</h1>
        <form @submit.prevent="storeGame">
          <div class="mt-4">
            <InputLabel for="game_scheduling" value="Equipo" />
            <VueMultiselect
              id="game_scheduling"
              v-model="form.game_scheduling"
              :options="game_scheduling
              .filter(item => !item.game) // Filtra las game_schedulings sin juego asociado
              .map(item => ({
                id: item.id,
                name: `${item.teams.map(team => team.name).join(' vs ')} - ${item.gameRole ? item.gameRole.name : ''}`
              }))"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige la programación de equipo"
              label="name"
              track-by="id"
            />
            <InputError class="mt-2" :message="form.errors.game_scheduling" />
          </div>
          <div class="mt-4">            
            <InputLabel for="result" value="Resultado" />
            <TextInput
              id="result"
              type="text"
              class="mt-1 block w-full"
              v-model="form.result"
              autofocus
              required
              autocomplete="gameresult"
            />
            <InputError class="mt-2" :message="form.errors.result" />
          </div>
          <div class="mt-4">
            <InputLabel for="observation" value="Observación" />
            <TextInput
              id="observation"
              type="text"
              class="mt-1 block w-full"
              v-model="form.observation"              
              autocomplete="gameobservation"
            />
            <InputError class="mt-2" :message="form.errors.observation" />
          </div>
                         
          
          <div class="mt-4">
            <InputLabel for="game_statistic.goals_team_a" value="Goles del equipo A" />
            <TextInput
              id="game_statistic.goals_team_a"
              type="number"
              class="mt-1 block w-full"
              v-model="form.game_statistic.goals_team_a"              
              autocomplete="gamegame_statistic.goals_team_a"
              required
              min="0"
            />
            <InputError class="mt-2" :message="form.errors.goals_team_a" />
          </div>
          <div class="mt-4">
            <InputLabel for="game_statistic.goals_team_b" value="Goles del equipo B" />
            <TextInput
              id="game_statistic.goals_team_b"
              type="number"
              class="mt-1 block w-full"
              v-model="form.game_statistic.goals_team_b"              
              autocomplete="gamegame_statistic.goals_team_b"
              required
              min="0"
            />
          </div>
          <div class="mt-4">
            <InputLabel for="game_statistic.yellow_cards_a" value="Tarjetas amarillas del equipo A" />
            <TextInput
              id="game_statistic.yellow_cards_a"
              type="number"
              class="mt-1 block w-full"
              v-model="form.game_statistic.yellow_cards_a"              
              autocomplete="gamegame_statistic.yellow_cards_a"
              required
              min="0"
            />
          </div>
          <div class="mt-4">
            <InputLabel for="game_statistic.yellow_cards_b" value="Tarjetas amarillas del equipo B" />
            <TextInput
              id="game_statistic.yellow_cards_b"
              type="number"
              class="mt-1 block w-full"
              v-model="form.game_statistic.yellow_cards_b"              
              autocomplete="gamegame_statistic.yellow_cards_b"
              required
              min="0"
            />
          </div>
          <div class="mt-4">
            <InputLabel for="game_statistic.red_cards_a" value="Tarjetas rojas del equipo A" />
            <TextInput
              id="game_statistic.red_cards_a"
              type="number"
              class="mt-1 block w-full"
              v-model="form.game_statistic.red_cards_a"              
              autocomplete="gamegame_statistic.red_cards_a"
              required
              min="0"
            />
          </div>
          <div class="mt-4">
            <InputLabel for="game_statistic.red_cards_b" value="Tarjetas rojas del equipo B" />
            <TextInput
              id="game_statistic.red_cards_b"
              type="number"
              class="mt-1 block w-full"
              v-model="form.game_statistic.red_cards_b"              
              autocomplete="gamegame_statistic.red_cards_b"
              required
              min="0"
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