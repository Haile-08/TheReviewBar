<script setup>
import { Head } from "@inertiajs/vue3";
import NavLink from "@/components/shared/NavLink.vue";
</script>

<template>
    <Head title="Home" />
    <div class="p-10 w-dvw h-full flex justify-between items-center">
        <div class="w-full h-full flex justify-center items-center">
            <NavLink route-name="Login"> Login</NavLink>
            <NavLink route-name="register"> Register</NavLink>
        </div>
    </div>
</template>
