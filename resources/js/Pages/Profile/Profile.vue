<script setup lang="ts">
import { usePage, Head } from "@inertiajs/vue3";
import { computed } from "vue";

import { Alert, AlertDescription } from "@/components/ui/alert";
import {
    CheckCircleIcon,
    AlertCircleIcon,
} from "lucide-vue-next";

import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/components/ui/avatar";

defineProps({
    status: String,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const posts = computed(() => page.props.posts);
</script>

<template>
    <Head title="Dashboard" />
    <div
        class="min-h-screen flex flex-col w-full bg-background font-sans text-foreground"
    >
        <Alert
            v-if="status"
            class="bg-emerald-500/10 border-emerald-500/20 text-emerald-400"
        >
            <CheckCircleIcon class="h-4 w-4 text-emerald-500" />
            <AlertDescription class="font-medium text-sm ml-2">
                {{ status }}
            </AlertDescription>
        </Alert>

        <Alert
            v-if="
                page.props.errors && Object.keys(page.props.errors).length > 0
            "
            variant="destructive"
            class="bg-red-500/10 border-red-500/20 text-red-400"
        >
            <AlertCircleIcon class="h-4 w-4" />
            <AlertDescription>
                <p class="mb-2 font-medium">
                    Please fix the errors below to continue:
                </p>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    <li
                        v-for="(errorMessage, field) in page.props.errors"
                        :key="field"
                    >
                        {{ errorMessage }}
                    </li>
                </ul>
            </AlertDescription>
        </Alert>
        <div
            class="w-full h-12 items-center px-10 flex justify-between bg-primary text-primary-foreground"
        >
            <div><h1 class="font-bold">User Dashboard</h1></div>
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <Avatar class="h-8 w-8">
                        <AvatarImage
                            :src="'/storage/' + user.profile"
                            alt="User Profile"
                        />
                        <AvatarFallback>{{
                            user.name.charAt(0)
                        }}</AvatarFallback>
                    </Avatar>
                    <h2 class="font-medium">{{ user.name }}</h2>
                </div>
            </div>
        </div>

        <div
            class="px-4 py-10 w-full max-w-4xl mx-auto flex flex-col items-center"
        >
            <div
                v-if="posts && Object.keys(posts).length"
                class="w-full max-w-2xl mt-12 flex flex-col gap-6"
            >
                <Card
                    v-for="post in posts"
                    :key="post.id"
                    class="overflow-hidden"
                >
                    <CardHeader class="pb-2">
                        <div class="flex justify-between items-center">
                            <CardTitle class="text-base font-bold">{{
                                post.name
                            }}</CardTitle>
                            <span class="text-sm text-muted-foreground">{{
                                post.created_at_human
                            }}</span>
                        </div>
                    </CardHeader>
                    <CardContent>
                        <p class="text-foreground mb-4">
                            {{ post.description }}
                        </p>
                        <div
                            class="border rounded-lg overflow-hidden flex bg-muted/20"
                        >
                            <img
                                v-if="post.poster"
                                :src="
                                    'https://image.tmdb.org/t/p/w200' +
                                    post.poster
                                "
                                :alt="post.movie"
                                class="w-24 object-cover"
                            />
                            <div class="p-4 flex items-center">
                                <span class="font-bold">{{ post.movie }}</span>
                            </div>
                        </div>
                    </CardContent>
                </Card>
            </div>
        </div>
    </div>
</template>
