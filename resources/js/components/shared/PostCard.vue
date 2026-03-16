<script setup>
import { router } from "@inertiajs/vue3";
import { Card, CardHeader, CardTitle, CardContent } from "@/components/ui/card";

const props = defineProps({
    post: Object,
});

const handleDeletePost = () => {
    if (confirm("Are you sure?")) {
        router.delete(`/post/${props.post.id}`);
    }
};
</script>
<template>
    <Card class="overflow-hidden">
        <CardHeader class="pb-2">
            <div class="flex justify-between items-center">
                <CardTitle class="text-base font-bold">{{
                    post.name
                }}</CardTitle>
                <span class="text-sm text-muted-foreground">{{
                    post.created_at_human
                }}</span>
                <button @click="handleDeletePost">Delete</button>
            </div>
        </CardHeader>
        <CardContent>
            <p class="text-foreground mb-4">
                {{ post.description }}
            </p>
            <div class="border rounded-lg overflow-hidden flex bg-muted/20">
                <img
                    v-if="post.poster"
                    :src="'https://image.tmdb.org/t/p/w200' + post.poster"
                    :alt="post.movie"
                    class="w-24 object-cover"
                />
                <div class="p-4 flex items-center">
                    <span class="font-bold">{{ post.movie }}</span>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
