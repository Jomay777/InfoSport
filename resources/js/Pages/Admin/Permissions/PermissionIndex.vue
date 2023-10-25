<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

import Table from '@/Components/Table.vue';
import TableRow from '@/Components/TableRow.vue';
import TableHeaderCell from '@/Components/TableHeaderCell.vue';
import TableDataCell from '@/Components/TableDataCell.vue';

defineProps(['permissions']);
</script>

<template>
    <Head title="Permissions" />

    <AdminLayout>
        <div class="max-w-7xl mx-auto py-4">
            <div class="flex justify-between">
                <h1>Permisos Index</h1>
                <Link :href="route('permissions.create')" 
                    class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounted">
                    Nuevo Permiso
                </Link>    
                    
            </div>
            <div class="mt-6">
                <Table>
                    <template #header>
                        <TableRow>
                            <TableHeaderCell>ID</TableHeaderCell>
                            <TableHeaderCell>Nombre</TableHeaderCell>
                            <TableHeaderCell>Acciones</TableHeaderCell>
                        </TableRow>
                    </template>
                    <template #default>
                        <TableRow 
                        v-for="permission in permissions" :key="permission.id"
                        class="border-b">
                            <TableDataCell>{{ permission.id }}</TableDataCell>
                            <TableDataCell>{{ permission.name }}</TableDataCell>
                            <TableDataCell class="space-x-4">
                                <Link :href="route('permissions.edit', permission.id)" 
                                    class="text-green-400 hover:text-green-600">
                                    Editar
                                </Link>
                                <Link :href="route('permissions.destroy', permission.id)" 
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
