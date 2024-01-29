<script>
export default {  
  name: 'Dashboard',
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
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import GameRoleShow from "@/Pages/Admin/GameRoles/Show.vue";

components: {
  GameRoleShow
}
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
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Bienvenido!</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class=" mt-5 flex flex-col justify-center items-center ">
                        <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                            <div class="mt-2 mb-8  text-gray-700 w-full">
                                <h4 class=" mt-5 px-2 text-2xl font-bold text-navy-700 dark:text-white">
                                Rol de partidos {{ game_role.name }}
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
                                <span class="inline-block w-1/2">{{ game_scheduling.teams.map(team => team.name).join(' vs ') }}</span>
                                <span class="inline-block w-1/2 text-right">{{ game_scheduling.time }}</span>
                                </p>

                            </div>
                            </div>
                
                        </div>  
                    <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Rol de partidos <a href="https://horizon-ui.com?ref=tailwindcomponents.com" target="_blank" class="text-brand-500 font-bold">{{ game_role.name }}</a></p>  
                    </div>
                    
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
