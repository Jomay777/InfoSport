<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';

defineProps(['users']);
</script>

<template>
    <Head title="Usuarios Index" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
            <div class="flex justify-between">
                <h1>Usuario Index Page</h1>
                <Link :href="route('users.create')" class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounted">
                    Nuevo Usuario
                </Link>    
                    
            </div>    
            <div class="mt-6">
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Nombre</TableHeaderCell>
                            <TableHeaderCell>Email</TableHeaderCell>
                            <TableHeaderCell>Acciones</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow 
                        v-for="user in users" :key="user.id"
                        class="border-b">
                            <TableDataCell>{{ user.id }}</TableDataCell>
                            <TableDataCell>{{ user.name }}</TableDataCell>
                            <TableDataCell>{{ user.email }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <Link :href="route('users.edit', user.id)" 
                                    class="text-green-400 hover:text-green-600">
                                    Editar
                                </Link>
                                <Link :href="route('users.destroy', user.id)" 
                                    method="DELETE" as="button"
                                    class="text-red-400 hover:text-red-600">
                                    Eliminar
                                </Link>
                            </TableDataCell>                  
                        </TableRow>

                    </template>
                </Table>
            </div>     
        </div>
    </AdminLayout>
</template>
