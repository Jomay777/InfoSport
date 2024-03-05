<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  team_sanction: {
    type: Object,
    required: true,
  },
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeleteTeamSanctionModal = ref(false)

const confirmDeleteTeamSanction = () => {
      showConfirmDeleteTeamSanctionModal.value = true;
}

const closeModal = () => {
  showConfirmDeleteTeamSanctionModal.value = false;
}

const deleteTeamSanction = (id) => {
   form.delete(route('team_sanctions.destroy', id), {
    onSuccess: () => closeModal()
   });
}

</script>

<template>
  <Head title="Ver Sanción de Equipo" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('team_sanctions.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-xl font-bold dark:text-white">
                      Sanción del equipo {{ team_sanction.team.name }}
                    </h4>                                      
                </div> 
                <div class="grid grid-cols-2 gap-4 px-2 w-full">
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Identificador</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ team_sanction.id }}
                    </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Estado</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ team_sanction.state }}
                    </p>
                    </div>                                    
              </div>
                <div class="mt-5 px-2 w-full">
                  <p class="text-xl text-gray-700 dark:text-white">Torneo</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy text-center">
                      {{  team_sanction.game.game_scheduling.game_role.tournament.name }}
                    </p>
                  </div>    
                  <br>
                  <p class="text-xl text-gray-700 dark:text-white">Rol de Paritido</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy text-center">
                      {{  team_sanction.game.game_scheduling.game_role.name }}
                    </p>
                  </div>  
                  <br>
                  <p class="text-xl text-gray-700 dark:text-white">Partido</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy text-center">                                      
                      {{ `${team_sanction.game.game_scheduling.teams.map(team => team.name).join(' vs ')}` }}
                    </p> 
                  </div> 
                </div>
                <div class="mt-5 px-2 w-full">
                  <p class="text-xl text-gray-700 dark:text-white">Sanción</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                      {{ team_sanction.sanction }}
                    </p> 
                  </div> 
                </div>
                <div class="mt-5 px-2 w-full">
                  <p class="text-xl text-gray-700 dark:text-white">Obsevación</p>
                  <div class="mt-3 flex justify-center">
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                      {{ team_sanction.observation }}
                    </p> 
                  </div> 
                </div>
                
                <div class="flex justify-center mt-6">
                    <Link :href="route('team_sanctions.edit', team_sanction.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeleteTeamSanction" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeleteTeamSanctionModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar la sanción del equipo {{ team_sanction.team.name }}</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deleteTeamSanction(team_sanction.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación de la sanción del equipo <span class="text-brand-500 font-bold">{{ team_sanction.team.name }}</span></p>  
        </div>
    </div>
  </AdminLayout>
</template>
