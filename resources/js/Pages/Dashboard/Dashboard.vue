<script setup>
import { Link, usePage, Head } from "@inertiajs/vue3";
import { computed } from "vue";

const page = usePage();

const user = computed(() => page.props.auth.user);
const posts = computed(() => page.props.posts);
</script>

<template>
    {{ console.log(posts) }}
    <Head title="Dashboard" />
    <div
        class="min-h-screen flex flex-col w-full px-20 bg-[#121212] font-sans text-white"
    >
        <!-- header -->
        <div
            class="w-full h-12 items-center px-10 flex justify-between bg-green-500 text-black"
        >
            <div><h1>User Dashboard</h1></div>
            <div class="flex justify-evenly">
                <img
                    :src="'/storage/' + user.profile"
                    alt="Description"
                    class="h-10 w-10"
                />
                <h2 class="mr-10">{{ user.name }}</h2>
                <Link method="post" as="button" :href="route('logout')"
                    >Logout</Link
                >
            </div>
        </div>
        <!-- post -->
        <div></div>
        <!-- posts -->
        <div
            v-if="Object.keys(posts).length"
            class=" w-full flex flex-col justify-center"
        >
            <div
                v-for="post in posts"
                :key="post.id"
                class="flex flex-col justify-center"
            >
                <div class="flex justify-center flex-col">
                    <div class="flex">
                        <p>{{ post.name }}</p>
                        <p>{{ post.created_at_human }}</p>
                    </div>
                    <div>
                        <p>{{ post.description }}</p>
                    </div>
                    <div>
                        <img
                    :src="post.poster"
                    :alt="post.movie"
                    class="h-10 w-10"
                />
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>
