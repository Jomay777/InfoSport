<script>
  export default {
    name: 'Reportes',
    data()
    {
      return{
        search: "",
      }
    },
    methods: {
      navigateToReport(selectedOption) {
        // Obtén la ruta correspondiente de la opción seleccionada
        const route = selectedOption.route ? selectedOption.route : '';
        if(selectedOption.id != 1){
          window.location.href = route;
        }
        // Navega a la ruta utilizando Vue Router
    }
  }
  }
</script>
<script setup>
import { Head } from '@inertiajs/vue3';
import {ref} from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import VueMultiselect from "vue-multiselect";
import AdminLayout from '@/Layouts/AdminLayout.vue';

import DataTable from 'datatables.net-vue3';
import DataTablesLib from 'datatables.net';
//import 'datatables.net-select';
import 'datatables.net-responsive-dt';
import language from 'datatables.net-plugins/i18n/es-ES.mjs';

import 'datatables.net-buttons';
import 'datatables.net-buttons/js/buttons.html5.mjs';
import jszip from 'jszip';
import pdfmake from 'pdfmake';
import * as pdfFonts from "pdfmake/build/vfs_fonts";
pdfmake.vfs = pdfFonts.pdfMake ? pdfFonts.pdfMake.vfs : pdfmake.vfs;

window.JSZip = jszip;
DataTable.use(DataTablesLib);
//DataTablesLib.Buttons.jszip(jszip);
//DataTablesLib.Buttons.pdfMake(pdfmake); 

defineProps({
  users: Object,

});

const columns1 = ref([]);

const buttons1 = ref([]);

const report = ref([]);
const types = ref([{id: 1, name:'Usuarios' }
                  ,{id: 2,name:'Clubs', route: 'reports/clubs'}
                  ,{'id': 3,'name':'Equipos', route: 'reports/teams'}
                  ,{'id': 4,'name':'Jugadores', route: 'reports/players'}
                  ,{'id': 5,'name':'Solicitud de pases', route: 'reports/pass_requests'}
                  ,{'id': 6,'name':'Categorías', route: 'reports/categories'}
                  ,{'id': 7,'name':'Torneos', route: 'reports/tournaments'}
                  ,{'id': 8,'name':'Roles de partidos', route: 'reports/game_roles'}
                  ,{'id': 9,'name':'Programación de partidos', route: 'reports/game_schedulings'}
                  ,{'id': 10,'name':'Partidos', route: 'reports/games'}
                  ,{'id': 11,'name':'Sanción de Jugadores', route: 'reports/player_sanctions'}
                  ,{'id': 12,'name':'Sanción de Equipos', route: 'reports/team_sanctions'}
                  ,{'id': 10,'name':'Partidos', route: 'reports/games'}
                  ,{'id': 10,'name':'Partidos', route: 'reports/games'}
                  ,{'id': 10,'name':'Partidos', route: 'reports/games'}
                  ,{'id': 10,'name':'Partidos', route: 'reports/games'}
                  ,{'id': 10,'name':'Partidos', route: 'reports/games'}
]);

columns1.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'name' },
  { data: 'email' },
  { data: null, render: function(data, type, row) {
      if (row.roles.length > 0) {
        return row.roles.map(function(role) {
          return role.name;
        }).join(', '+'<br>');
      } else {
        return 'Rol no asignado';
      }
    }
  },
];

buttons1.value= [
    {
        title:'Usuarios',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Usuarios',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Usuarios',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Usuarios',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPIAR',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]

</script>

<template>
    <Head title="Reportes" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reportes</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <InputLabel for="rep" value="Reporte:"></InputLabel>
                    <VueMultiselect 
                    class="mt-1 mb-20"
                    id="rep" 
                    v-model= "report" 
                    :options="types"
                    :multiple="false"
                    :preselect-first="true" 
                    track-by="id"
                    label="name"
                    @select="navigateToReport"
                    />                    
                </div>
                <div v-if="report?.id == 1" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="users" :columns="columns1"
                    
                    width="100%"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:true,dom:'Bfrtip',buttons:buttons1,select:true,language: language}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Correo electrónico</th>        
                            <th class="px-2 py-2">Rol</th>                        
                
                        </tr>
                    </thead>
                    </DataTable>
                </div>                
            </div>
        </div>
    </AdminLayout>

    <!-- <DataTable
      :columns="columns"
      :options="options"
      ajax="/data.json"
      class="display"
      width="100%"
    >
      <thead>       
      </thead>
      <tfoot>        
      </tfoot>
    </DataTable> -->
 
</template>
<style src="vue-multiselect/dist/vue-multiselect.css">
</style>
<style>
@import 'datatables.net-dt';
@import 'datatables.net-buttons-dt';
/*@import 'datatables.net-select-dt';*/
@import 'datatables.net-responsive-dt';
  
</style>