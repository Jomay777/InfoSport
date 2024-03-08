<script>
  export default {
    name: 'GameEdit'
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
  game: {
    type: Object,
    required: true
  },
  game_scheduling: Array,
  game_statistic: Object,
});


const form = useForm({
  result: '',
  observation: props.game.observation,
  //game_scheduling_id: props.game.game_scheduling_id,  
  game_scheduling: null,
  game_statistic: {
    goals_team_a: "",
    goals_team_b: "",
  },
  _method: "put"
});

onMounted(() => {
  form.game_scheduling = props.game?.game_scheduling;
  form.game_statistic.goals_team_a = props.game.game_statistic? String(props.game?.game_statistic?.goals_team_a): "";
  form.game_statistic.goals_team_b = props.game.game_statistic? String(props.game?.game_statistic?.goals_team_b): "";
  form.result = (props.game.result === 'Ganó A' ? { id: 1 , name:props.game?.result}: '') || (props.game.result === 'Ganó B' ? { id: 2 , name:props.game?.result}: '') || (props.game.result === 'Empate' ? { id: 3 , name:props.game?.result} : '') || (props.game.result === 'Ganó A por W.O.' ? { id: 4 , name:props.game?.result} : '')
  || (props.game.result === 'Ganó B por W.O.' ? { id: 5 , name:props.game?.result} : '')|| (props.game.result === 'Partido Cancelado' ? { id: 6 , name:props.game?.result} : '');

});

const updateGame= () => {
  form.post(route('games.update', props.game?.id));
};
</script>

<template>
  <Head title="Actualizar partido" />

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
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar partido</h1>
        <form @submit.prevent="updateGame">
         
          <div class="mt-4">            
            <InputLabel  value="Partido" />
            <span class=" flex justify-center text-lg font-bold">
<!--               {{ `${game.game_scheduling.teams.map(team => team.name).join(' vs ')} - ${game.game_scheduling.game_role.name}` }}
 -->
                {{ game.game_scheduling.teams[0]?.name }} vs {{ game.game_scheduling.teams[1]?.name }}
            </span>          
          </div>
          <div class="mt-4">
            <InputLabel for="result" value="Resultado" />
            <VueMultiselect
              id="result"
              v-model="form.result"
              :options="[{ id: 1, name: 'Ganó A' }, { id: 2, name: 'Ganó B' }, { id: 3, name: 'Empate' }
                        , { id: 4, name: 'Ganó A por W.O.' }, { id: 5, name: 'Ganó B por W.O.'}, { id: 5, name: 'Partido Cancelado'}]"
              :multiple="false"
              :preselect-first="true"
              placeholder="Resultado del partido"
              label="name"
              track-by="id"
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
