<script>
export default {  
  name: 'PublishedTournamentIndex',
  computed: {
    sortedGameSchedulin(g) {
      // Ordenar los partidos por la hora
      return g.slice().sort((a, b) => {
        // Suponiendo que 'time' es una cadena en formato 'HH:mm:ss'
        return a.time.localeCompare(b.time);
      });
    }
  },
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
import AdminLayout from "@/Layouts/AdminLayout.vue";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import GuestLayout from '@/Layouts/GuestLayout.vue';

import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";

import { ref, computed } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
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
  form.game_roles = ''
}
</script>

<template>
  <Head title="Ver Rol de Partido" />

  <div
        class="relative sm:flex sm:justify-center sm:items-center bg-gradient-to-b from-slate-100 to-gray-400 min-h-screen bg-center selection:bg-red-500 selection:text-white"
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
      <p class=" mt-4 block font-medium text-lg text-blue-900"> <span class="font-bold text-xl text-green-500">Fecha de Partidos:</span> {{ form.game_roles ? form.game_roles.date : '' }}</p>
      <p class=" mt-4 block font-medium text-lg text-blue-900"> <span class="font-bold text-xl text-green-500">Cancha:</span> {{ form.game_roles?.pitch ? form.game_roles?.pitch.name : '' }}</p>

      <div v-if="form.game_roles" class="mt-6" >  

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
             :key="game_scheduling.id" class="border-b bg-slate-200">

            <TableDataCell >         
              <div v-for="team in teams_a" :key="team.id" >
                <div v-for="item in team.gameSchedulingsAsTeamA" :key="item" class="flex items-center justify-between">
                  <span v-if="item?.id === game_scheduling.id" @click="handleClick(team)"
                  class="font-bold hover:text-green-500 cursor-pointer">{{ team.name }}</span>
                  <img hidden v-if="item?.id === game_scheduling.id" :src="team.club.logo_path" alt="Logo del equipo" class="md:block w-10 h-10 ml-2" />
                </div>              
              </div>
            </TableDataCell>
            <TableDataCell> 
              vs  
            </TableDataCell>
            <TableDataCell>   
              <div v-for="team in teams_b" :key="team.id">
                <div v-for="item in team.gameSchedulingsAsTeamB" :key="item" class="flex items-center">                  
                  <img hidden v-if="item?.id === game_scheduling.id" :src="team.club.logo_path" alt="Logo del equipo" class="md:block w-10 h-10 mr-2" />
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
      {{ team?.name }}
      <p class=" mt-4 block font-medium text-lg text-blue-900">
        <span class="font-bold text-xl text-green-500">Sanciones de los jugadores del equipo:</span> {{ team?.name }}
      </p>

      <div v-if="showPlayerSanctions && (form.game_roles ? form.game_roles : showPlayerSanctions = false )" class="mt-6" >  
        <Table class="rounded-sm">
          <template #header>
          <!--  class="bg-white" -->
          <TableRow>              
            <TableHeaderCell>Jugador</TableHeaderCell>
            <TableHeaderCell>Equipo</TableHeaderCell>
            <TableHeaderCell>Rol de Partido<br>Programación de Partido<br>Fecha</TableHeaderCell>
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
             :key="player_sanction.id" class="border-b bg-slate-200">

            <TableDataCell>         
              {{ player_sanction.player.first_name }} {{ player_sanction.player.second_name }} {{ player_sanction.player.last_name }} {{ player_sanction.player.mother_last_name }}
            </TableDataCell>
            <TableDataCell> 
              {{ player_sanction.player.team.name }}
            </TableDataCell>
            <TableDataCell>    
              <!-- {{ player_sanction.game.game_scheduling.game_role.tournament.name }} <br>   -->         
              {{ player_sanction.game.game_scheduling.game_role.name }} <br>
              {{ player_sanction.game.game_scheduling.team_a.name }} <br>
              vs<br>
              {{ player_sanction.game.game_scheduling.team_b.name }} <br>
              {{ player_sanction.game.game_scheduling.game_role.date }}
            </TableDataCell>
            <TableDataCell>   
              {{ player_sanction.yellow_cards }} 
            </TableDataCell>
            <TableDataCell>   
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