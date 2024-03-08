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
  postion_tables: Array,
  position_tables_by_tournament: Object,
  tournaments: Array,
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
      <InputLabel for="tournaments" value="Torneo" />
        <VueMultiselect
          class="w-full"
          id="tournaments"
          v-model="form.tournaments"
          :options="tournaments"
          :multiple="false"
          :close-on-select="true"
          :preselect-first="false"
          placeholder="Elige el torneo"
          label="name"              
          track-by="id"
        />
      <InputError class="mt-2" :message="form.errors.tournaments" />
      </div>

      <div v-if="form.tournaments" class="mt-6">      
      <Table>
        <template #header>
          <TableRow>              
            <TableHeaderCell>Equipo</TableHeaderCell>
            <TableHeaderCell>PJ</TableHeaderCell>
            <TableHeaderCell>G</TableHeaderCell>
            <TableHeaderCell>E</TableHeaderCell>
            <TableHeaderCell>P</TableHeaderCell>
            <TableHeaderCell>GF</TableHeaderCell>
            <TableHeaderCell>GC</TableHeaderCell>
            <TableHeaderCell>DG</TableHeaderCell>
            <TableHeaderCell>PTS</TableHeaderCell>
          </TableRow>
        </template>
        <template #default>
          <!-- form.game_roles.game_schedulings -->
          <TableRow v-for="(position_table_by_tournament, index) in position_tables_by_tournament[form.tournaments.id]"
             :key="position_table_by_tournament.id" class="border-b">
            <TableDataCell>         
              <div class="flex items-center">
                <img :src="position_table_by_tournament.team.club.logo_path" alt="Logo del equipo" class="w-10 h-10 mr-2 rounded-sm" />
                <span class="font-bold">{{ position_table_by_tournament.team.name }}</span>
              </div>              
            </TableDataCell>
            <TableDataCell> 
              {{ position_table_by_tournament.games_played }}
            </TableDataCell>
            <TableDataCell>   
              {{ position_table_by_tournament.games_won }}
            </TableDataCell>
            <TableDataCell>   
              {{ position_table_by_tournament.games_drawn }}
            </TableDataCell>
            <TableDataCell>   
              {{ position_table_by_tournament.games_lost }}
            </TableDataCell>
            <TableDataCell>   
              {{ position_table_by_tournament.goals_scored }}
            </TableDataCell>
            <TableDataCell>   
              {{ position_table_by_tournament.goals_against }} 
            </TableDataCell>
            <TableDataCell>   
              {{ position_table_by_tournament.goals_scored - position_table_by_tournament.goals_against }}
            </TableDataCell>
            <TableDataCell>   
              {{ position_table_by_tournament.points }} 
            </TableDataCell>
          </TableRow>
        </template>  
      </Table>
    </div>
    </div>  
  </div>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>