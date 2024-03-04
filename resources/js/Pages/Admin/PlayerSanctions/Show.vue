<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  player_sanction: {
    type: Object,
    required: true,
  }, 
  player_sanctions: Array
  /* player_sanction_scheduling: {
    type: Array,
  },
  game_statistic: {
    type: Object,
  } */
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeletePlayerSanctionModal = ref(false)

const confirmDeletePlayerSanction = () => {
      showConfirmDeletePlayerSanctionModal.value = true;
}

const closeModal = () => {
  showConfirmDeletePlayerSanctionModal.value = false;
}

const deletePlayerSanction = (id) => {
   form.delete(route('player_sanctions.destroy', id), {
    onSuccess: () => closeModal()
   });
}

const totalYellowCards = (playerId, tournamentId, player_sanctions) => {
    let totalYellows = 0;

    for (const player_sanction of player_sanctions) { // <-- Aquí cambia el nombre
        if (player_sanction.player_id === playerId && 
            player_sanction.game.game_scheduling.game_role.tournament.id === tournamentId) {
            totalYellows += player_sanction.yellow_cards;
        }
    }
    
    return totalYellows;
}

</script>

<template>
  <Head title="Ver Sanción de Jugador" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('player_sanctions.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-xl font-bold dark:text-white">
                    Sanción del jugador {{ player_sanction.player.first_name }} {{ player_sanction.player.second_name }} {{ player_sanction.player.last_name }} {{ player_sanction.player.mother_last_name }}                  
                    </h4>                                      
                </div> 
                <div class="grid grid-cols-2 gap-4 px-2 w-full">
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Identificador</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player_sanction.id }}
                    </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Equipo</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player_sanction.player.team.name }}
                    </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Tarjetas Amarillas</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player_sanction.yellow_cards }}
                    </p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                      <p class="text-sm text-gray-600">Tarjeta Roja</p>
                      <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player_sanction.red_card }}
                      </p>
                    </div>                                   
                   
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                      <p class="text-sm text-gray-600">Estado</p>
                      <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player_sanction.state}}
                      </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Tarjetas Amarillas Acumuladas</p>
                    <p  class="text-base font-medium text-navy-700 dark:text-navy">
                      {{ totalYellowCards(player_sanction.player.id, player_sanction.game.game_scheduling.game_role.tournament.id, player_sanctions) }}
                    </p>
                    </div>                   
              </div>
                <div class="mt-5 px-2 w-full">
                  <p class="text-xl text-gray-700 dark:text-white">Torneo</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy text-center">
                      {{  player_sanction.game.game_scheduling.game_role.tournament.name }}
                    </p>
                  </div>    
                  <br>
                  <p class="text-xl text-gray-700 dark:text-white">Rol de Paritido</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy text-center">
                      {{  player_sanction.game.game_scheduling.game_role.name }}
                    </p>
                  </div>  
                  <br>
                  <p class="text-xl text-gray-700 dark:text-white">Partido</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy text-center">                                      
                      {{ `${player_sanction.game.game_scheduling.teams.map(team => team.name).join(' vs ')}` }}
                    </p> 
                  </div> 
                </div>
                <div class="mt-5 px-2 w-full">
                  <p class="text-xl text-gray-700 dark:text-white">Sanción</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                      {{ player_sanction.sanction }}
                    </p> 
                  </div> 
                </div>
                
                <div class="flex justify-center mt-6">
                    <Link :href="route('player_sanctions.edit', player_sanction.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeletePlayerSanction" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeletePlayerSanctionModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar al partido /@ {{ `${game.game_scheduling.teams.map(team => team.name).join(' vs ')} - ${game.game_scheduling.game_role.name}` }}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deletePlayerSanction(player_sanction.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación de la sanción del jugador <span class="text-brand-500 font-bold">X</span></p>  
        </div>
    </div>
  </AdminLayout>
</template>
