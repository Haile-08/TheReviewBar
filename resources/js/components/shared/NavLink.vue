<script setup>
import { Link } from '@inertiajs/vue3';

defineProps({
    routeName: {
        type: String,
        default: "Home",
    },
});
</script>

<template>
    <Link :href="route(routeName)" class="">
        <slot />
    </Link>
</template>
