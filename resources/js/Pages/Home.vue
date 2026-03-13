<script setup>
import { Switch } from "@/components/ui/switch";
import { Head } from "@inertiajs/vue3"
</script>

<template>
    <Head title="Home"/>
    <div class="p-10">
        <Switch default-checked />
        <h1 class="text-white bg-red-500">Hello</h1>
    </div>
</template>
