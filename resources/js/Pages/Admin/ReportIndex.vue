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

const showConfirmDeleteClubModal = ref(null)

const confirmDeleteClub = (id) => {
      showConfirmDeleteClubModal.value = id;
}

const closeModal = () => {
  showConfirmDeleteClubModal.value = null;
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
         <h1>Página principal de reportes</h1>
         <Link :href="route('clubs.create')" 
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">
          Nuevo Club
         </Link>
     </div>
     <div class="flex space-x-4">
    
                     
     </div>
     
      <!-- component for list-->
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
        </Table>
      </div>

    </div>
  </AdminLayout>
</template>