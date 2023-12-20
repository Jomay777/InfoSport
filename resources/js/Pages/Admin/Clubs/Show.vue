<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";
import Table from "@/Components/Table.vue";
import TableRow from "@/Components/TableRow.vue";
import TableDataCell from "@/Components/TableDataCell.vue";
import TableHeaderCell from "@/Components/TableHeaderCell.vue";
import { onMounted, watch } from "vue";

const props = defineProps({
  club: {
    type: Object,
    required: true,
  }
});
const form = useForm({
  logo_path: props.club.logo_path,
  id: props.club.id,
  name: props.club.name,
  coach: props.club.coach,
  users: props.club.users
});

</script>

<template>
  <Head title="Ver club" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('clubs.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Back</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Ver Club</h1>
        <form @submit.prevent="form.put(route('clubs.edit', club.id))">
          <div class="mt-4">
            <h2 class="mt-6 text-xl font-semibold text-gray-900 dark:text-white" for="id" value="Identificador">Identificador</h2>
            <label for="id">{{ form.id }}</label>
            <InputLabel for="logo" value="Logo"></InputLabel>
            <img class=" bg-cover bg-center" :src="form.logo_path" alt="logo de club"/>
            
            <InputLabel for="name" value="Nombre"></InputLabel>
            <label for="name">{{ form.name }}</label>
            <InputLabel for="coach" value="Coach"></InputLabel>
            <label for="coach">{{ form.coach }}</label>
            <InputLabel for="user" value="Delegado"></InputLabel>
            
            <ul v-if="form.users.length > 0">
              <li v-for="user in form.users" :key="user.id">
                {{ user.name }}
              </li>
            </ul>
            <p v-else>Sin Deledado</p>
            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          
        </form>
      </div>
    </div>
  </AdminLayout>
</template>
