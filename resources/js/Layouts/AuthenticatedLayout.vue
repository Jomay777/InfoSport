<script setup>
import { ref } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/vue3';
import { usePermission } from "@/composables/permissions"

const showingNavigationDropdown = ref(false);
const { hasPermission, hasRole } = usePermission();
</script>

<template>
    <div>
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link rel="stylesheet" href="/">
                                    <svg 
                                    xmlns="https://www.w3.org/2000/svg" 
                                    height="25" width="25"
                                    viewBox="0 0 512 512" fill="none"
                                    class="fill-blue-900 dark:fill-gray-400 hover:fill-green-500 dark:hover:fill-gray-100"
                                        >
                                        <path d="M417.3 360.1l-71.6-4.8c-5.2-.3-10.3 1.1-14.5 4.2s-7.2 7.4-8.4 12.5l-17.6 69.6C289.5 445.8 273 448 256 448s-33.5-2.2-49.2-6.4L189.2 372c-1.3-5-4.3-9.4-8.4-12.5s-9.3-4.5-14.5-4.2l-71.6 4.8c-17.6-27.2-28.5-59.2-30.4-93.6L125 228.3c4.4-2.8 7.6-7 9.2-11.9s1.4-10.2-.5-15l-26.7-66.6C128 109.2 155.3 89 186.7 76.9l55.2 46c4 3.3 9 5.1 14.1 5.1s10.2-1.8 14.1-5.1l55.2-46c31.3 12.1 58.7 32.3 79.6 57.9l-26.7 66.6c-1.9 4.8-2.1 10.1-.5 15s4.9 9.1 9.2 11.9l60.7 38.2c-1.9 34.4-12.8 66.4-30.4 93.6zM256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zm14.1-325.7c-8.4-6.1-19.8-6.1-28.2 0L194 221c-8.4 6.1-11.9 16.9-8.7 26.8l18.3 56.3c3.2 9.9 12.4 16.6 22.8 16.6h59.2c10.4 0 19.6-6.7 22.8-16.6l18.3-56.3c3.2-9.9-.3-20.7-8.7-26.8l-47.9-34.8z"/>
                                    </svg> 
                                </Link>     
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <NavLink 
                                    :href="route('dashboard')" 
                                    :active="route().current('dashboard')">
                                    Bienvenido
                                </NavLink>
                                <NavLink        
                                    v-if="hasRole('Administrador')"                         
                                    :href="route('users.index')" 
                                    :active="route().current('users.index')">
                                    Panel de Administrador
                                </NavLink>
                                <NavLink        
                                    v-if="hasPermission('Ver club') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver equipo') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver torneo') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver categoría') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver jugador') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver pase') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver rol de partido') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver programación de partido') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver partido') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')"                         
                                    :href="route('administration.index')" 
                                    :active="route().current('administration.index')">
                                    Panel de Administración
                                </NavLink>
                                <NavLink        
                                    v-if="hasRole('Comité técnico')"                         
                                    :href="route('clubs.index')" 
                                    :active="route().current('clubs.index')">
                                    Comité Técnico
                                </NavLink>
                                <NavLink        
                                    v-if="hasRole('Delegado')"                         
                                    :href="route('clubs.index')" 
                                    :active="route().current('clubs.index')">
                                    Delegado
                                </NavLink>
                                <NavLink        
                                    v-if="hasRole('Asistente')"                         
                                    :href="route('game_roles.index')" 
                                    :active="route().current('game_roles.index')">
                                    Asistente
                                </NavLink>
                                <NavLink        
                                    v-if="hasRole('Directiva')"                         
                                    :href="route('clubs.index')" 
                                    :active="route().current('clubs.index')">
                                    Directiva
                                </NavLink>
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="https://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Perfil </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Finalizar sesión
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <ResponsiveNavLink :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </ResponsiveNavLink>
                        <ResponsiveNavLink  
                                    v-if="hasPermission('Ver club') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver equipo') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver torneo') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver categoría') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver jugador') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver pase') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver rol de partido') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver programación de partido') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')  ||
                                    hasPermission('Ver partido') && !hasRole('Administrador') && !hasRole('Comité técnico') && !hasRole('Delegado') && !hasRole('Asistente') && !hasRole('Directiva')"                         
                                                             
                                    :href="route('administration.index')" 
                                    :active="route().current('Administration.index')">
                                    Panel de administración
                        </ResponsiveNavLink>
                        <ResponsiveNavLink  v-if="hasRole('Administrador')"                         
                                    :href="route('users.index')" 
                                    :active="route().current('users.index')">
                                    Administrador
                        </ResponsiveNavLink>
                        <ResponsiveNavLink  v-if="hasRole('Comité Técnico')"                         
                                    :href="route('clubs.index')" 
                                    :active="route().current('clubs.index')">
                                    Comité Técnico
                        </ResponsiveNavLink>
                        <ResponsiveNavLink  v-if="hasRole('Delegado')"                         
                                    :href="route('clubs.index')" 
                                    :active="route().current('clubs.index')">
                                    Delegado
                        </ResponsiveNavLink>
                        <ResponsiveNavLink  v-if="hasRole('Asistente')"                         
                                    :href="route('game_roles.index')" 
                                    :active="route().current('game_roles.index')">
                                    Asistente
                        </ResponsiveNavLink>
                        <ResponsiveNavLink  v-if="hasRole('Directiva')"                         
                                    :href="route('clubs.index')" 
                                    :active="route().current('clubs.index')">
                                    Directiva
                        </ResponsiveNavLink>
                       
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800 dark:text-gray-200">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Perfil </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Finalizar sesión
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
            <header class="bg-white dark:bg-gray-800 shadow" v-if="$slots.header">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
