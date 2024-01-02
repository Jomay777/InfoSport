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
  },
  users: Array,
});
const form = useForm({
  name: props.club.name,
  coach: props.club.coach,
  logo_path: props.club.logo_path,
  users: [],
});

onMounted(() => {
  form.users = props.club?.users;
});
/*
watch(
  () => props.users,
);*/
</script>

<template>
  <Head title="Actualizar club" />

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
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar usuario</h1>
        <form @submit.prevent="form.put(route('clubs.update', club.id))">
          <div class="mt-4">
            <InputLabel for="name" value="Nombre" />
            <TextInput
              id="name"
              type="text"
              class="mt-1 block w-full"
              v-model="form.name"
              autofocus
              autocomplete="clubname"
            />

            <InputError class="mt-2" :message="form.errors.name" />
          </div>
          <div>
            <InputLabel for="coach" value="Profesor" />
            <TextInput
              id="coach"
              type="text"
              class="mt-1 block w-full"
              v-model="form.coach"              
              autocomplete="clubcoach"
            />
            <InputError class="mt-2" :message="form.errors.coach" />
          </div>
          <div class="mt-4">
            <InputLabel for="logo_path" value="Logo" />
            <TextInput
              id="logo_path"
              type="text"
              class="mt-1 block w-full"
              v-model="form.logo_path"              
              autocomplete="clublogo_path"
            />
            <InputError class="mt-2" :message="form.errors.logo_path" />
          </div>
          <div class="mt-4">
            <InputLabel for="users" value="Delegado" />
            <VueMultiselect
              id="users"
              v-model="form.users"
              :options="users"
              :multiple="true"
              :close-on-select="true"
              placeholder="Escoge delegados"
              label="name"
              track-by="id"
            />
          </div>
          <div class="flex items-center mt-4">
            <PrimaryButton
              class="ml-4"
              :class="{ 'opacity-25': form.processing }"
              :disabled="form.processing"
            >
              Actualizar
            </PrimaryButton>
          </div>
        </form>
      </div>

      <!--<div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Delegados</h1>
        <div class="bg-white">
          <Table>
            <template #header>
              <TableRow>
                <TableHeaderCell>ID</TableHeaderCell>
                <TableHeaderCell>Nombre</TableHeaderCell>
                <TableHeaderCell>Acción</TableHeaderCell>
              </TableRow>
            </template>
            <template #default>
              <TableRow
                v-for="clubUser in club.users"
                :key="clubUser.id"
                class="border-b"
              >
                <TableDataCell>{{ clubUser.id }}</TableDataCell>
                <TableDataCell>{{ clubUser.name }}</TableDataCell>
                <TableDataCell class="space-x-4">
                  <Link
                    :href="
                      route('clubs.destroy', [
                        club.id,
                        clubUser.id,
                      ])
                    "
                    method="DELETE"
                    as="button"
                    class="text-red-400 hover:text-red-600"
                    >Quitar</Link
                  >
                </TableDataCell>
              </TableRow>
            </template>
          </Table>
        </div>
      </div>-->
    </div>
  </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>