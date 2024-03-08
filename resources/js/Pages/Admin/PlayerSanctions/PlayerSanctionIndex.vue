<script>
  export default {
    name: 'PlayerSanctionIndex',
    data()
    {
      return{
        search: "",
      }
    },
    methods: {
    async searchPlayerSanction() {
      // Utiliza this.$inertia.get para enviar la solicitud GET

      if(this.search.length > 0){
        await this.$inertia.get(route('player_sanctions.index', { search: this.search }));
      }else{
        await this.$inertia.get(route('player_sanctions.index'));
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

defineProps(["player_sanctions"]);
const form = useForm({})

const showConfirmDeletePlayerSanctionModal = ref(null)

const confirmDeletePlayerSanction = (id) => {
      showConfirmDeletePlayerSanctionModal.value = id;
}

const closeModal = () => {
  showConfirmDeletePlayerSanctionModal.value = null;
}

const deletePlayerSanction = (id) => {
   form.delete(route('player_sanctions.destroy', id), {
    onSuccess: () => closeModal()
   });
}

const totalYellowCards = (playerId, tournamentId, player_sanctions) => {
    let totalYellows = 0;

    for (const player_sanction of player_sanctions) { // <-- Aquí cambia el nombre
        if (player_sanction.player_id === playerId && 
            player_sanction.game.game_scheduling.game_role.tournament.id === tournamentId) {
            totalYellows += player_sanction.yellow_cards;
        }
    }
    
    return totalYellows;
}
</script>

<template>
  <Head title="PlayerSanctions Index" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
     <div class="flex justify-between">
         <h1>Página principal de sación de jugadores</h1>
         <Link :href="route('player_sanctions.create')" 
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded">
          Nueva sanción de jugador
         </Link>
     </div>
     <div class="flex space-x-4">
        <!--search bar -->
        <div  class="md:block">
          <div class="relative flex items-center text-gray-400 focus-within:text-cyan-400">                        
            <input v-model="search"
            @keydown.enter.prevent="searchPlayerSanction" 
             type="search" name="search" id="search" placeholder="Buscar sanción de jugador" class="w-full pl-14 pr-4 py-2.5 rounded-xl text-sm text-gray-600 outline-none border border-gray-300 focus:border-cyan-300 transition">
            <button @click.prevent="searchPlayerSanction" class="absolute left-4 h-6 flex items-center pr-3 border-r border-gray-300">
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
              <TableHeaderCell>Jugador - Equipo</TableHeaderCell>
              <TableHeaderCell>Partido</TableHeaderCell>
              <TableHeaderCell>Sanción</TableHeaderCell>
              <TableHeaderCell>Tarjetas Amarillas Totales</TableHeaderCell>
              <TableHeaderCell>Estado</TableHeaderCell>   
              <TableHeaderCell>Tarjetas Amarillas</TableHeaderCell>            
              <TableHeaderCell>Tarjeta Roja</TableHeaderCell>  
              <TableHeaderCell>Acción</TableHeaderCell>                     
                   
            </TableRow>
          </template>

          <template #default>
            <TableRow v-for="player_sanction in player_sanctions" :key="player_sanction.id" class="border-b">              
                <TableDataCell>   
                  <Link :href="route('player_sanctions.show', player_sanction.id)">     
                    {{ player_sanction.id }} 
                  </Link>             
                </TableDataCell>
                <TableDataCell v-if="player_sanction.player">
                   <Link :href="route('player_sanctions.show', player_sanction.id)">     
                    {{ player_sanction.player.first_name }} {{ player_sanction.player.second_name }} {{ player_sanction.player.last_name }} {{ player_sanction.player.mother_last_name }}
                    - {{ player_sanction.player.team.name }}
                  </Link>  
                </TableDataCell>
                <TableDataCell>
                  <Link :href="route('player_sanctions.show', player_sanction.id)">     
                    <div class=" text-blue-800">
                      {{ player_sanction.game.game_scheduling?.game_role?.tournament?.name }}
                    </div>

                    <div class=" text-blue-500">
                    {{ player_sanction.game.game_scheduling?.game_role?.name }}
                    </div>                  
                    {{ player_sanction.game.game_scheduling?.team_a.name }}
                     <div class="text-red-400">
                      vs
                     </div> 
                      {{ player_sanction.game.game_scheduling?.team_b?.name }}      
                </Link>                                                                 
                </TableDataCell>

                <TableDataCell >
                   <Link :href="route('player_sanctions.show', player_sanction.id)">     
                    {{ player_sanction.sanction }}
                  </Link>  
                </TableDataCell>          
                <TableDataCell>   
                  <Link :href="route('player_sanctions.show', player_sanction.id)">     
                      {{ totalYellowCards(player_sanction.player.id, player_sanction.game.game_scheduling.game_role.tournament.id, player_sanctions) }}
                  </Link>             
              </TableDataCell>
                <TableDataCell >
                   <Link :href="route('player_sanctions.show', player_sanction.id)">     
                    {{ player_sanction.state }}
                  </Link>  
                </TableDataCell>
                <TableDataCell >
                  <Link :href="route('player_sanctions.show', player_sanction.id)">  
                    {{ player_sanction.yellow_cards }}                    
                  </Link>  
                </TableDataCell> <TableDataCell >
                   <Link :href="route('player_sanctions.show', player_sanction.id)">     
                    {{ player_sanction.red_card }}
                  </Link>  
                </TableDataCell>

                <TableDataCell class="space-x-4">
                    <Link :href="route('player_sanctions.edit', player_sanction.id)" class="text-green-400 hover:text-green-600">Editar</Link>
                    <button @click="() => confirmDeletePlayerSanction(player_sanction.id)" class="text-red-400 hover:text-red-600">Eliminar</button>
                    <Modal :show="showConfirmDeletePlayerSanctionModal === player_sanction.id" @close="closeModal">
                        <div class="p-6">
                            <h2 class="text-lg font-semibold text-slate-800 dark:text-white">¿Está seguro de eliminar la sanción del jugador {{ player_sanction.player.first_name }} {{ player_sanction.player.second_name }} {{ player_sanction.player.last_name }} {{ player_sanction.player.mother_last_name }} ?</h2>
                            <div class="mt-6 flex space-x-4">
                                <DangerButton @click="deletePlayerSanction(player_sanction.id)">Eliminar</DangerButton>
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