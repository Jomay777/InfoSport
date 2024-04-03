<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";

defineProps({
  games: Array,
  teams: Array
});
const form = useForm({
  sanction: "",
  observation: "",
  state: "",

  games: [],
  teams: [],
});
const storeTeamSanction = () =>{
//  form.game_scheduling_id = form.game_scheduling ? form.game_scheduling.id : null;

  form.post(route('team_sanctions.store'));
}

const dispatchAction = () => {
  form.games = ''
}

</script>

<template>
  <Head title="Crear nueva sanción al jugador" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('team_sanctions.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Crear nueva sanción al equipo</h1>
        <form @submit.prevent="storeTeamSanction">
          <div class="mt-4">
            <InputLabel for="teams" value="Equipo" />
            <VueMultiselect
              id="teams"
              v-model="form.teams"
              :options="teams"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Elige el equipo"
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
                      // Filtrar los juegos que no tienen sanciones para el equipo seleccionado
                      return !item.team_sanctions.some(sanction => sanction.team_id === parseInt(form.teams.id))
                      && (item.game_scheduling.team_a.id === parseInt(form.teams.id) || item.game_scheduling.team_b.id === parseInt(form.teams.id));
                  })
                  .map(item => ({
                      id: item.id, // Utiliza el ID del juego
                      name: `${item.game_scheduling.team_a.name} vs ${item.game_scheduling.team_b.name} - ${item.game_scheduling?.game_role ? item.game_scheduling.game_role.name : ''} - ${item.game_scheduling.game_role.tournament?.name}`
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
              Crear
            </PrimaryButton>
          </div>
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>