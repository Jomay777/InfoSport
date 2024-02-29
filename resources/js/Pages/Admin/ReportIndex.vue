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
  clubs: Object,
  users: Object,
  teams: Object,
  players: Object,
  pass_requests: Object,
  categories: Object,
  tournaments: Object,
  game_roles: Object,
  game_schedulings: Object,
  games: Object
 
});

const columns1 = ref([]);
const columns2 = ref([]);
const columns3 = ref([]);
const columns4 = ref([]);
const columns5 = ref([]);
const columns6 = ref([]);
const columns7 = ref([]);
const columns8 = ref([]);
const columns9 = ref([]);
const columns10 = ref([]);


const buttons1 = ref([]);
const buttons2 = ref([]);
const buttons3 = ref([]);
const buttons4 = ref([]);
const buttons5 = ref([]);
const buttons6 = ref([]);
const buttons7 = ref([]);
const buttons8 = ref([]);
const buttons9 = ref([]);
const buttons10 = ref([]);

const report = ref([]);
const types = ref([{'id': 1,'name':'Usuarios'},{'id': 2,'name':'Clubs'},{'id': 3,'name':'Equipos'},{'id': 4,'name':'Jugadores'}
                ,{'id': 5,'name':'Solicitud de pases'},{'id': 6,'name':'Categorías'},{'id': 7,'name':'Torneos'},{'id': 8,'name':'Roles de partidos'}
                ,{'id': 9,'name':'Programación de partidos'},{'id': 10,'name':'Partidos'},{'id': 10,'name':'Partidos'},{'id': 10,'name':'Partidos'},{'id': 10,'name':'Partidos'},{'id': 10,'name':'Partidos'},{'id': 10,'name':'Partidos'}]);
