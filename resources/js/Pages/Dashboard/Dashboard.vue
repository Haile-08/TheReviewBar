<script setup lang="ts">
import { Link, usePage, Head, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import axios from "axios";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import * as z from "zod";

import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import {
    FormControl,
    FormField,
    FormItem,
    FormMessage,
} from "@/components/ui/form";
import {
    Card,
    CardHeader,
    CardTitle,
    CardContent,
    CardFooter,
} from "@/components/ui/card";
import { Avatar, AvatarImage, AvatarFallback } from "@/components/ui/avatar";
import { ScrollArea } from "@/components/ui/scroll-area";

import { Search, X, CheckCircle2, Image as ImageIcon } from "lucide-vue-next";

defineProps({
    status: String,
});

const page = usePage();
const user = computed(() => page.props.auth.user);
const posts = computed(() => page.props.posts);

const formSchema = toTypedSchema(
    z.object({
        description: z
            .string()
            .min(1, "What's on your mind? Description is required."),
        movie: z.any().refine((val) => val !== null, {
            message: "Please select a movie to tag.",
        }),
    }),
);

const { handleSubmit, setFieldValue, values, resetForm } = useForm({
    validationSchema: formSchema,
    initialValues: {
        description: "",
        movie: null,
    },
});

const searchQuery = ref("");
const results = ref([]);
const isLoading = ref(false);
let controller = null;

const handleSearch = async (query) => {
    if (!query) {
        results.value = [];
        return;
    }

    if (controller) controller.abort();
    controller = new AbortController();

    isLoading.value = true;

    try {
        const response = await axios.get("/movies/search", {
            params: { query: query },
            signal: controller.signal,
        });

        results.value = response.data.results || [];
    } catch (err) {
        if (!axios.isCancel(err)) {
            console.error("Search error:", err);
        }
    } finally {
        isLoading.value = false;
    }
};

watch(searchQuery, (newVal) => {
    handleSearch(newVal);
});

const selectMovie = (movie) => {
    setFieldValue("movie", movie);
    searchQuery.value = "";
};

const clearMovie = () => {
    setFieldValue("movie", null);
};

const submitPost = handleSubmit((value) => {
    const post = {
        movie: value.movie.title,
        poster: value.movie.poster_path,
        description: value.description,
    };

    router.post("/post", post);
});
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
                <Link
                    method="post"
                    as="button"
                    :href="route('logout')"
                    class="font-bold hover:underline"
                    >Logout</Link
                >
            </div>
        </div>

        <div
            class="px-4 py-10 w-full max-w-4xl mx-auto flex flex-col items-center"
        >
            <Card class="w-full max-w-2xl shadow-lg border-muted">
                <CardHeader
                    class="flex flex-row items-center justify-between border-b pb-4 pt-4 px-6 bg-card"
                >
                    <CardTitle
                        class="text-lg font-bold flex items-center gap-2"
                    >
                        <span class="text-primary text-xl">🎬</span> Create Post
                    </CardTitle>
                    <Button
                        variant="ghost"
                        size="icon"
                        class="h-8 w-8 rounded-full text-muted-foreground"
                    >
                        <X class="h-5 w-5" />
                    </Button>
                </CardHeader>

                <form @submit="submitPost">
                    <CardContent class="p-6">
                        <FormField
                            v-slot="{ componentField }"
                            name="description"
                        >
                            <FormItem class="flex gap-4 mb-6">
                                <Avatar class="h-10 w-10 mt-1">
                                    <AvatarImage
                                        :src="'/storage/' + user.profile"
                                        alt="User"
                                    />
                                    <AvatarFallback>{{
                                        user.name.charAt(0)
                                    }}</AvatarFallback>
                                </Avatar>
                                <div class="flex-1 space-y-1">
                                    <FormControl>
                                        <Textarea
                                            v-bind="componentField"
                                            placeholder="What are you watching?"
                                            class="w-full resize-none border-none shadow-none focus-visible:ring-0 text-lg placeholder:text-muted-foreground p-0 min-h-[80px]"
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </div>
                            </FormItem>
                        </FormField>

                        <FormField name="movie">
                            <FormItem>
                                <div
                                    v-if="values.movie"
                                    class="relative bg-secondary/50 border rounded-xl p-3 flex items-center justify-between transition-all group"
                                >
                                    <div class="flex items-center gap-4">
                                        <img
                                            v-if="values.movie.poster_path"
                                            :src="
                                                'https://image.tmdb.org/t/p/w200' +
                                                values.movie.poster_path
                                            "
                                            :alt="values.movie.title"
                                            class="w-14 h-20 object-cover rounded shadow-sm"
                                        />
                                        <div
                                            v-else
                                            class="w-14 h-20 bg-muted flex flex-col items-center justify-center rounded shadow-sm text-muted-foreground"
                                        >
                                            <ImageIcon
                                                class="h-6 w-6 mb-1 opacity-50"
                                            />
                                        </div>

                                        <div>
                                            <h3
                                                class="font-bold text-foreground text-lg"
                                            >
                                                {{
                                                    values.movie.title ||
                                                    values.movie.name
                                                }}
                                            </h3>
                                            <p
                                                class="text-muted-foreground text-sm"
                                            >
                                                {{
                                                    values.movie.release_date
                                                        ? new Date(
                                                              values.movie
                                                                  .release_date,
                                                          ).getFullYear()
                                                        : "N/A"
                                                }}
                                            </p>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 pr-2">
                                        <CheckCircle2
                                            class="h-6 w-6 text-primary"
                                        />
                                        <Button
                                            type="button"
                                            variant="ghost"
                                            size="icon"
                                            class="opacity-0 group-hover:opacity-100 transition-opacity text-destructive hover:text-destructive hover:bg-destructive/10 h-8 w-8"
                                            title="Remove movie"
                                            @click="clearMovie"
                                        >
                                            <X class="h-4 w-4" />
                                        </Button>
                                    </div>
                                </div>

                                <div v-else class="relative space-y-4">
                                    <div class="relative flex items-center">
                                        <Search
                                            class="absolute left-3 h-5 w-5 text-muted-foreground"
                                        />
                                        <Input
                                            v-model="searchQuery"
                                            type="text"
                                            placeholder="Search for a movie to tag..."
                                            class="pl-10 py-6 bg-secondary/40 border-transparent focus-visible:ring-primary focus-visible:border-transparent text-md rounded-xl"
                                        />
                                    </div>

                                    <div
                                        v-if="isLoading"
                                        class="text-primary text-sm font-medium animate-pulse ml-1"
                                    >
                                        Searching...
                                    </div>

                                    <ScrollArea
                                        v-if="results.length && !isLoading"
                                        class="h-[280px] rounded-md"
                                    >
                                        <div class="flex flex-col gap-2 pr-4">
                                            <p
                                                class="text-xs font-bold text-muted-foreground uppercase tracking-wider mb-2 ml-1"
                                            >
                                                Suggested Movies
                                            </p>

                                            <div
                                                v-for="item in results"
                                                :key="item.id"
                                                class="flex items-center gap-4 p-2 rounded-xl border border-transparent hover:border-border hover:bg-muted/50 cursor-pointer transition-all"
                                                @click="selectMovie(item)"
                                            >
                                                <img
                                                    v-if="item.poster_path"
                                                    :src="
                                                        'https://image.tmdb.org/t/p/w200' +
                                                        item.poster_path
                                                    "
                                                    :alt="item.title"
                                                    class="w-12 h-16 object-cover rounded-md shadow-sm"
                                                />
                                                <div
                                                    v-else
                                                    class="w-12 h-16 bg-muted flex items-center justify-center rounded-md text-muted-foreground"
                                                >
                                                    <ImageIcon
                                                        class="h-5 w-5 opacity-50"
                                                    />
                                                </div>

                                                <div class="flex-1">
                                                    <h3
                                                        class="font-bold text-foreground"
                                                    >
                                                        {{
                                                            item.title ||
                                                            item.name
                                                        }}
                                                    </h3>
                                                    <p
                                                        class="text-sm text-muted-foreground"
                                                    >
                                                        {{
                                                            item.release_date
                                                                ? new Date(
                                                                      item.release_date,
                                                                  ).getFullYear()
                                                                : "N/A"
                                                        }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </ScrollArea>

                                    <div
                                        v-else-if="
                                            searchQuery &&
                                            !isLoading &&
                                            !results.length
                                        "
                                        class="text-muted-foreground mt-4 ml-1"
                                    >
                                        No movies found for "{{ searchQuery }}"
                                    </div>
                                    <FormMessage />
                                </div>
                            </FormItem>
                        </FormField>
                    </CardContent>

                    <CardFooter
                        class="bg-muted/30 px-6 py-4 flex justify-end gap-3 border-t"
                    >
                        <Button
                            type="button"
                            variant="ghost"
                            class="text-muted-foreground hover:text-foreground"
                            @click="() => resetForm()"
                        >
                            Cancel
                        </Button>
                        <Button
                            type="submit"
                            class="bg-[#ff5722] hover:bg-[#f4511e] text-white px-8 rounded-full"
                        >
                            Post
                        </Button>
                    </CardFooter>
                </form>
            </Card>

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
