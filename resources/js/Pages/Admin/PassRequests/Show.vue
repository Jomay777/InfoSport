<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { ref } from "vue";
import { Head, Link, useForm } from "@inertiajs/vue3";

import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps({
  pass_request: {
    type: Object,
    required: true,
  }, 
  player: {
    type: Object,
  }
});

const form = useForm({
  _method: "DELETE",
});

const showConfirmDeletePassRequestModal = ref(false)

const confirmDeletePassRequest = () => {
      showConfirmDeletePassRequestModal.value = true;
}

const closeModal = () => {
  showConfirmDeletePassRequestModal.value = false;
}

const deletePassRequest = (id) => {
   form.delete(route('pass_requests.destroy', id), {
    onSuccess: () => closeModal()
   });
}

</script>

<template>
  <Head title="Ver solicitud de pase de jugador" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('pass_requests.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class=" mt-5 flex flex-col justify-center items-center ">
            <div class="relative flex flex-col items-center rounded-[20px] w-[700px] max-w-[95%] mx-auto bg-gray-100 bg-clip-border shadow-3xl shadow-shadow-500 dark:bg-gray-700 dark:text-gray-400 dark:!shadow-none p-3">
                <div class="mt-2 mb-8  text-gray-700 w-full">
                    <h4 class=" mt-5 px-2 text-xl font-bold dark:text-white">
                    Jugador {{ pass_request.player.first_name }} {{ pass_request.player.second_name }} {{ pass_request.player.last_name }} {{ pass_request.player.mother_last_name }}
                    </h4>  
                    <h2 class=" mt-5 px-2 text-xl font-bold dark:text-white">ID de solicitud de pase <span class="text-red-500"> {{ pass_request.id }} </span></h2>
                    <div class="mt-5 flex justify-center">
                      <img v-if="pass_request.request_photo_path" class=" bg-cover bg-center max-w-20" :src="pass_request.request_photo_path" alt="foto de solicitud de pase"/> 
                      <img v-else class=" bg-cover bg-center max-w-20" src="https://cdn.pixabay.com/photo/2018/04/18/18/56/user-3331256_1280.png" alt="foto de solicitud de pase"/> 
                    </div>                  
                </div> 
               
                
                <div class="flex justify-center mt-6">
                    <Link :href="route('pass_requests.edit', pass_request.id)" class="text-green-400 hover:text-green-600 m-5">Editar</Link>
                    <button @click="confirmDeletePassRequest" class="text-red-400 hover:text-red-600 m-5">Eliminar</button>
                    <Modal :show="showConfirmDeletePassRequestModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar la solicitud de pase del jugador {{ pass_request.player.first_name }} {{ pass_request.player.second_name }} {{ pass_request.player.last_name }} {{ pass_request.player.mother_last_name }}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deletePassRequest(pass_request.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>
                    </div>
            </div>  
            <p class="font-normal text-navy-700 mt-20 mx-auto w-max">Tarjeta de Presentación de Pase del jugador <span class="text-brand-500 font-bold"> {{ pass_request.player.first_name }} {{ pass_request.player.second_name }} {{ pass_request.player.last_name }} {{ pass_request.player.mother_last_name }}</span></p>  
        </div>
    </div>
  </AdminLayout>
</template>
