<script>
export default {  
  name: 'GameRoleShow',
  computed: {
    sortedGameSchedulings() {
      // Ordenar los partidos por la hora
      return this.game_role.game_schedulings.slice().sort((a, b) => {
        // Suponiendo que 'time' es una cadena en formato 'HH:mm:ss'
        return a.time.localeCompare(b.time);
      });
    }
  }
};
</script>
<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  game_role: {
    type: Object,
    required: true,
  }, 
  game_scheduling: {
    type: Array,
    required: true,
  }
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

const deleteGameRole = (id) => {
   form.delete(route('game_roles.destroy', id), {
    onSuccess: () => closeModal()
   });
}
</script>

<template>
  <Head title="Ver Rol de Partido" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('game_roles.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
        
        <Link
          :href="route('game_roles.publish', game_role)"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Publicar</Link
        >
      
      </div>
      
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-2xl font-bold text-navy-700 dark:text-white">
                    Rol de partido {{ game_role.name }}
                    </h4>                    
                </div> 
                <div class="grid grid-cols-2 gap-4 px-2 w-full">
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-1 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">ID</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ game_role.id }}
                    </p>
                    </div>

                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-1 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Fecha del rol de partidos</p>
                    <p  class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ game_role.date }}
                    </p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-1 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Cancha</p>
                    <p v-if="game_role.pitch" class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ game_role.pitch.name }}
                    </p>
                    <p v-else>Cancha no asignada</p>
                    </div>

                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-1 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Torneo</p>
                    <p v-if="game_role.tournament" class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ game_role.tournament.name }}
                    </p>
                    <p v-else>Torneo no asignada</p>
                    </div>
                    
                                       
                </div>
                <div class="mt-3 mb-8 px-2 w-full" v-if="game_role.game_schedulings">
                  <div v-for="game_scheduling in sortedGameSchedulings" :key="game_scheduling.id"
                    class="mt-3 rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">

                    <p class="text-m text-gray-600">Partido</p>

                    <p class="text-xl dark:text-navy">
                      <span class="inline-block w-1/2">{{ game_scheduling.team_a.name }} vs {{ game_scheduling.team_b.name  }}</span>
                      <span class="inline-block w-1/2 text-right">{{ game_scheduling.time }}</span>
                    </p>

                  </div>
                </div>


                 
                <div class="flex justify-center mt-6">
                    <Link :href="route('game_roles.edit', game_role.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeleteGameRole" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeleteGameRoleModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar el rol de partidos {{ game_role.name }}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deleteGameRole(game_role.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación de <span class="text-brand-500 font-bold">{{ game_role.name }}</span></p>  
        </div>
    </div>
  </AdminLayout>
</template>
