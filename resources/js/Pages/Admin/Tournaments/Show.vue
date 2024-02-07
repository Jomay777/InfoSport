<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  tournament: {
    type: Object,
    required: true,
  }, 
  category: {
    type: Object,
    required: true,
  }
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeleteTournamentModal = ref(false)

const confirmDeleteTournament = () => {
      showConfirmDeleteTournamentModal.value = true;
}

const closeModal = () => {
  showConfirmDeleteTournamentModal.value = false;
}

const deleteTournament = (id) => {
   form.delete(route('tournaments.destroy', id), {
    onSuccess: () => closeModal()
   });
}
</script>

<template>
  <Head title="Ver Torneo" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('tournaments.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-xl font-bold text-navy-700 dark:text-white">
                    Torneo {{ tournament.name }}
                    </h4>                    
                </div> 
                <div class="grid grid-cols-2 gap-4 px-2 w-full">
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Identificador</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ tournament.id }}
                    </p>
                    </div>

                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Categoría</p>
                    <p v-if="tournament.category" class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ tournament.category.name }}
                    </p>
                    <p v-else>Categoría no asignada</p>
                    </div>

                                       
                </div>
                <div class="mt-3 mb-8 px-2 w-full">
                  <div class="  rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none ">
                    <p class="text-sm text-gray-600">Descripción</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">                  
                      <span>
                      {{ tournament.description }}
                      <br> 
                      </span>                                                                    
                    </p>
                    
                    </div> 
                </div>
                <div class="flex justify-center mt-6">
                    <Link :href="route('tournaments.edit', tournament.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeleteTournament" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeleteTournamentModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar el torneo {{ tournament.name }}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deleteTournament(tournament.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación de <span class="text-brand-500 font-bold">{{ tournament.name }}</span></p>  
        </div>
    </div>
  </AdminLayout>
</template>
