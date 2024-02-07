<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  club: {
    type: Object,
    required: true,
  }
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeleteClubModal = ref(false)

const confirmDeleteClub = () => {
      showConfirmDeleteClubModal.value = true;
}

const closeModal = () => {
  showConfirmDeleteClubModal.value = false;
}

const deleteClub = (id) => {
   form.delete(route('clubs.destroy', id), {
    onSuccess: () => closeModal()
   });
}
</script>

<template>
  <Head title="Ver club" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('clubs.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-xl font-bold text-navy-700 dark:text-white">
                    Club {{ club.name }}
                    </h4>
                    <div class="mt-5 flex justify-center">
                      <img class=" bg-cover bg-center max-w-20" :src="club.logo_path" alt="logo de club"/> 
                    </div>
                </div> 
                <div class="grid grid-cols-2 gap-4 px-2 w-full">
                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Identificador</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ club.id }}
                    </p>
                    </div>

                    <div class="flex flex-col justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none">
                    <p class="text-sm text-gray-600">Profesor</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy">
                        {{ club.coach }}
                    </p>
                    </div>

                    <div class="flex flex-col items-start justify-center rounded-2xl bg-white bg-clip-border px-3 py-4 shadow-3xl shadow-shadow-500 dark:!bg-navy-700 dark:shadow-none ">
                    <p class="text-sm text-gray-600">Delegado</p>
                    <p class="text-base font-medium text-navy-700 dark:text-navy" v-if="club.users.length > 0" >
                  
                      <span  v-for="user in club.users" :key="user.id" >
                      {{ user.name }}
                      <br> 
                      </span>                                                                    
                    </p>
                    <p v-else>Delegado no asignado</p>
                    </div>                    
                </div>
                <div class="flex justify-center mt-6">
                    <Link :href="route('clubs.edit', club.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeleteClub" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeleteClubModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar el club {{club.name}}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deleteClub(club.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación de <span class="text-brand-500 font-bold">{{ club.name }}</span></p>  
        </div>
    </div>
  </AdminLayout>
</template>
