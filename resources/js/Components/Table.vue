<script setup>
</script>
<template>    
<div class="relative overflow-x-auto">
    <table class="w-full text-sm text-left text-gray-500">
        <thead class="text-xs text-gray-700 uppercase bg-gray-50 ">
           <slot name="header"/>
        </thead>
        <tbody>
            <slot></slot>
        </tbody>
    </table>
</div>

</template>