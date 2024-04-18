<script setup>
import { Head } from '@inertiajs/vue3';
import {ref} from 'vue';
import InputLabel from '@/Components/InputLabel.vue';
import VueMultiselect from "vue-multiselect";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

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
  log_login_attempt: Array,
  log_transactions: Array,
});

const columns1 = ref([]);
const columns2 = ref([]);

const buttons1 = ref([]);
const buttons2 = ref([]);

const report = ref([]);
const types = ref([{'id': 1,'name':'Intentos de Inicio de Sesión'},{'id': 2,'name':'Transacciones'}]);
columns1.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'ip' },
  { data: 'email_attempt' },
  { data: null, render: function(data, type, row) {
      if (row.user) {
        return row.user.name;
      } else {
        return 'Usuario no relacionado';
      }
    }
  },
  { data: null, render: function(data, type, row) {
      if (row.user) {
        return row.user.email;
      } else {
        return 'correo no registrado';
      }
    }
  },
  {   data: 'created_at',
    render: function(data, type, row) {
      // Obtener la fecha y hora en formato Date
      var date = new Date(data);

      // Obtener los componentes de la fecha y hora
      var year = date.getFullYear();
      var month = ('0' + (date.getMonth() + 1)).slice(-2);
      var day = ('0' + date.getDate()).slice(-2);
      var hours = ('0' + date.getHours()).slice(-2);
      var minutes = ('0' + date.getMinutes()).slice(-2);
      var seconds = ('0' + date.getSeconds()).slice(-2);

      // Construir la cadena formateada
      var formattedDate = year + '-' + month + '-' + day + ' T ' + hours + ':' + minutes + ':' + seconds;

      return formattedDate;
    }
},

];
columns2.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: null, render: function(data, type, row) {
      if (row.user) {
          return row.user.name;
      } else {
        return 'usuario no autenticado';
      }
    }
  },
  { data: 'action' },
  { data: 'resource' },
  {data: 'resource_id'},
  {   data: 'created_at',
    render: function(data, type, row) {
      // Obtener la fecha y hora en formato Date
      var date = new Date(data);

      // Obtener los componentes de la fecha y hora
      var year = date.getFullYear();
      var month = ('0' + (date.getMonth() + 1)).slice(-2);
      var day = ('0' + date.getDate()).slice(-2);
      var hours = ('0' + date.getHours()).slice(-2);
      var minutes = ('0' + date.getMinutes()).slice(-2);
      var seconds = ('0' + date.getSeconds()).slice(-2);

      // Construir la cadena formateada
      var formattedDate = year + '-' + month + '-' + day + ' T ' + hours + ':' + minutes + ':' + seconds;

      return formattedDate;
    }
  },
  { data: 'details' },
]; 


buttons1.value= [
    {
        title:'Intento de Inicio de Sesión',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Intento de Inicio de Sesión',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Intento de Inicio de Sesión',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Intento de Inicio de Sesión',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPIAR',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons2.value= [
    {
        title:'Transacciones',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150',        
    },
    {
        title:'Transacciones',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150',     
    },
    {
        title:'Transacciones',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Transacciones',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]

</script>

<template>
    <Head title="Logs" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Reportes</h2>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="px-6 py-6 overflow-hidden shadow-sm sm:rounded-lg">
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
                    />                    
                </div>
                <div v-if="report.id == 1" class="px-6 py-6 bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="log_login_attempt" :columns="columns1"
                    
                    width="100%"
                    class="w-full display border border-gray-400 rounded-lg" 
                    :options="{responsive:true, autoWidth:true,dom:'Bfrtip',buttons:buttons1,select:true,language: language}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">IP</th>
                            <th class="px-2 py-2">Correo de intento de sesión</th>
                            <th class="px-2 py-2">Nombre de usuario</th>         
                            <th class="px-2 py-2">Correo de usuario</th>          
                            <th class="px-2 py-2">Fecha de intento</th>                                                                     
                                                           
                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 2" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="log_transactions" :columns="columns2"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons2,select:true,language: language}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Usuario Autor</th>
                            <th class="px-2 py-2">Acción</th>
                            <th class="px-2 py-2">Recurso</th>
                            <th class="px-2 py-2">Id de Recurso</th>
                            <th class="px-2 py-2">Fecha de Transacción</th>                                                                     
                            <th class="px-2 py-2">Detalles</th>
                        </tr>
                    </thead>
                    </DataTable>
                </div>

            </div>
        </div>
    </AuthenticatedLayout>

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


