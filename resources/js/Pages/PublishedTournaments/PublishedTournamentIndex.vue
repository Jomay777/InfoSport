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

</script>

<template>
  <Head title="Ver Rol de Partido" />

  <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white"
    >
    <div class="max-w-7xl mx-auto py-4">
      <div class="sm:fixed sm:top-0 sm:left-0 p-6 text-left">
        <Link
        rel="stylesheet" href="/"
          class="px-3 py-2 text-white font-semibold bg-green-500 hover:bg-green-700 rounded"
          >Back</Link
        >      
      </div>
      <div class="mt-6 ml-6 w-80">
      <InputLabel for="published_tournaments" value="Torneo" />
        <VueMultiselect
          class="w-full"
          id="published_tournaments"
          v-model="form.published_tournaments"
          :options="published_tournaments"
          :multiple="false"
          :close-on-select="true"
          :preselect-first="false"
          placeholder="Elige el torneo"
          label="name"              
          track-by="id"
        />
      <InputError class="mt-2" :message="form.errors.published_tournaments" />
      </div>
      <div class="mt-6 ml-6 w-80">
      <InputLabel for="game_roles" value="Rol de partidos" />
        <VueMultiselect
          class="w-full"
          id="game_roles"
          v-model="form.game_roles"
          :options="form.published_tournaments?.id ? game_roles
          .filter(item => item.tournament_id === form.published_tournaments.id)
           : []"
          :multiple="false"
          :close-on-select="true"
          :preselect-first="false"
          placeholder="Elige el rol de partidos"
          label="name"              
          track-by="id"
        />
      <InputError class="mt-2" :message="form.errors.game_roles" />
      </div>
      <!-- <div v-for="team in teams_a" :key="team">
        <div v-for="item in team.gameSchedulingsAsTeamA" :key="item">
          {{ item.id }}
        </div> 
      </div> -->
      <div v-if="form.game_roles" class="mt-6">  

      <Table>
        <template #header>
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
             :key="game_scheduling.id" class="border-b">

            <TableDataCell>         
              <div v-for="team in teams_a" :key="team.id" >
                <div v-for="item in team.gameSchedulingsAsTeamA" :key="item" class="flex items-center justify-between">
                  <span v-if="item?.id === game_scheduling.id">{{ team.name }}</span>
                  <img v-if="item?.id === game_scheduling.id" :src="team.club.logo_path" alt="Logo del equipo" class="w-10 h-10 ml-2" />
                </div>              
              </div>
            </TableDataCell>
            <TableDataCell> 
              vs  
            </TableDataCell>
            <TableDataCell>   
              <div v-for="team in teams_b" :key="team.id">
                <div v-for="item in team.gameSchedulingsAsTeamB" :key="item" class="flex items-center">                  
                  <img v-if="item?.id === game_scheduling.id" :src="team.club.logo_path" alt="Logo del equipo" class="w-10 h-10 mr-2" />
                  <span v-if="item?.id === game_scheduling.id" class="font-bold">{{ team.name }}</span>
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
    </div>  
  </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>