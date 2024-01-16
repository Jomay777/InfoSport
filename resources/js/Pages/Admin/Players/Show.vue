<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  player: {
    type: Object,
    required: true,
  }, 
  team: {
    type: Object,
  },
  photo_player: {
    type: Object,
  }
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeletePlayerModal = ref(false)

const confirmDeletePlayer = () => {
      showConfirmDeletePlayerModal.value = true;
}

const closeModal = () => {
  showConfirmDeletePlayerModal.value = false;
}

const deletePlayer = (id) => {
   form.delete(route('players.destroy', id), {
    onSuccess: () => closeModal()
   });
}
</script>

<template>
  <Head title="Ver Jugador" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('players.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-xl font-bold dark:text-white">
                    Jugador {{ player.first_name }} {{ player.second_name }} {{ player.last_name }} {{ player.mother_last_name }}
                    </h4>  
                    <div class="mt-5 flex justify-center">
                      <img v-if="player.photo_player" class=" bg-cover bg-center max-w-20" :src="player.photo_player.photo_path" alt="foto de jugador"/> 
                      <img v-else class=" bg-cover bg-center max-w-20" src="https://cdn.pixabay.com/photo/2018/04/18/18/56/user-3331256_1280.png" alt="foto de jugador"/> 
                    </div>                  
                </div> 
                <div class="grid grid-cols-2 gap-4 px-2 w-full">
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Identificador</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player.id }}
                    </p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                      <p class="text-sm text-gray-600">CI</p>
                      <p class="text-base font-medium text-navy-700 dark:text-navy">
                          {{ player.c_i}}
                      </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Equipo</p>
                    <p v-if="player.team" class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player.team.name }}
                    </p>
                    <p v-else>Equipo no asignado</p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                      <p class="text-sm text-gray-600">Fecha de nacimiento</p>
                      <p class="text-base font-medium text-navy-700 dark:text-navy">
                          {{ player.birth_date}}
                      </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Nacionalidad</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player.nacionality }}
                    </p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                      <p class="text-sm text-gray-600">Ciudad de nacimiento</p>
                      <p class="text-base font-medium text-navy-700 dark:text-navy">
                          {{ player.country_birth}}
                      </p>
                    </div>
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Región de nacimiento</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                      {{ player.region_birth }}
                    </p>
                    </div>
                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                      <p class="text-sm text-gray-600">Estado de jugador</p>
                      <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ player.state == 1? "inhabilitado" : "habilitado"}}
                      </p> 
                    </div>                                   
                </div>
                <div class="mt-5 px-2 w-full">
                  <p class="text-xl text-gray-700 dark:text-white">Foto de carnet de identidad</p>
                  <div class="mt-3 flex justify-center">
                      <img v-if="player.photo_player" class=" bg-cover bg-center max-w-20" :src="player.photo_player.photo_c_i" alt="foto de carnet de identidad"/> 
                      <img v-else class=" bg-cover bg-center max-w-20" src="https://cdn.pixabay.com/photo/2018/04/18/18/56/user-3331256_1280.png" alt="foto de carnet de identidad"/>                     
                  </div> 
                </div>
                <div class="mt-3 px-2 w-full">
                  <p class="text-xl text-gray-600 dark:text-white">Foto de certificado de nacimiento</p>
                  <div class="mt-3 flex justify-center">
                      <img v-if="player.photo_player" class=" bg-cover bg-center max-w-20" :src="player.photo_player.photo_birth_certificate" alt="foto de carnet de identidad"/> 
                      <img v-else class=" bg-cover bg-center max-w-20" src="https://cdn.pixabay.com/photo/2018/04/18/18/56/user-3331256_1280.png" alt="foto de carnet de identidad"/>                     
                    </div> 
                </div>
                <div class="mt-3 px-2 w-full">
                  <p class="text-xl text-gray-600 dark:text-white">Foto de autorización parental</p>
                  <div class="mt-3 flex justify-center">
                      <img v-if="player.photo_player" class=" bg-cover bg-center max-w-20" :src="player.photo_player.photo_parental_authorization" alt="foto de carnet de identidad"/> 
                      <img v-else class=" bg-cover bg-center max-w-20" src="https://cdn.pixabay.com/photo/2018/04/18/18/56/user-3331256_1280.png" alt="foto de carnet de identidad"/>                     
                    </div> 
                </div>
                
                <div class="flex justify-center mt-6">
                    <Link :href="route('players.edit', player.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeletePlayer" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeletePlayerModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar el jugador {{ player.first_name }} {{ player.second_name }} {{ player.last_name }} {{ player.mother_last_name }}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deletePlayer(player.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación del <a href="https://horizon-ui.com?ref=tailwindcomponents.com" target="_blank" class="text-brand-500 font-bold">Jugador</a></p>  
        </div>
    </div>
  </AdminLayout>
</template>
