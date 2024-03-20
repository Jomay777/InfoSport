<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head } from '@inertiajs/vue3';
</script>

<template>
    <Head title="Panel de Administrador" />

    <AdminLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Panel</h2>
        </template>

        <div class="py-4 bg-gray-100 rounded-md p-5">
            <h3 class="font-semibold text-xl text-gray-800 dark:text-gray-800 leading-tight">Bienvenido al panel de Administración</h3>            
            <p class="font-semibold mt-4 text-l text-gray-800 dark:text-gray-600 leading-tight">Tienes estos roles <span class="text-green-900">{{ $page.props.auth.user.roles }}</span></p>

            <p class="font-semibold mt-4 text-l text-gray-800 dark:text-gray-600 leading-tight">Tienes estos permisos: <span class="text-blue-800 hover:text-blue-900 dark:text-blue-700">{{ $page.props.auth.user.permissions }}</span></p>
        </div>
    </AdminLayout>
</template>
