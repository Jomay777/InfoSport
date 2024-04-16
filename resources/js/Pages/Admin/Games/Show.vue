<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  game: {
    type: Object,
    required: true,
  }, 
  /* game_scheduling: {
    type: Array,
  },
  game_statistic: {
    type: Object,
  } */
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeleteGameModal = ref(false)

const confirmDeleteGame = () => {
      showConfirmDeleteGameModal.value = true;
}

const closeModal = () => {
  showConfirmDeleteGameModal.value = false;
}

const deleteGame = (id) => {
   form.delete(route('games.destroy', id), {
    onSuccess: () => closeModal()
   });
}

</script>

<template>
  <Head title="Ver Partido" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('games.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-xl font-bold ">
                    Partido {{ `${game.game_scheduling.team_a.name} vs ${game.game_scheduling.team_b.name} - ${game.game_scheduling.game_role.name}` }}
                    </h4>                                      
                </div> 
                <div class="grid grid-cols-2 gap-4 px-2 w-full">
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                    <p class="text-sm text-gray-600">Identificador</p>
                    <p class="text-base font-medium text-navy-700 ">
                        {{ game.id }}
                    </p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                      <p class="text-sm text-gray-600">Resultado</p>
                      <p class="text-base font-medium text-navy-700 ">
                          {{ game.result}}
                      </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                    <p class="text-sm text-gray-600">Goles del equipo A</p>
                    <p v-if="game.game_statistic" class="text-base font-medium text-navy-700 ">
                        {{ game.game_statistic.goals_team_a }}
                    </p>
                    <p v-else>Goles no registrados</p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                      <p class="text-sm text-gray-600">Goles del equipo B</p>
                      <p v-if="game.game_statistic" class="text-base font-medium text-navy-700 ">
                        {{ game.game_statistic.goals_team_b }}
                      </p>
                      <p v-else>Goles no registrados</p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                    <p class="text-sm text-gray-600">Tarjetas amarillas del equipo A</p>
                    <p v-if="game.game_statistic" class="text-base font-medium text-navy-700 ">
                        {{ game.game_statistic.yellow_cards_a }}
                    </p>
                    <p v-else>Número de tarjetas no registrado</p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                      <p class="text-sm text-gray-600">Tarjetas amarillas del equipo B</p>
                      <p v-if="game.game_statistic" class="text-base font-medium text-navy-700 ">
                        {{ game.game_statistic.yellow_cards_b }}
                    </p>
                    <p v-else>Número de tarjetas no registrado</p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                    <p class="text-sm text-gray-600">Tarjetas rojas del equipo A</p>
                    <p v-if="game.game_statistic" class="text-base font-medium text-navy-700 ">
                        {{ game.game_statistic.red_cards_a }}
                    </p>
                    <p v-else>Número de tarjetas no registrado</p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 ">
                      <p class="text-sm text-gray-600">Tarjetas rojas del equipo B</p>
                      <p v-if="game.game_statistic" class="text-base font-medium text-navy-700 ">
                        {{ game.game_statistic.red_cards_b }}
                    </p>
                    <p v-else>Número de tarjetas no registrado</p>
                    </div>  
                                                 
                </div>
                <div class="mt-5 px-2 w-full">
                  <p class="text-xl text-gray-700 ">Observación</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 ">
                        {{ game.observation}}
                    </p> 
                  </div> 
                </div>
             
                
                <div class="flex justify-center mt-6">
                    <Link :href="route('games.edit', game.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeleteGame" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeleteGameModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 ">¿Está seguro de eliminar el partido {{ `${game.game_scheduling.team_a.name} vs ${game.game_scheduling.team_b.name} - ${game.game_scheduling.game_role.name}` }}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deleteGame(game.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación del <span class="text-brand-500 font-bold">Partido</span></p>  
        </div>
    </div>
  </AdminLayout>
</template>
