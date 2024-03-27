<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    canLogin: {
        type: Boolean,
    },
    canRegister: {
        type: Boolean,
    },
    laravelVersion: {
        type: String,
        required: true,
    },
    phpVersion: {
        type: String,
        required: true,
    },
});
</script>

<template>
    <Head title="Bienvenido" />
    <!-- dark:bg-dots-lighter  bg-gradient-to-b dark:from-gray-900 dark:to-gray-800 dark:bg-gray-900 -->  
    <div
        class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-gradient-to-b from-gray-100 to-gray-400 bg-center  selection:bg-red-500 selection:text-white"        
    >    
        
        <div v-if="canLogin" class="sm:fixed sm:top-0 sm:right-0 p-6 text-right">                    
            <Link
                    :href="route('published_tournaments')"
                    class="font-semibold text-blue-900 hover:text-green-500 hover:outline hover:p-1  hover:outline-2  hover:ring hover:rounded-sm hover:outline-blue-900"
                    >Rol de Partidos</Link
                >
            <Link
                    :href="route('position_tables')"
                    class="ml-4 font-semibold text-green-500 hover:text-blue-900 hover:outline hover:p-1  hover:outline-2  hover:ring hover:rounded-sm hover:outline-green-500"
                    >Tabla de Posiciones</Link
                >
            <Link
                v-if="$page.props.auth.user"
                :href="route('dashboard')"
                class="ml-4 font-semibold text-blue-900 hover:text-green-500 hover:outline hover:p-1  hover:outline-2  hover:ring hover:rounded-sm hover:outline-blue-900"
                >Dashboard</Link
            >

            <template v-else>
                <Link
                    :href="route('login')"
                    class="ml-4 font-semibold text-blue-900 hover:text-green-500 hover:outline hover:p-1  hover:outline-2  hover:ring hover:rounded-sm hover:outline-blue-900"
                    >Iniciar sesión</Link
                >
                <Link
                    v-if="canRegister"
                    :href="route('register')"
                    class="ml-4 font-semibold text-green-500 hover:text-blue-900 hover:outline hover:p-1  hover:outline-2  hover:ring hover:rounded-sm hover:outline-green-500"
                    >Registrarse</Link
                >
            </template>
        </div>
              
        <div class="max-w-7xl mx-auto p-6 lg:p-8">
            <div class="h-30 flex justify-between">
                <div class="relative w-1/2">
                    <img src="/storage/bermejoEscudo.png" alt="escudo" class="absolute top-0 left-0 w-20 mt-8">
                </div>
                <div class="relative w-1/2">
                    <img src="/storage/bermejoEscudo.png" alt="escudo" class="absolute top-0 right-0 w-20 mt-8">
                </div>
            </div>
            <div class="flex justify-center mt-5">
                <Link rel="stylesheet" href="/">
                <svg 
                xmlns="https://www.w3.org/2000/svg" 
                height="60" width="60"
                viewBox="0 0 512 512" fill="none"
                class="fill-blue-900  hover:fill-green-500 "
                    >
                    <path d="M417.3 360.1l-71.6-4.8c-5.2-.3-10.3 1.1-14.5 4.2s-7.2 7.4-8.4 12.5l-17.6 69.6C289.5 445.8 273 448 256 448s-33.5-2.2-49.2-6.4L189.2 372c-1.3-5-4.3-9.4-8.4-12.5s-9.3-4.5-14.5-4.2l-71.6 4.8c-17.6-27.2-28.5-59.2-30.4-93.6L125 228.3c4.4-2.8 7.6-7 9.2-11.9s1.4-10.2-.5-15l-26.7-66.6C128 109.2 155.3 89 186.7 76.9l55.2 46c4 3.3 9 5.1 14.1 5.1s10.2-1.8 14.1-5.1l55.2-46c31.3 12.1 58.7 32.3 79.6 57.9l-26.7 66.6c-1.9 4.8-2.1 10.1-.5 15s4.9 9.1 9.2 11.9l60.7 38.2c-1.9 34.4-12.8 66.4-30.4 93.6zM256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm14.1-325.7c-8.4-6.1-19.8-6.1-28.2 0L194 221c-8.4 6.1-11.9 16.9-8.7 26.8l18.3 56.3c3.2 9.9 12.4 16.6 22.8 16.6h59.2c10.4 0 19.6-6.7 22.8-16.6l18.3-56.3c3.2-9.9-.3-20.7-8.7-26.8l-47.9-34.8z"/>
                </svg> 
                </Link>                                              
            </div>

            <div class="max-w-7xl mx-auto mt-10 p-6 lg:p-8 overflow-hidden bg-gradient-to-b from-slate-300 to-gray-100  rounded-lg">            
                <div id="main_container" 
                class="flex justify-center  before:bg-gradient-to-t before:from-teal-500/70 before:via-fuchsia-600 before:to-transparent before:blur-xl before:filter">
                
                    <h1 class="title text-6xl font-black text-blue-900 ">Info</h1>
                    <h2 class="cursive text-6xl font-thin text-green-500">Sport</h2>                    
                </div>
                <div class="mt-10 flex">
                    <div class="sm:w-full mt-10 md:w-1/2 ">
                        <p class="text-gray-600">
                        La <span class="text-green-500 font-extrabold">Asociación Municipal de Fútbol de Salón Bermejo</span>, arraigada en la pasión por el deporte, ha dejado una marca distintiva en la comunidad local. Su fundación se centra en fomentar el fútbol de salón y promover valores como el juego limpio y la inclusión. 
                        </p>
                        <br>
                        <p class="text-gray-600">
                            Esta entidad ha logrado integrar a personas de todas las edades y habilidades, convirtiéndose en un pilar esencial en Bermejo.
                        </p>
                        <br>
                        <p hidden class="text-gray-600 md:block">
                            La asociación ha organizado numerosos torneos locales que fortalecen los lazos comunitarios y brindan una plataforma para exhibir habilidades futbolísticas. 
                        </p> 
                        <br>
                        <p hidden class="text-gray-600 md:block">
                            Su enfoque inclusivo se traduce en un espacio donde la pasión por el deporte se fusiona con la camaradería. Más allá del campo de juego, la asociación está comprometida con programas de responsabilidad social, empoderando a jóvenes mediante iniciativas educativas y de desarrollo personal.
                        </p>

                    </div>
                    <div hidden class="md:block md:w-1/2">
                        <img class="bg-cover bg-center w-full" src="/storage/jugadores.webp" alt="jugadores">
                    </div>
                    
                </div>
            </div>
            
            <div class="flex justify-center mt-16 px-6 sm:items-center sm:justify-between">
                <div class="text-center text-sm text-gray-900  sm:text-left">
                    <div class="flex items-center gap-4">
                        <a
                            href="https://github.com/sponsors/taylorotwell" target="_blank"   
                            class="group inline-flex items-center hover:text-green-400  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >
                            <svg
                                xmlns="https://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke-width="1.5"
                                class="-mt-px mr-1 w-5 h-5 stroke-gray-900 group-hover:stroke-green-400 "
                            >
                                <path
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"
                                />
                            </svg>
                            <span hidden class="md:block" >
                            Esponsor
                        </span>
                        </a>
                    </div>
                </div>

                <div class="ml-4 text-center text-sm text-gray-900  sm:text-right sm:ml-0">
                    <a
                            href="https://github.com/Jomay777" target="_blank"   
                            class="group inline-flex items-center hover:text-green-400  focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                        >                       
                        <svg 
                            xmlns="https://www.w3.org/2000/svg" 
                            fill="none"
                            viewBox="0 0 640 512"
                            stroke-width="1.5"
                            class="-mt-px mr-1 w-5 h-5 fill-gray-900  group-hover:fill-green-400"

                            >
                            <path 
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                d="M392.8 1.2c-17-4.9-34.7 5-39.6 22l-128 448c-4.9 17 5 34.7 22 39.6s34.7-5 39.6-22l128-448c4.9-17-5-34.7-22-39.6zm80.6 120.1c-12.5 12.5-12.5 32.8 0 45.3L562.7 256l-89.4 89.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0l112-112c12.5-12.5 12.5-32.8 0-45.3l-112-112c-12.5-12.5-32.8-12.5-45.3 0zm-306.7 0c-12.5-12.5-32.8-12.5-45.3 0l-112 112c-12.5 12.5-12.5 32.8 0 45.3l112 112c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L77.3 256l89.4-89.4c12.5-12.5 12.5-32.8 0-45.3z"/>
                        </svg>
                        <span hidden class="md:block" >
                            Desarrollado por Joel M.Y.
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</template>


