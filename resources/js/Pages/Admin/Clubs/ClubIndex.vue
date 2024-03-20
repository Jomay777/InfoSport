<script>
  export default {
    name: 'ClubIndex',
    data()
    {
      return{
        search: "",
      }
    },
    methods: {
    async searchClub() {
      // Utiliza this.$inertia.get para enviar la solicitud GET

      if(this.search.length > 0){
        await this.$inertia.get(route('clubs.index', { search: this.search }));
      }else{
        await this.$inertia.get(route('clubs.index'));
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
  <Head title="Clubs" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
     <div class="flex justify-between">
         <h1>Página principal de clubs</h1>
         <Link :href="route('clubs.create')" 
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">
          Nuevo Club
         </Link>
     </div>
     <div class="flex space-x-4">
        <!--search bar -->
        <div  class="md:block">
          <div class="relative flex items-center text-gray-400 focus-within:text-cyan-400">                        
            <input v-model="search"
            @keydown.enter.prevent="searchClub" 
             type="search" name="search" id="search" placeholder="Buscar club" class="w-full pl-14 pr-4 py-2.5 rounded-xl text-sm text-gray-600 outline-none border border-gray-300 focus:border-cyan-300 transition">
            <button @click.prevent="searchClub" class="absolute left-4 h-6 flex items-center pr-3 border-r border-gray-300">
              <svg xmlns="http://ww50w3.org/2000/svg" class="w-4 fill-current" viewBox="0 0 35.997 36.004">
                <path id="Icon_awesome-search-md" data-name="search" d="M35.508,31.127l-7.01-7.01a1.686,1.686,0,0,0-1.2-.492H26.156a14.618,14.618,0,1,0-2.531,2.531V27.3a1.686,1.686,0,0,0,.492,1.2l7.01,7.01a1.681,1.681,0,0,0,2.384,0l1.99-1.99a1.7,1.7,0,0,0,.007-2.391Zm-20.883-7.5a9,9,0,1,1,9-9A8.995,8.995,0,0,1,14.625,23.625Z"></path>
              </svg>
            </button>
          </div>              
        </div>
                     
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
                <TableDataCell v-if="club.coach">
                  <Link :href="route('clubs.show', club.id)">     
                    {{ club.coach }} 
                   </Link>   
                </TableDataCell> 
                <TableDataCell v-else>
                  Profesor no registrado
                </TableDataCell>
                <TableDataCell v-if="club.users.length > 0" >
                  <Link :href="route('clubs.show', club.id)">     
                    <span  v-for="user in club.users" :key="user.id" >
                    {{ user.name }}
                    <br> 
                    </span>  
                  </Link>                                                                  
                </TableDataCell>
                <TableDataCell v-else>
                  <Link :href="route('clubs.show', club.id)">     
                    Delegado no asignado
                  </Link>  
                </TableDataCell>  
                <TableDataCell>
                  <Link :href="route('clubs.show', club.id)">     
                   <img class="bg-cover bg-center max-w-20" :src="club.logo_path" alt="logo de club"/> 
                  </Link> 
                </TableDataCell>
                <TableDataCell class="space-x-4">
                    <Link :href="route('clubs.edit', club.id)" class="text-green-400 hover:text-green-600">Editar</Link>
                    <button @click="() => confirmDeleteClub(club.id)" class="text-red-400 hover:text-red-600">Eliminar</button>
                    <Modal :show="showConfirmDeleteClubModal === club.id" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar el club {{ club.name }}?</h2>
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