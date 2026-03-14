<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const user = computed(()=> page.props.auth.user)
</script>

<template>
    <h1>User Dashboard</h1>
    <h2>{{ user.name }}</h2>
    <Link 
    method="post" as="button" :href="route('logout')">Logout</Link>
</template>