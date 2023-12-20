<script setup>
import { ref } from "vue";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import Modal from "@/Components/Modal.vue";
import DangerButton from "@/Components/DangerButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

defineProps(["clubs"]);
const form = useForm({})

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
  <Head title="Clubs Index" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
     <div class="flex justify-between">
         <h1>Página principal de clubs</h1>
         <Link :href="route('clubs.create')" 
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">
          Nuevo Club
         </Link>
     </div>
     <!-- component for celphone-->
      <div class=" md:hidden flex h-screen bg-gray-100">
        <div class="m-auto">
            <div>
              
              <div class="mt-5 bg-white rounded-lg shadow">
                  <div class="flex">
                    <div class="flex-1 py-5 pl-5 overflow-hidden">
                        <svg class="inline align-text-top" height="24px" viewBox="0 0 24 24" width="24px" xmlns="http://www.w3.org/2000/svg" fill="#000000">
                          <g>
                              <path d="m4.88889,2.07407l14.22222,0l0,20l-14.22222,0l0,-20z" fill="none" id="svg_1" stroke="null"></path>
                              <path d="m7.07935,0.05664c-3.87,0 -7,3.13 -7,7c0,5.25 7,13 7,13s7,-7.75 7,-13c0,-3.87 -3.13,-7 -7,-7zm-5,7c0,-2.76 2.24,-5 5,-5s5,2.24 5,5c0,2.88 -2.88,7.19 -5,9.88c-2.08,-2.67 -5,-7.03 -5,-9.88z" id="svg_2"></path>
                              <circle cx="7.04807" cy="6.97256" r="2.5" id="svg_3"></circle>
                          </g>
                        </svg>
                        <h2 class="inline text-2xl font-semibold leading-none">Clubs</h2>
                    </div>
                  </div>
                  <div class="px-5 pb-5">
                    <label class=" text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base   transition duration-500 ease-in-out transform border-transparent rounded-lg bg-gray-200  focus:border-blueGray-500 focus:bg-white dark:focus:bg-gray-800 focus:outline-none focus:shadow-outline focus:ring-2 ring-offset-current ring-offset-2 ring-gray-400"></label>
                      <input  placeholder="Address" class=" text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base   transition duration-500 ease-in-out transform border-transparent rounded-lg bg-gray-200  focus:border-blueGray-500 focus:bg-white dark:focus:bg-gray-800 focus:outline-none focus:shadow-outline focus:ring-2 ring-offset-current ring-offset-2 ring-gray-400"> 
                    <div class="flex">
                        <div class="flex-grow w-1/4 pr-2"><input  placeholder="PLZ" class=" text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base   transition duration-500 ease-in-out transform border-transparent rounded-lg bg-gray-200  focus:border-blueGray-500 focus:bg-white dark:focus:bg-gray-800 focus:outline-none focus:shadow-outline focus:ring-2 ring-offset-current ring-offset-2 ring-gray-400"></div>
                        <div class="flex-grow"><input placeholder="City" class=" text-black placeholder-gray-600 w-full px-4 py-2.5 mt-2 text-base   transition duration-500 ease-in-out transform border-transparent rounded-lg bg-gray-200  focus:border-blueGray-500 focus:bg-white dark:focus:bg-gray-800 focus:outline-none focus:shadow-outline focus:ring-2 ring-offset-current ring-offset-2 ring-gray-400"></div>
                    </div>
                  </div>
                  
                 
                  <hr class="mt-4">
                  <div class="flex flex-row-reverse p-3">
                    <div class="flex-initial pl-3">
                        <button type="button" class="flex items-center px-5 py-2.5 font-medium tracking-wide text-white capitalize   bg-black rounded-md hover:bg-gray-800  focus:outline-none focus:bg-gray-900  transition duration-300 transform active:scale-95 ease-in-out">
                          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#FFFFFF">
                              <path d="M0 0h24v24H0V0z" fill="none"></path>
                              <path d="M5 5v14h14V7.83L16.17 5H5zm7 13c-1.66 0-3-1.34-3-3s1.34-3 3-3 3 1.34 3 3-1.34 3-3 3zm3-8H6V6h9v4z" opacity=".3"></path>
                              <path d="M17 3H5c-1.11 0-2 .9-2 2v14c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V7l-4-4zm2 16H5V5h11.17L19 7.83V19zm-7-7c-1.66 0-3 1.34-3 3s1.34 3 3 3 3-1.34 3-3-1.34-3-3-3zM6 6h9v4H6z"></path>
                          </svg>
                          <span class="pl-2 mx-1">Save</span>
                        </button>
                    </div>
                    <div class="flex-initial">
                        <button type="button" class="flex items-center px-5 py-2.5 font-medium tracking-wide text-black capitalize rounded-md  hover:bg-red-200 hover:fill-current hover:text-red-600  focus:outline-none  transition duration-300 transform active:scale-95 ease-in-out">
                          <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px">
                              <path d="M0 0h24v24H0V0z" fill="none"></path>
                              <path d="M8 9h8v10H8z" opacity=".3"></path>
                              <path d="M15.5 4l-1-1h-5l-1 1H5v2h14V4zM6 19c0 1.1.9 2 2 2h8c1.1 0 2-.9 2-2V7H6v12zM8 9h8v10H8V9z"></path>
                          </svg>
                          <span class="pl-2 mx-1">Delete</span>
                        </button>
                    </div>
                  </div>
              </div>
              
            </div>
        </div>
      </div>
      <!-- component for tablet and pc-->
      <div class="mt-6 max-sm:hidden">
        
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Nombre</TableHeaderCell>
              <TableHeaderCell>Profesor</TableHeaderCell>
              <TableHeaderCell>Delegado</TableHeaderCell>

              <TableHeaderCell>Logo</TableHeaderCell>
              <TableHeaderCell>Acción</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow v-for="club in clubs" :key="club.id" class="border-b">
              <Link :href="route('clubs.show', club.id)" class="text-gray-400 hover:text-black-600">
                <TableDataCell>{{ club.id }}</TableDataCell>
              </Link>    
                <TableDataCell>{{ club.name }}</TableDataCell>
                <TableDataCell>{{ club.coach }}</TableDataCell>
              
                <span v-if="''">                
                  <TableDataCell> <ul>Sin Delegado</ul> </TableDataCell>
                </span>
                <TableDataCell v-else>
                  <ul v-for="user in club.users" :key="user.id">
                    {{ user.name }}
                  </ul>
                </TableDataCell>
                <TableDataCell> <img class="w-full bg-cover bg-center max-w-20" :src="club.logo_path" alt="logo de club"/> </TableDataCell>
                
                <TableDataCell class="space-x-4">
                    <Link :href="route('clubs.edit', club.id)" class="text-green-400 hover:text-green-600">Editar</Link>
                    <button @click="confirmDeleteClub" class="text-red-400 hover:text-red-600">Eliminar</button>
                    <Modal :show="showConfirmDeleteClubModal" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800">¿Está seguro de eliminar este club?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deleteClub(club.id)">Eliminar</DangerButton>
                                <SecondaryButton @click="closeModal">Cancelar</SecondaryButton>
                            </div>
                        </div>
                    </Modal>              
                </TableDataCell>
            </TableRow>
          </template>
        </Table>
      </div>

    </div>
  </AdminLayout>
</template>