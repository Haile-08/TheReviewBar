<script setup>
import { Link, usePage } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const user = computed(() => page.props.auth.user);
</script>

<template>
    <Head title="Dashboard" />
    <div
        class="min-h-screen flex flex-col w-full bg-[#121212] font-sans text-white"
    >
        <div
            class="w-full h-12 items-center px-10 flex justify-between bg-green-500 text-black"
        >
            <div><h1>User Dashboard</h1></div>
            <div class="flex justify-evenly">
                <img :src="'/storage/' + user.profile" alt="Description" class="h-10 w-10">
                <h2 class="mr-10">{{ user.name }}</h2>
                <Link method="post" as="button" :href="route('logout')"
                    >Logout</Link
                >
            </div>
        </div>
        <div>

        </div>
    </div>
</template>
