<script>
  export default {
    time: 'GameSchedulingEdit'
  }
</script>

<script setup>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Head, Link, useForm } from "@inertiajs/vue3";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import VueMultiselect from "vue-multiselect";

import { onMounted, ref } from "vue";
import VueTimepicker from 'vue3-timepicker'
import 'vue3-timepicker/dist/VueTimepicker.css'

const props = defineProps({
  game_scheduling: {
    type: Object,
    required: true
  },
  game_role: Array,
  teamA:{
    type: Array,
    required: true
  },
  teamB:{
    type: Array,
    required: true
  },
  position_tables:{
    type: Array,
    required: true
  }
});

const form = useForm({
  time: props.game_scheduling?.time,
  game_role: null, 
  teamA: [],
  teamB:[],
  _method: "put"
});

onMounted(() => {
  form.teamA = props.game_scheduling?.teamA;
  form.teamB = props.game_scheduling?.teamB;

  //form.game_role = {id:props.game_scheduling?.gameRole.id ,name:props.game_scheduling?.gameRole.name + ' - ' + props.game_scheduling?.gameRole.tournament.name + ' - ' + props.game_scheduling?.gameRole.tournament.category.name};
  form.game_role =  props.game_scheduling?.gameRole;
});

const updateGameScheduling = () => {
  form.post(route('game_schedulings.update', props.game_scheduling?.id));
};
const dispatchAction = () => {
  if(form.teamA?.id === form.teamB?.id){
    form.teamB = {id:'', name:''}
  }

};
const dispatchActionTeamATeamB = () => {
  form.teamA = '';
  form.teamB = '';
}
</script>

<template>
  <Head title="Actualizar programación de partido" />

  <AdminLayout>
    <div class="max-w-7xl mx-auto py-4">
      <div class="flex justify-between">
        <Link
          :href="route('game_schedulings.index')"
          class="px-3 py-2 text-white font-semibold bg-indigo-500 hover:bg-indigo-700 rounded"
          >Volver</Link
        >
      </div>
      <div class="mt-6 max-w-6xl mx-auto bg-slate-100 shadow-lg rounded-lg p-6">
        <h1 class="text-2xl font-semibold text-indigo-700">Actualizar programación de partido</h1>
        <form @submit.prevent="updateGameScheduling" >
          <div class="mt-4">
            <InputLabel for="time" value="Hora" />
            <vue-timepicker 
              id="time"
              v-model="form.time"
              format="HH:mm"              
              :second-interval="60"
              :minute-interval="5"
              label="name"
              track-by="id"
              required
            />
            <InputError class="mt-2" :message="form.errors.time" />
          </div> 
          <div class="mt-4 text-gray-700" v-if="form.game_role">
            <InputLabel for="tournament" value="Torneo - Categoría" />
            <span  id="tournament" class="font-bold text-green-900"> {{  game_role.find(role => role.id === form.game_role.id)?.tournament?.name }} - </span>
            <span id="tournament" class="font-bold text-blue-900">{{  game_role.find(role => role.id === form.game_role.id)?.tournament?.category?.name }}</span>
          </div> 
          <div class="mt-4">
            <InputLabel for="game_role" value="Rol de partido" />
            <VueMultiselect
              id="game_role"
              v-model="form.game_role"
              :options="form.game_role ? game_role.filter(item => item.tournament_id === form.game_role.tournament_id)                  
              : []"
              :multiple="false"
              :close-on-select="true"
              :preselect-first="true"
              placeholder="Escoge el rol de partido"
              label="name"
              track-by="id"
            />
            <InputError class="mt-2" :message="form.errors.game_role" />
          </div>
           
          <div class="mt-4">
              <InputLabel for="teamA" value="Equipo A" />
              <VueMultiselect
                  id="teamA"
                  v-model="form.teamA"
                  :options="form.game_role ? teamA.filter(item => item.category?.id === game_role.find(role => role.id === form.game_role.id)?.tournament?.category?.id)
                    .map(item =>({
                      id: item?.id,
                      name: `${item?.name} - ${item.category?.name}`
                    }))
                  : []"
                  :multiple="false"            
                  :close-on-select="true"
                  @select="dispatchAction"
                  :disabled=" true"
                  placeholder="Escoge equipo A"
                  label="name"
                  track-by="id"
              />
              <InputError class="mt-2" :message="form.errors.teamA" />
          </div>
          <div class="mt-4">
              <InputLabel for="teamB" value="Equipo B" />
              <VueMultiselect
                  id="teamB"
                  v-model="form.teamB"
                  :options="form.game_role ? teamB.filter(item => item.category?.id === (game_role.find(role => role.id === form.game_role.id)?.tournament?.category?.id) &&  item.id !== form.teamA?.id)
                    .map(item =>({
                      id: item.id,
                      name: `${item.name} - ${item.category?.name}`
                    }))
                  : []"
                  :multiple="false"            
                  :close-on-select="true"
                  :disabled=" true"

                  placeholder="Escoge equipo B"
                  label="name"
                  track-by="id"
              />
              <InputError class="mt-2" :message="form.errors.teamB" />
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
    </div>
  </AdminLayout>
</template>
<style src="vue-multiselect/dist/vue-multiselect.css"></style>