columns1.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'name' },
  { data: 'email' }
];
columns2.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'name' },
  { data: 'coach' },
  { data: null, render: function(data, type, row) {
      if (row.users.length > 0) {
        return row.users.map(function(user) {
          return user.name;
        }).join('<br>');
      } else {
        return 'Delegado no asignado';
      }
    }
  },
  /* {
  data: null,
  render: function(data, type, row) {
    const imageData = btoa('http://127.0.0.1:8000'+row.logo_path);
    return '<img src="data:image/png;base64,' + imageData + '" alt="logo" />';
  }
}, */

  {
    data: null,
    render: function(data, type, row) {      
        return '<a href="' + row.logo_path + '" target="_blank"><img src="http://127.0.0.1:8000'+ row.logo_path + '" alt="logo" style="max-width: 100px;"></img></a>';   
    }   
}
]; 
columns3.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'name' },
  { data: null, render: function(data, type, row) {
      if (row.club) {
        return row.club.name;
      } else {
        return 'Club no asignado';
      }
    }
  },
  { data: null, render: function(data, type, row) {
      if (row.category) {
        return row.category.name;
      } else {
        return 'Categoría no asignado';
      }
    }
  },
  { data: 'description' }
];
columns4.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  {
            "data": null,
            "render": function ( data, type, row ) {
                // Combina el primer nombre y el apellido en una sola cadena
                return data.first_name + ' ' + (data.second_name ? data.second_name : '') + ' ' + data.last_name + ' '+ (data.mother_last_name? data.mother_last_name : '');
            }
        },
  { data: null, render: function(data, type, row) {
      if (row.team) {
        return row.team.name;
      } else {
        return 'Equipo no asignado';
      }
    }
  },
  { data: 'c_i' },
  { data: null, render: function(data, type, row) {
      if (row.photo_player) {
        return '<img src="' + row.photo_player.photo_path + '" alt="logo" style="max-width: 100px;">';
      } else {
        return 'Imagen no asignada';
      }
    }
  },
  
];
columns5.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: null, render: function(data, type, row) {
      if (row.player) {
        return data.player.first_name + ' ' + (data.player.second_name ? data.player.second_name : '') + ' ' + data.player.last_name + ' '+ (data.player.mother_last_name? data.player.mother_last_name : '');
      } else {
        return 'Jugador no asignado';
      }
    }
  },
  { data: null, render: function(data, type, row) {
      if (row.request_photo_path) {
        return '<img src="' + row.request_photo_path + '" alt="logo" style="max-width: 100px;">';
      } else {
        return 'Imagen no asignada';
      }
    }
  }
];
columns6.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  {data: 'name'},
  {data: 'description'}
];
columns7.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'name'},
  { data: null, render: function(data, type, row) {
      if (row.categorie) {
        return data.categorie.name;
      } else {
        return 'Categoría no asignado';
      }
    }
  },
  {data: 'description'}
];
columns8.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'name'},
  
  {data: 'date'},
  { data: null, render: function(data, type, row) {
      if (row.tournament) {
        return data.tournament.name;
      } else {
        return 'Torneo no asignado';
      }
    }
  },
  { data: null, render: function(data, type, row) {
      if (row.pitch) {
        return data.pitch.name;
      } else {
        return 'Cancha no asignada';
      }
    }
  }
];
columns9.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  { data: 'time'},
  { data: null, render: function(data, type, row) {
      if (row.gameRole) {
        return data.gameRole.name;
      } else {
        return 'Rol de partido no asignado';
      }
    }
  },
  {
  data: null,
  render: function(data, type, row) {
    if (row.teams && row.teams.length > 0) {
      // Si hay equipos asignados, construye el contenido de la celda
      var teamsHTML = '';
      row.teams.forEach(function(team, index) {
        teamsHTML += team.name;
        if (index < row.teams.length - 1) {
          teamsHTML +=' ' +'<br>vs<br>'+' ';
        }
      });
      return teamsHTML;
    } else {
      // Si no hay equipos asignados, muestra un mensaje
      return 'Equipos no asignados';
    }
  }
  }

];
columns10.value = [
  { data: null, render: function(data, type, row, meta) {
      return meta.row + 1;
    }
  },
  {
  data: null,
  render: function(data, type, row) {
    if (row.game_scheduling && row.game_scheduling.teams && row.game_scheduling.teams.length > 0) {
      // Si hay equipos asignados, construye el contenido de la celda
      var teamsHTML = '(' + row.game_scheduling.game_role.name + ')<br>';
      row.game_scheduling.teams.forEach(function(team, index) {
        teamsHTML += team.name;
        if (index < row.game_scheduling.teams.length - 1) {
          teamsHTML += ' '+'<br>vs<br>'+' ';
        }
      });
      return '<a href="' + route('games.show', row.id) + '">' + teamsHTML + '</a>';
    } else {
      // Si no hay equipos asignados, muestra un mensaje
      return '<a href="' + route('games.show', row.id) + '">Equipos no asignados</a>';
    }
  }
  },
  { data: 'result'},
  { data: 'observation'},



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
buttons2.value= [
    {
        title:'Clubs',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150',        
    },
    {
        title:'Clubs',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150',
       /*  customize: function (doc) {
                        doc.content.splice(1, 0, {
                            margin: [0, 0, 0, 12],
                            alignment: 'center',
                            image:
                                //'data:image/png;base64,aHR0cDovLzEyNy4wLjAuMTo4MDAwL3N0b3JhZ2UvbG9nb3MvU1VHSXViZm1ZNHMzNVA0ZHpKWDBpRDRreTJFT0N4QjdJc2dQZ0V4bC5wbmc='
                                
                        });
                    }  */        
    },
    {
        title:'Clubs',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Clubs',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons3.value= [
    {
        title:'Equipos',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Equipos',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Equipos',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Equipos',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons4.value= [
    {
        title:'Jugadores',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Jugadores',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Jugadores',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Jugadores',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons5.value= [
    {
        title:'Solicitud de Pase de Jugadores',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Solicitud de Pase de Jugadores',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Solicitud de Pase de Jugadores',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Solicitud de Pase de Jugadores',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons6.value= [
    {
        title:'Categorías',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Categorías',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Categorías',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Categorías',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons7.value= [
    {
        title:'Torneos',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Torneos',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Torneos',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Torneos',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons8.value= [
    {
        title:'Rol de Partidos',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Rol de Partidos',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Rol de Partidos',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Rol de Partidos',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons9.value= [
    {
        title:'Programación de Partidos',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Programación de Partidos',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Programación de Partidos',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Programación de Partidos',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
        className:'inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    }
]
buttons10.value= [
    {
        title:'Partidos',extend:'excelHtml5', 
        text:'<i class="fa-solid fa-file-excel"></i> Excel',
        className:'inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Partidos',extend:'pdfHtml5', 
        text:'<i class="fa-solid fa-file-pdf"></i> PDF',
        className:'inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Partidos',extend:'csv', 
        text:'<i class="fa-solid fa-print"></i> CSV',
        className:'inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150'
    },
    {
        title:'Partidos',extend:'copy', 
        text:'<i class="fa-solid fa-copy"></i> COPY',
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
                    />                    
                </div>
                <div v-if="report.id == 1" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="users" :columns="columns1"
                    
                    width="100%"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:true,dom:'Bfrtip',buttons:buttons1,select:true,language: language}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Correo electrónico</th>                        
                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 2" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="clubs" :columns="columns2"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons2,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Profesor</th>
                            <th class="px-2 py-2">Delegado</th>
                            <th class="px-2 py-2">Logo</th>
                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 3" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="teams" :columns="columns3"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons3,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Club</th>
                            <th class="px-2 py-2">Categoría</th>
                            <th class="px-2 py-2">Descripción</th>

                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 4" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="players" :columns="columns4"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons4,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Equipo</th>
                            <th class="px-2 py-2">CI</th>
                            <th class="px-2 py-2">Foto</th>

                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 5" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="pass_requests" :columns="columns5"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons5,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Foto de Solocitud de Pase</th>


                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 6" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="categories" :columns="columns6"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons6,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Descripción</th>


                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 7" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="tournaments" :columns="columns7"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons7,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Categoría</th>
                            <th class="px-2 py-2">Descripción</th>


                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 8" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="game_roles" :columns="columns8"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons8,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Nombre</th>
                            <th class="px-2 py-2">Fecha</th>
                            <th class="px-2 py-2">Torneo</th>
                            <th class="px-2 py-2">Cancha</th>


                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 9" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="game_schedulings" :columns="columns9"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons9,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Hora</th>
                            <th class="px-2 py-2">Rol de Partido</th>
                            <th class="px-2 py-2">Equipos</th>
                        </tr>
                    </thead>
                    </DataTable>
                </div>
                <div v-if="report.id == 10" class="px-6 py-6 bg-white overflow-hidden shadow-sm sm:rounded-lg -z-10">
                    <DataTable :data="games" :columns="columns10"
                    class="w-full display border border-gray-400" 
                    :options="{responsive:true, autoWidth:false,dom:'Bfrtip',buttons:buttons10,select:true}">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 py-2">#</th>
                            <th class="px-2 py-2">Equipos</th>
                            <th class="px-2 py-2">Resultado</th>
                            <th class="px-2 py-2">Observación</th>
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


