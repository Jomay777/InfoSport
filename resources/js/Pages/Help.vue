<script>
export default {  
  name: 'Ayuda',
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

});
//const gameRoleDate = new Date(game_role.date);

</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">¡Hola,
              <span class=" font-extrabold ">{{$page.props.auth.user.name}}</span>!</h2>
        </template>
        <div class="py-4 bg-white rounded-md p-5 md:mx-10 mt-10">
            <div>
                <div hidden>
                    <img src="https://cdn.pixabay.com/photo/2013/07/13/10/51/football-157930_1280.png" alt="Pelota">
                </div> 
                <div>
                    <h3 class="font-semibold text-green-500 leading-tight text-center">
                Bienvenido a la Sección de Ayuda de <span class="text-blue-900 font-extrabold">Info</span><span>Sport</span>
                    </h3>
                </div>
                <div></div>
            </div>
            

            <h4 class="font-semibold mt-4 text-l leading-tight">
                <span class="text-blue-900 font-extrabold">Info</span><span class="text-green-500">Sport</span><span class="text-blue-900 font-extrabold">: Permisos y Roles</span>
            </h4>

            <h4 class="font-semibold mt-3 text-l leading-tight">
                Introducción
            </h4>

            <p>
                InfoSport es un sistema que utiliza permisos y roles para controlar el acceso a sus funciones. Para que un usuario pueda realizar una acción en el sistema, el administrador debe asignarle un rol que tenga los permisos necesarios.
            </p>

            <h4>Permisos</h4>

            <p>
                Los permisos son acciones específicas que un usuario puede realizar en el sistema. En InfoSport, hay 48 permisos disponibles, que se agrupan en las siguientes categorías:
            </p>

            <ol>
                <li class="greenT">Roles: Crear, ver, actualizar y eliminar roles.</li>
                <li class="blueT">Usuarios: Crear, ver, actualizar y eliminar usuarios.</li>
                <li class="greenT">Clubes: Crear, ver, actualizar y eliminar clubes.</li>
                <li class="blueT">Categorías: Crear, ver, actualizar y eliminar categorías.</li>
                <li class="greenT">Torneos: Crear, ver, actualizar y eliminar torneos.</li>
                <li class="blueT">Equipos: Crear, ver, actualizar y eliminar equipos.</li>
                <li class="greenT">Jugadores: Crear, ver, actualizar y eliminar jugadores.</li>
                <li class="blueT">Pases: Crear, ver, actualizar y eliminar pases.</li>
                <li class="greenT">Partidos: Crear, ver, actualizar y eliminar partidos.</li>
            </ol>

            <h4>Roles</h4>

            <p>
                Los roles son conjuntos de permisos que se asignan a los usuarios. En InfoSport, hay 5 roles predefinidos:
            </p>

            <ul>
                <li class="greenT">Administrador: Tiene todos los permisos del sistema.</li>
                <li class="blueT">Comité técnico: Puede gestionar usuarios, roles, clubes, categorías, torneos y equipos.</li>
                <li class="greenT">Delegado: Puede gestionar jugadores, pases y partidos.</li>
                <li class="blueT">Asistente: Puede ver información del sistema, pero no puede realizar cambios.</li>
                <li class="greenT">Directiva: Puede ver información del sistema y gestionar usuarios, roles y clubes.</li>
            </ul>

            <h4>Asignación de roles y permisos</h4>

            <p>
                El administrador del sistema es responsable de asignar roles y permisos a los usuarios. Para ello, puede utilizar la sección de administración del sistema.
            </p>

            <h4>Ejemplo</h4>

            <p>
                Un usuario con el rol de "Delegado" puede:
            </p>

            <ul>
                <li class="blueT">Ver la información de los jugadores.</li>
                <li class="greenT">Crear, actualizar y eliminar jugadores.</li>
                <li class="blueT">Ver la información de los pases.</li>
                <li class="greenT">Crear, actualizar y eliminar pases.</li>
                <li class="blueT">Ver la información de los partidos.</li>
                <li class="greenT">Crear, actualizar y eliminar partidos.</li>
            </ul>

            <h4>¿Necesitas más ayuda?</h4>

            <p>
                Si necesitas más ayuda con los permisos y roles de InfoSport, puedes consultar la siguiente documentación:
            </p>

            <ul>
                <li>
                    <a href="https://www.techsmith.es/blog/manual-usuario/"
                    class="hover:text-blue-600">Manual de usuario</a>
                </li>
                <li>
                    <a href="https://es.slideshare.net/OtildeLucyAlarconPaz/guia-para-permisospdf"
                    class="hover:text-blue-600">Guía de permisos</a>
                </li>
                <li>
                    <a href="https://es.wikipedia.org/wiki/Preguntas_frecuentes"
                    class="hover:text-blue-600">Preguntas frecuentes</a>
                </li>
            </ul>

            <h4>Contacto</h4>
            <p>
                Contacto:
                Si tienes alguna pregunta o problema con los permisos y roles de InfoSport, puedes contactar con el administrador del sistema.

                Esperamos que esta información te haya sido útil.

                ¡Gracias por usar InfoSport!
            </p>
        </div>            

    </AuthenticatedLayout>
</template>
<style>
    p{
        color : #666565;
        margin-left: 1rem;
        margin-top: 0.5rem;
    }
    h3{
        font-size: 2rem; /* Applies a font size of 1rem */  
    }
    h4 {
        font-weight: bold; /* Applies bold font weight */ 
        margin-top: 1rem; /* Applies a margin-top of 1rem */   
        font-size: 1.3rem; /* Applies a font size of 1rem */  
        color: #13b65c; /* Applies a dark gray color */
        /*color: #717171; /* Applies a lighter gray color for dark mode */
        line-height: 1.25; /* Sets a line-height for tighter spacing */
    }
    ul{
        /*text-decoration:dotted;*/
        margin-left: 2rem;
        /*list-style-type: circle;*/
        
    }
    ol{
        margin-left: 2rem;
        color: #b1af2f;
    }
    img {
        width: 3rem;
    }
    .greenT{
        color: #232324;
    }
    .blueT{
        color: #838386;
    }
    a {
        color:#13b65c;
    }
</style>