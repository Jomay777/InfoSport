<script>
export default {  
  name: 'Bienvenido',
};  
</script>
<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import GameRoleShow from "@/Pages/Admin/GameRoles/Show.vue";

components: {
  GameRoleShow
}
const props = defineProps({
  game_role: {
    type: Object,
    required: true,
  }, 
  game_scheduling: {
    type: Array,
    required: true,
  }
});
//const gameRoleDate = new Date(game_role.date);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">¡Tu sesión ha iniciado correctamente,
              <span class=" font-extrabold ">{{$page.props.auth.user.name}}</span>!</h2>
        </template>
        <!-- <div class="py-4 bg-gray-100 rounded-md p-5 mx-10 mt-10">
            <h3 class="font-semibold text-xl text-gray-800 leading-tight">Bienvenido a InfoSport</h3>            
            <p class="font-semibold mt-4 text-l text-gray-800 dark:text-gray-600 leading-tight">Tienes estos roles:<br> 
              <span class="text-green-900" v-for=" (role, c = 1) in $page.props.auth.user.roles">{{ c = c+1 }}.- {{ role }}.</span>
            </p>
          <p class="font-semibold mt-4 text-l text-gray-800 dark:text-gray-600 leading-tight">Tienes estos permisos: <br>
              <span class="text-blue-800 hover:text-blue-900 dark:text-blue-700" v-for=" (permission, index ) in $page.props.auth.user.permissions">{{ index = index+1 }}.- {{ permission  }}. <br></span>
            </p>
        </div> -->

    </AuthenticatedLayout>
</template>
