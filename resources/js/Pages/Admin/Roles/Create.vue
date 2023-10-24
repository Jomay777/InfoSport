<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

import Checkbox from '@/Components/Checkbox.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const form = useForm({
    name: ""
});
</script>

<template>
    <Head title="Crear nuevo role" />

    <AdminLayout>       
        <div class="max-w-7xl mx-auto py-4">
            <div class="flex justify-between">
                <Link :href="route('roles.index')" class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounted">
                    Volver
                </Link>                        
            </div>
            <div class="mt-6 max-w-md">
                <h1 class="text-2xl p-4">Crear un nuevo rol</h1>
                <form @submit.prevent="form.post(route('roles.store'))">
                    <div>
                        <InputLabel for="name" value="Nombre" />

                        <TextInput
                            id="name"
                            type="text"
                            class="mt-1 block w-full"
                            v-model="form.name"                        
                            autofocus                            
                            autocomplete="username"
                        />

                        <InputError class="mt-2" :message="form.errors.name" />
                    </div>                    
                    <div class="flex items-center mt-4">                        
                        <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Crear
                        </PrimaryButton>
                    </div>
                </form>
            </div>     
        </div>
    </AdminLayout>
</template>
