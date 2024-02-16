<script>
  export default {
    name: 'RoleIndex',
    data()
    {
      return{
        search: "",
      }
    },
    methods: {
    async searchRole() {
      // Utiliza this.$inertia.get para enviar la solicitud GET

      if(this.search.length > 0){
        await this.$inertia.get(route('roles.index', { search: this.search }));
      }else{
        await this.$inertia.get(route('roles.index'));
      }
      
    }
  }
  }
</script>
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

defineProps(["roles"]);
const form = useForm({})

const showConfirmDeleteRoleModal = ref(null)

const confirmDeleteRole = (id) => {
      showConfirmDeleteRoleModal.value = id;
}
const closeModal = () => {
  showConfirmDeleteRoleModal.value = null;
}

const deleteRole = (id) => {
   form.delete(route('roles.destroy', id), {
    onSuccess: () => closeModal()
   });
}
</script>

<template>
  <Head title="Dashboard" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
     <div class="flex justify-between">
         <h1>Página principal de roles</h1>
         <Link :href="route('roles.create')" class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">Nuevo Rol</Link>
     </div>
     <div class="flex space-x-4">
        <!--search bar -->
        <div  class="md:block">
          <div class="relative flex items-center text-gray-400 focus-within:text-cyan-400">                        
            <input v-model="search"
            @keydown.enter.prevent="searchRole" 
             type="search" name="search" id="search" placeholder="Buscar rol" class="w-full pl-14 pr-4 py-2.5 rounded-xl text-sm text-gray-600 outline-none border border-gray-300 focus:border-cyan-300 transition">
            <button @click.prevent="searchRole" class="absolute left-4 h-6 flex items-center pr-3 border-r border-gray-300">
              <svg xmlns="http://ww50w3.org/2000/svg" class="w-4 fill-current" viewBox="0 0 35.997 36.004">
                <path id="Icon_awesome-search-md" data-name="search" d="M35.508,31.127l-7.01-7.01a1.686,1.686,0,0,0-1.2-.492H26.156a14.618,14.618,0,1,0-2.531,2.531V27.3a1.686,1.686,0,0,0,.492,1.2l7.01,7.01a1.681,1.681,0,0,0,2.384,0l1.99-1.99a1.7,1.7,0,0,0,.007-2.391Zm-20.883-7.5a9,9,0,1,1,9-9A8.995,8.995,0,0,1,14.625,23.625Z"></path>
              </svg>
            </button>
          </div>              
        </div>
                     
     </div>
      <div class="mt-6">
        <Table>
          <template #header>
            <TableRow>
              <TableHeaderCell>ID</TableHeaderCell>
              <TableHeaderCell>Nombre</TableHeaderCell>
              <TableHeaderCell>Acción</TableHeaderCell>
            </TableRow>
          </template>
          <template #default>
            <TableRow v-for="role in roles" :key="role.id" class="border-b">
                <TableDataCell>{{ role.id }}</TableDataCell>
                <TableDataCell>{{ role.name }}</TableDataCell>
                <TableDataCell class="space-x-4">
                    <Link :href="route('roles.edit', role.id)" class="text-green-400 hover:text-green-600">Editar</Link>
                    <button @click="() => confirmDeleteRole(role.id)" class="text-red-400 hover:text-red-600">Eliminar</button>
                    <Modal :show="showConfirmDeleteRoleModal === role.id" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar el rol {{role.name}}?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deleteRole(role.id)">Eliminar</DangerButton>
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