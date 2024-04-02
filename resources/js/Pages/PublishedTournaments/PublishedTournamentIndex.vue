<script>
export default {  
  name: 'PublishedTournamentIndex',
  data() {
    return {
      showPlayerSanctions: false,
      team: null // Variable para almacenar el equipo seleccionado
    };
  },
  methods: {
    handleClick(team) {
      // Cambiar el estado de la variable showPlayerSanctions
      this.showPlayerSanctions = true;
      // Actualizar el equipo seleccionado
      this.team = team;
    }
  }  
};
</script>
<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

import { ref, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import VueMultiselect from "vue-multiselect";

const props = defineProps({
  published_tournaments: Array,
  teams_a: Array,
  teams_b: Array,
  game_roles: Array,
  game_schedulings: Array,
  player_sanctions: Array,
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeleteGameRoleModal = ref(false)

const confirmDeleteGameRole = () => {
      showConfirmDeleteGameRoleModal.value = true;
}

const closeModal = () => {
  showConfirmDeleteGameRoleModal.value = false;
}
const dispatchAction = () => {
  form.game_roles = '';
}

</script>

<template>
  <Head title="Ver Rol de Partido" />

  <div
        class="relative sm:flex sm:justify-center sm:items-center bg-gradient-to-b from-slate-100 to-gray-300 min-h-screen bg-center selection:bg-red-500 selection:text-white"
    >

    <div class="max-w-7xl mx-auto py-4">
      <div class="sm:fixed sm:top-0 sm:left-0 p-6 text-left z-20">
        <Link
        rel="stylesheet" href="/"
          class="px-3 py-2 text-white font-semibold bg-green-500 hover:bg-blue-900 hover:text-green-500 rounded z-20"
          >Back</Link
        >      
      </div>
      <div class="mt-6 ml-6 w-80  text-6xlz-10">
      <InputLabel for="published_tournaments" value="Torneo"
        class="text-blue-900 z-50 inline-block" />
        <VueMultiselect
          class="w-full"
          id="published_tournaments"
          v-model="form.published_tournaments"
          :options="published_tournaments"
          :multiple="false"
          :close-on-select="true"
          :preselect-first="true"
          @select="dispatchAction"
          placeholder="Elige el torneo"
          label="name"              
          track-by="id"
        />
      <InputError class="mt-2" :message="form.errors.published_tournaments" />
      </div>
      <div class="mt-6 ml-6 w-80">
      <InputLabel for="game_roles" value="Rol de partidos"
        class="text-green-500" />
        <VueMultiselect
          class="w-full"
          id="game_roles"
          v-model="form.game_roles"
          :options="form.published_tournaments?.id ? game_roles
          .filter(item => item.tournament_id === form.published_tournaments.id)
           : []"
          :multiple="false"
          :close-on-select="true"
          :preselect-first="true"
          placeholder="Elige el rol de partidos"
          label="name"              
          track-by="id"
        />
      <InputError class="mt-2" :message="form.errors.game_roles" />
      </div>
      <p class=" mt-4 block font-medium text-lg text-blue-900"> <span class="font-bold text-xl text-green-500">Fecha de Partidos:</span> {{ form.game_roles ? form.game_roles.date + '.' : '' }}</p>
      <p class=" mt-4 block font-medium text-lg text-blue-900"> <span class="font-bold text-xl text-green-500">Cancha:</span> {{ form.game_roles?.pitch ? form.game_roles?.pitch.name + '.' : '' }}</p>

      <div v-if="form.game_roles" class="mt-3" >  

      <Table class="rounded-sm">
        <template #header>
          <!--  class="bg-white" -->
          <TableRow>              
            <TableHeaderCell>Equipo A</TableHeaderCell>
            <TableHeaderCell>vs</TableHeaderCell>
            <TableHeaderCell>Equipo B</TableHeaderCell>
            <TableHeaderCell>Hora</TableHeaderCell>
          </TableRow>
        </template>
        <template #default>
          <!-- form.game_roles.game_schedulings -->
          <TableRow v-for="(game_scheduling, index) in game_schedulings.filter(item => item.game_role_id === form.game_roles.id).slice().sort(((a, b) => {
                return a.time.localeCompare(b.time);
              }))"
             :key="game_scheduling.id" class="border-b bg-gradient-to-b from-blue-100 to-blue-50">

            <TableDataCell  class="text-gray-600">         
              <div v-for="team in teams_a" :key="team.id" >
                <div v-for="item in team.gameSchedulingsAsTeamA" :key="item" class="flex items-center justify-between">
                  <span v-if="item?.id === game_scheduling.id" @click="handleClick(team)"
                  class="font-bold hover:text-green-500 cursor-pointer">{{ team.name }}</span>
                  <img hidden v-if="item?.id === game_scheduling.id" :src="team.club.logo_path" alt="Logo del club" class="md:block w-10 h-10 ml-2" />
                </div>              
              </div>
            </TableDataCell>
            <TableDataCell class="text-red-500" >
              vs  
            </TableDataCell>
            <TableDataCell class="text-gray-600">   
              <div v-for="team in teams_b" :key="team.id">
                <div v-for="item in team.gameSchedulingsAsTeamB" :key="item" class="flex items-center">                  
                  <img hidden v-if="item?.id === game_scheduling.id" :src="team.club.logo_path" alt="Logo del club" class="md:block w-10 h-10 mr-2" />
                  <span v-if="item?.id === game_scheduling.id" @click="handleClick(team)" 
                  class="font-bold hover:text-green-500 cursor-pointer" >{{ team.name }}</span>
                </div>
              </div>              
            </TableDataCell>
            <TableDataCell>   
              {{ game_scheduling.time }} 
            </TableDataCell>
          </TableRow>
        </template>  
      </Table>
      </div>         
      <!-- Sanción de Jugadores de un equipo seleccionado -->
      <div v-if="showPlayerSanctions && (form.game_roles ? form.game_roles : showPlayerSanctions = false ) 
        && (player_sanctions
            .filter(item => item.game.game_scheduling.game_role.tournament.id === form.published_tournaments.id 
            && item.player.team.id === team?.id 
            && item.game.game_scheduling.game_role.date < form.game_roles.date
            && item.state === 'Activo').length)" class="mt-6" > 
        <p class=" mt-4 mb-3 block font-medium text-lg text-blue-900">
          <span class="text-xl text-gray-900">Jugadores del equipo:  <span class="font-bold text-green-800">{{ team?.name }}</span> que fueron<span class="text-red-500"> sancionados</span> </span> 
          <span class="text-gray-900"> en partidos anteriores.</span>
        </p> 
        <Table class="rounded-sm"> 
          <template #header>
          <!--  class="bg-white" -->
          <TableRow>              
            <TableHeaderCell>Jugador</TableHeaderCell>
            <TableHeaderCell>Equipo</TableHeaderCell>
            <TableHeaderCell class="text-center">
              <span class="text-blue-900">
                Rol de Partido<br>
              </span>
              <span class="text-green-600">Fecha<br></span>
              <span class="text-blue-900">Partido</span>              
            </TableHeaderCell>
            <TableHeaderCell>Amarillas</TableHeaderCell>
            <TableHeaderCell>Roja</TableHeaderCell>
          </TableRow>
        </template>
        <template #default>
          <!-- form.game_roles.game_schedulings -->
          <TableRow v-for="(player_sanction, index) in player_sanctions
            .filter(item => item.game.game_scheduling.game_role.tournament.id === form.published_tournaments.id 
            && item.player.team.id === team?.id 
            && item.game.game_scheduling.game_role.date < form.game_roles.date
            && item.state === 'Activo').slice()
            .sort((a, b) => {
                // Ordenar por fecha de manera descendente (de mayor a menor)
                return new Date(b.game.game_scheduling.game_role.date) - new Date(a.game.game_scheduling.game_role.date);
              })"
             :key="player_sanction.id" class="border-b bg-gradient-to-b from-blue-100 to-blue-50">

            <TableDataCell>         
              {{ player_sanction.player.first_name }} {{ player_sanction.player.second_name }} {{ player_sanction.player.last_name }} {{ player_sanction.player.mother_last_name }}
            </TableDataCell>
            <TableDataCell> 
              {{ player_sanction.player.team.name }}
            </TableDataCell>
            <TableDataCell>    
              <!-- {{ player_sanction.game.game_scheduling.game_role.tournament.name }} <br>   -->   
              <div class="text-center">
                <p class="text-blue-900">
                  {{ player_sanction.game.game_scheduling.game_role.name }} <br>  
                </p>
                <p class="text-green-600">
                  {{ player_sanction.game.game_scheduling.game_role.date }} <br>
                </p>
                <p class="text-blue-900">
                  {{ player_sanction.game.game_scheduling.team_a.name }} <br>
                  <span class="text-red-500">vs</span><br>
                  {{ player_sanction.game.game_scheduling.team_b.name }} 
                </p>      
              </div>                   
            </TableDataCell>
            <TableDataCell class="text-yellow-600">   
              {{ player_sanction.yellow_cards }} 
            </TableDataCell>
            <TableDataCell class="text-red-500">   
              {{ player_sanction.red_card }} 
            </TableDataCell>
          </TableRow>          
        </template> 
        </Table>
      </div> 
      <div v-else-if="showPlayerSanctions && (form.game_roles ? form.game_roles : showPlayerSanctions = false ) ">
        <p class="mt-4 text-red-500 italic">
          No hay Jugadores sancionados del equipo {{ team?.name }}
        </p>            
      </div>        
      <div v-if="form.game_roles && (player_sanctions
                  .filter(item =>
                      (game_roles
                          .filter(role => role.id !== form.game_roles.id)
                          .filter(role => new Date(role.date) <= new Date(form.game_roles.date))
                          .sort((a, b) => new Date(b.date) - new Date(a.date)) 
                          .map(role => role.date)[0] === item.game.game_scheduling.game_role.date)
                      &&
                      (form.game_roles?.game_schedulings.some(schedule =>
                          item.player.team.id === schedule.team_a.id || item.player.team.id === schedule.team_b.id
                      ))
                  ).map(item => item.player).length !== 0) " class="mt-6" > 
        <p class=" mt-4 mb-3 block font-medium text-lg text-blue-900">          
          <span class="font-bold text-xl text-gray-800">Jugadores que recibieron tarjeta <span class=" text-red-500">roja</span> en su partido anterior</span>
        </p> 
        <Table class="rounded-sm"> 
          <template #header>
          <TableRow>              
            <TableHeaderCell>Jugador</TableHeaderCell>
            <TableHeaderCell>Equipo</TableHeaderCell>
            <TableHeaderCell class="text-center">              
              <span class="text-blue-900">Partido</span>              
            </TableHeaderCell>
            <TableHeaderCell>Roja</TableHeaderCell>
          </TableRow>
        </template>
        <template #default>
          <TableRow v-for="(player_sanction, index) in 
              player_sanctions
                  .filter(item =>
                      (game_roles
                          .filter(role => role.id !== form.game_roles.id)
                          .filter(role => new Date(role.date) <= new Date(form.game_roles.date))
                          .sort((a, b) => new Date(b.date) - new Date(a.date)) 
                          .map(role => role.date)[0] === item.game.game_scheduling.game_role.date)
                      &&
                      (form.game_roles?.game_schedulings.some(schedule =>
                          item.player.team.id === schedule.team_a.id || item.player.team.id === schedule.team_b.id
                      ))
                  )"
             :key="player_sanction.id" class="border-b bg-gradient-to-b from-blue-100 to-blue-50">

            <TableDataCell>         
              {{ player_sanction.player.first_name }} {{ player_sanction.player.second_name }} {{ player_sanction.player.last_name }} {{ player_sanction.player.mother_last_name }}
            </TableDataCell>
            <TableDataCell> 
              {{ player_sanction.player.team.name }}
            </TableDataCell>
            <TableDataCell>    
              <div class="text-center">
                <p class="text-blue-900">
                  {{ player_sanction.game.game_scheduling.game_role.name }} <br>  
                </p>
                <p class="text-green-600">
                  {{ player_sanction.game.game_scheduling.game_role.date }} <br>
                </p>
                <p class="text-blue-900">
                  {{ player_sanction.game.game_scheduling.team_a.name }} <br>
                  <span class="text-red-500">vs</span><br>
                  {{ player_sanction.game.game_scheduling.team_b.name }} 
                </p>      
              </div>                   
            </TableDataCell>

            <TableDataCell class="text-red-500">   
              {{ player_sanction.red_card }} 
            </TableDataCell>
          </TableRow>          
        </template> 
        </Table>
      </div>   
    </div>      
  </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>