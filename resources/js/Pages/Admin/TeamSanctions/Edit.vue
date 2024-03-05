<script>
  export default {
    name: 'TeamSanctionEdit'
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


const props = defineProps({
  team_sanction: {
    type: Object,
    required: true
  },
  games: Array,
  teams: Array,
});


const form = useForm({
  sanction: props.team_sanction.sanction,
  state: [],
  observation: String(props.team_sanction.observation),
  games: ref([]),
  teams: ref([]),
  _method: "put"
});

onMounted(() => {
  form.teams = {id:props.team_sanction.team.id ,name:props.team_sanction?.team.name};
  form.state = (props.team_sanction.state === 'Activo' ? { id: 1 , name:props.team_sanction?.state}: '') || (props.team_sanction.state === 'Inactivo' ? { id: 2 , name:props.team_sanction?.state}: '') || (props.team_sanction.state === 'Suspendido' ? { id: 3 , name:props.team_sanction?.state} : '');
  form.games = {id: props.team_sanction?.game?.id, name: props.team_sanction?.game.game_scheduling?.teams?.map(team => team.name).join(' vs ') + ' - ' + props.team_sanction.game.game_scheduling?.game_role?.name + ' - ' + props.team_sanction?.game?.game_scheduling?.game_role?.tournament?.name};
});

const updateTeamSanction= () => {
  form.post(route('team_sanctions.update', props.team_sanction?.id));
};
const dispatchAction = () => {
  form.games = {id:'', name:''}
}

</script>

<template>
  <Head title="Actualizar sanción de jugador" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('player_sanctions.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar sanción de jugador</h1>
        <form @submit.prevent="updateTeamSanction">        
          <div class="mt-4">
            <InputLabel for="teams" value=" Equipo" />
            <VueMultiselect
              id="teams"
              v-model="form.teams"
              :options="teams"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige el jugador"
              label="name"
              track-by="id"
              @select="dispatchAction"

              required
            />
            <InputError class="mt-2" :message="form.errors.teams" />
          </div>
          <div class="mt-4">
            <InputLabel for="games" value="Equipos - Rol de Partido - Torneo" />
            <VueMultiselect
              id="games"
              v-model="form.games"           
              :options="form.teams?.id ? games
                  .filter(item => {
                      // Filtrar los juegos que no tienen sanciones para el jugador seleccionado
                      return !item.team_sanctions.some(sanction => sanction.team_id === parseInt(form.teams.id))
                          && item.game_scheduling.teams.some(team => team.id === parseInt(form.teams.id));
                  })
                  .map(item => ({
                      id: item.id, // Utiliza el ID del juego
                      name: `${item.game_scheduling.teams.map(team => team.name).join(' vs ')} - ${item.game_scheduling?.game_role ? item.game_scheduling.game_role.name : ''} - ${item.game_scheduling.game_role.tournament?.name}`
                  }))
                  : []"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="false"
              placeholder="Elige el partido"
              label="name"
              track-by="id"
              required
            />
            <InputError class="mt-2" :message="form.errors.games" />
          </div>
          <div class="mt-4">
            <InputLabel for="state" value="Estado de la sanción" />
            <VueMultiselect
              id="state"
              v-model="form.state"
              :options="[{ id: 1, name: 'Activo' }, { id: 2, name: 'Inactivo' }, { id: 3, name: 'Suspendido' }]"
              :multiple="false"             
              :preselect-first="true"
              placeholder="Estado de la sanción"
              label="name"
              track-by="id"
            />
            <InputError class="mt-2" :message="form.errors.state" />
          </div>   
          <div class="mt-4">            
            <InputLabel for="sanction" value="Sanción" />
            <TextInput
              id="sanction"
              type="text"
              class="mt-1 block w-full"
              v-model="form.sanction"
              autofocus
              required
              autocomplete="team_sancionsanction"
            />
            <InputError class="mt-2" :message="form.errors.sanction" />
          </div>                    
          <div class="mt-4">
            <InputLabel for="observation" value="Observación" />
            <TextInput
              id="observation"
              type="text"
              class="mt-1 block w-full"
              v-model="form.observation"              
              autocomplete="team_sanctionobservation"
              required
            />
            <InputError class="mt-2" :message="form.errors.observation" />
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
