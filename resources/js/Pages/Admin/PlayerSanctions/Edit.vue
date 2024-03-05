<script>
  export default {
    name: 'PlayerSanctionEdit'
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

import { onMounted, ref, watch } from "vue";

import { parseISO, format } from 'date-fns';

const props = defineProps({
  player_sanction: {
    type: Object,
    required: true
  },
  games: Array,
  players: Array,
});


const form = useForm({
  sanction: props.player_sanction.sanction,
  state: [],
  yellow_cards: String(props.player_sanction.yellow_cards),
  red_card: String(props.player_sanction.red_card),
  games: ref([]),
  players: ref([]),
  _method: "put"
});

onMounted(() => {
  form.players = {id:props.player_sanction.player.id ,name:props.player_sanction?.player.first_name + ' ' + (props.player_sanction?.player.second_name ? props.player_sanction?.player.second_name + ' ' : '' ) + props.player_sanction?.player.last_name + ' ' + (props.player_sanction?.player.mother_last_name ? props.player_sanction?.player.mother_last_name + '- ' : '- ') + props.player_sanction?.player.team.name};
  form.state = (props.player_sanction.state === 'Activo' ? { id: 1 , name:props.player_sanction?.state}: '') || (props.player_sanction.state === 'Inactivo' ? { id: 2 , name:props.player_sanction?.state}: '') || (props.player_sanction.state === 'Suspendido' ? { id: 3 , name:props.player_sanction?.state} : '');
  form.games = {id: props.player_sanction?.game?.id, name: props.player_sanction?.game.game_scheduling?.teams?.map(team => team.name).join(' vs ') + ' - ' + props.player_sanction.game.game_scheduling?.game_role?.name + ' - ' + props.player_sanction?.game?.game_scheduling?.game_role?.tournament?.name};
});

const updatePlayerSanction= () => {
  form.post(route('player_sanctions.update', props.player_sanction?.id));
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
        <form @submit.prevent="updatePlayerSanction">        
          <div class="mt-4">
            <InputLabel for="players" value="Jugador - Equipo" />
            <VueMultiselect
              id="players"
              v-model="form.players"
              :options="players
                .filter(item => item.state === '2') // Utiliza '===' en lugar de '=' para comparar el estado
                .map(item => ({
                  id: item.id,
                  name: `${item.first_name} ${item.second_name ? item.second_name : ''} ${item.last_name} ${item.mother_last_name ? item.mother_last_name : ''}- ${item.team ? item.team.name : ''}`
                }))"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige el jugador"
              label="name"
              track-by="id"
              @select="dispatchAction"

              required
            />
            <InputError class="mt-2" :message="form.errors.players" />
          </div>
          <div class="mt-4">
            <InputLabel for="games" value="Equipos - Rol de Partido - Torneo" />
            <VueMultiselect
              id="games"
              v-model="form.games"           
              :options="form.players?.id ? games
                  .filter(item => {
                      // Filtrar los juegos que no tienen sanciones para el jugador seleccionado
                      return !item.player_sanctions.some(sanction => sanction.player_id === parseInt(form.players.id))
                          && item.game_scheduling.teams.some(team => team.players.some(player => player.id === parseInt(form.players.id)));
                  })
                  .map(item => ({
                      id: item.id, // Utiliza el ID del juego
                      name: `${item.game_scheduling.teams.map(team => team.name).join(' vs ')} - ${item.game_scheduling?.game_role ? item.game_scheduling.game_role.name : ''} - ${item.game_scheduling.game_role.tournament?.name}`
                  }))
                  : []"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
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
              autocomplete="player_sancionsanction"
            />
            <InputError class="mt-2" :message="form.errors.sanction" />
          </div>                    
          <div class="mt-4">
            <InputLabel for="yellow_cards" value="Tarjetas amarilas en el partido" />
            <TextInput
              id="yellow_cards"
              type="number"
              class="mt-1 block w-full"
              v-model="form.yellow_cards"              
              autocomplete="player_sanctionyellow_cards"
              required
              min="0"
              max="2"
            />
            <InputError class="mt-2" :message="form.errors.yellow_cards" />
          </div>  
          <div class="mt-4">
            <InputLabel for="red_card" value="Tarjeta roja" />
            <TextInput
              id="red_card"
              type="number"
              class="mt-1 block w-full"
              v-model="form.red_card"
              autocomplete="player_sanctionred_card"
              required
              min="0"
              max="1"
            />
            <InputError class="mt-2" :message="form.errors.red_card" />
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
