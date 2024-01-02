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
     
      <!-- component for tablet and pc-->
      <div class="mt-6">
        
        <Table>
          <template #header>
            <TableRow >              
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
                <TableDataCell>   
                  <Link :href="route('clubs.show', club.id)">     
                    {{ club.id }} 
                  </Link>             
                </TableDataCell>
                <TableDataCell >
                   <Link :href="route('clubs.show', club.id)">     
                    {{ club.name }} 
                   </Link>  
                </TableDataCell>
                <TableDataCell>
                  <Link :href="route('clubs.show', club.id)">     
                    {{ club.coach }} 
                   </Link>   
                </TableDataCell>
               
                <TableDataCell v-if="club.users.length > 0" >
                  
                    <span  v-for="user in club.users" :key="user.id" >
                    {{ user.name }}
                    <br> 
                    </span>                                                                    
                </TableDataCell>
                <TableDataCell v-else>Delegado no asignado</TableDataCell>  
                <TableDataCell> <img class="bg-cover bg-center max-w-20" :src="club.logo_path" alt="logo de club"/> </TableDataCell>
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