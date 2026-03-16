<script setup>
import { Link, usePage, Head, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import axios from "axios";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm, useField } from "vee-validate";
import * as z from "zod";

import { Avatar, AvatarImage, AvatarFallback } from "@/components/ui/avatar";

import {
    Search,
    X,
    CheckCircle2,
    Image as ImageIcon,
    Bell,
    MessageSquare,
    Bookmark,
    ArrowUp,
    ArrowDown,
    MoreHorizontal,
    ChevronDown,
} from "lucide-vue-next";

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

const { handleSubmit, resetForm, errors, setErrors } = useForm({
    validationSchema: formSchema,
    initialValues: {
        description: "",
        movie: null,
    },
});

const { value: description } = useField("description");
const { value: movie } = useField("movie");

const searchQuery = ref("");
const results = ref([]);
const isLoading = ref(false);
const showDropdown = ref(false);

let controller = null;
let searchTimeout = null;

const handleSearch = async (query) => {
    if (!query) {
        results.value = [];
        showDropdown.value = false;
        isLoading.value = false;
        return;
    }

    if (controller) controller.abort();
    controller = new AbortController();

    isLoading.value = true;
    showDropdown.value = true;

    try {
        const response = await axios.get("/movies/search", {
            params: { query: query },
            signal: controller.signal,
        });

        results.value = response.data.results || [];
        isLoading.value = false;
    } catch (err) {
        if (!axios.isCancel(err)) {
            console.error("Search error:", err);
            isLoading.value = false;
        }
    }
};

watch(searchQuery, (newVal) => {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        handleSearch(newVal);
    }, 300);
});

const selectMovie = (selectedMovie) => {
    movie.value = selectedMovie;
    searchQuery.value = "";
    showDropdown.value = false;
};

const clearMovie = () => {
    movie.value = null;
};

const submitPost = handleSubmit((value) => {
    const post = {
        movie: value.movie.title || value.movie.name,
        poster: value.movie.poster_path,
        description: value.description,
    };

    router.post("/post", post, {
        preserveScroll: true,
        onSuccess: () => resetForm(),
        onError: (backendErrors) => setErrors(backendErrors),
    });
});
</script>

<template>
    <Head title="OurTakeOne Archive" />

    <div class="min-h-screen flex w-full bg-[#030c08] font-sans text-gray-300 selection:bg-[#00e599] selection:text-black">
        
        <div class="flex-1 flex flex-col p-8 lg:p-12 xl:p-16 h-screen overflow-y-auto custom-scrollbar relative">
            
            <header class="flex justify-between items-center mb-12">
                <div class="flex items-center gap-4">
                    <div class="grid grid-cols-2 gap-1 w-5 h-5">
                        <div class="bg-[#00e599] rounded-sm"></div>
                        <div class="bg-[#00e599] rounded-sm"></div>
                        <div class="bg-[#00e599]/40 rounded-sm"></div>
                        <div class="bg-[#00e599] rounded-sm"></div>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-lg tracking-[0.2em] leading-none uppercase">
                            OURTAKEONE
                        </h1>
                        <p class="text-[9px] text-gray-500 tracking-[0.2em] mt-1 uppercase">
                            DIGITAL ARCHIVE INTERFACE
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-2.5 bg-white/[0.02] border border-white/5 rounded-xl hover:bg-white/10 transition">
                        <Search class="w-4 h-4 text-gray-400" />
                    </button>
                    <Link
                        method="post"
                        as="button"
                        :href="route('logout')"
                        class="text-xs text-red-500/80 hover:text-red-500 tracking-widest uppercase block transition lg:hidden"
                    >
                        Logout
                    </Link>
                </div>
            </header>

            <div v-if="status" class="mb-6 p-4 rounded-xl bg-[#00e599]/10 border border-[#00e599]/20 text-[#00e599] flex items-center gap-3">
                <CheckCircle2 class="h-5 w-5" />
                <span class="font-medium text-sm">{{ status }}</span>
            </div>

            <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-3 px-4 mb-8 transition-all focus-within:border-[#00e599]/30 relative z-20">
                <form @submit.prevent="submitPost" class="flex flex-col gap-3">
                    <div class="flex items-center gap-4">
                        <Avatar class="h-8 w-8 shrink-0 border border-white/10">
                            <AvatarImage :src="'/storage/' + user.profile" alt="User Profile" />
                            <AvatarFallback class="bg-[#0a1e16] text-[#00e599] text-xs">
                                {{ user.name.charAt(0) }}
                            </AvatarFallback>
                        </Avatar>

                        <input
                            v-model="description"
                            type="text"
                            placeholder="Share a new cinematic entry..."
                            class="flex-1 bg-transparent border-none shadow-none focus-visible:ring-0 text-gray-300 placeholder:text-gray-500 placeholder:italic text-sm outline-none"
                        />

                        <button
                            type="submit"
                            class="shrink-0 flex items-center gap-1.5 text-[10px] font-bold text-[#00e599] hover:text-white transition tracking-widest uppercase"
                        >
                            QUICK SHARE <ChevronDown class="w-3 h-3" />
                        </button>
                    </div>
                    
                    <div class="w-full flex justify-between items-center">
                        <p v-if="errors.description" class="text-red-500/80 text-[10px] ml-12">
                            {{ errors.description }}
                        </p>
                        <p v-if="errors.movie && !movie" class="text-red-500/80 text-[10px] ml-12">
                            {{ errors.movie }}
                        </p>
                    </div>

                    <div v-if="movie" class="ml-12 inline-flex items-center gap-3 bg-[#0a1e16] border border-[#00e599]/20 rounded-lg p-1.5 pr-3 w-max">
                        <img v-if="movie.poster_path" :src="'https://image.tmdb.org/t/p/w200' + movie.poster_path" class="w-6 h-8 object-cover rounded shadow-sm" />
                        <div>
                            <h3 class="font-bold text-white text-xs">{{ movie.title || movie.name }}</h3>
                            <p class="text-[10px] text-[#00e599]/70">{{ movie.release_date ? new Date(movie.release_date).getFullYear() : "N/A" }}</p>
                        </div>
                        <button type="button" class="ml-2 text-gray-500 hover:text-red-400 transition" @click="clearMovie">
                            <X class="h-3 w-3" />
                        </button>
                    </div>
                </form>
            </div>

            <div class="relative bg-[#05110c] border border-white/5 rounded-xl p-2 pl-5 flex items-center gap-4 mb-12 z-30">
                <input
                    v-model="searchQuery"
                    type="text"
                    placeholder="EXPLORE FILMS & CINEMATIC ENTRIES..."
                    class="flex-1 bg-transparent border-none text-white outline-none placeholder:text-gray-700 text-[10px] tracking-[0.2em] uppercase"
                />
                <button class="bg-white/[0.04] border border-white/5 px-6 py-2 rounded-lg text-[10px] font-bold text-gray-500 tracking-widest hover:bg-white/10 transition uppercase">
                    ARCHIVE
                </button>

                <div v-if="showDropdown && (results.length || isLoading)" class="absolute top-full left-0 right-0 mt-2 bg-[#06140e] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
                    <div v-if="isLoading" class="p-4 text-sm text-[#00e599] animate-pulse">
                        Searching archives...
                    </div>
                    <div v-else class="max-h-[300px] overflow-y-auto custom-scrollbar p-2">
                        <div v-for="item in results" :key="item.id" class="flex items-center gap-4 p-2 rounded-lg hover:bg-white/5 cursor-pointer transition-all" @click="selectMovie(item)">
                            <img v-if="item.poster_path" :src="'https://image.tmdb.org/t/p/w200' + item.poster_path" class="w-10 h-14 object-cover rounded shadow-sm" />
                            <div v-else class="w-10 h-14 bg-white/5 flex items-center justify-center rounded text-gray-600">
                                <ImageIcon class="h-4 w-4" />
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-200 text-sm">{{ item.title || item.name }}</h3>
                                <p class="text-xs text-gray-500">{{ item.release_date ? new Date(item.release_date).getFullYear() : "N/A" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 pb-12 z-10 w-full">
                <div v-for="post in posts" :key="post.id" class="flex flex-col gap-4">
                    
                    <div class="bg-black border border-white/5 rounded-xl overflow-hidden relative group">
                        <div class="w-full flex items-center justify-center relative overflow-hidden bg-[#0a1e16]">
                            <img v-if="post.poster" :src="'https://image.tmdb.org/t/p/w1280' + post.poster" class="w-full h-auto object-cover max-h-[400px] transition duration-500 opacity-90 group-hover:opacity-100 group-hover:scale-105" />
                            <div v-else class="w-full aspect-video flex items-center justify-center text-[#00e599] italic font-serif text-3xl">
                                {{ post.movie }}
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start justify-between px-1">
                        <div class="flex gap-3 items-start">
                            <Avatar class="h-10 w-10 border border-white/5 mt-1">
                                <AvatarImage :src="'/storage/' + user.profile" />
                                <AvatarFallback class="bg-black">{{ post.name ? post.name.charAt(0) : "U" }}</AvatarFallback>
                            </Avatar>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-white font-bold text-sm">{{ post.name || "Jane Doe" }}</h4>
                                    <span class="text-[#00e599] text-xs">{{ user.email || 'user@email.com' }}</span>
                                </div>
                                <p class="text-sm text-gray-300 mt-1 leading-relaxed">
                                    {{ post.description }}
                                </p>
                            </div>
                        </div>
                        <span class="text-[10px] text-gray-600 font-bold uppercase tracking-wider mt-1">{{ post.created_at_human || '2H' }}</span>
                    </div>

                    <div class="flex items-center gap-3 px-1 mt-1">
                        <button class="flex items-center gap-2 bg-white/[0.02] hover:bg-white/[0.06] border border-white/5 rounded-xl px-4 py-2 text-[11px] font-bold text-white transition">
                            <ArrowUp class="w-3.5 h-3.5 text-gray-400" /> 1.2K
                        </button>
                        <button class="flex items-center gap-2 bg-white/[0.02] hover:bg-white/[0.06] border border-white/5 rounded-xl px-4 py-2 text-[11px] font-bold text-white transition">
                            <ArrowDown class="w-3.5 h-3.5 text-gray-400" /> 42
                        </button>
                        <button class="ml-auto text-gray-600 hover:text-white transition">
                            <MoreHorizontal class="w-5 h-5" />
                        </button>
                    </div>
                    
                </div>
            </div>
        </div>

        <div class="w-[320px] xl:w-[380px] border-l border-white/5 bg-[#030c08] p-10 flex flex-col hidden lg:flex justify-between h-screen sticky top-0">
            <div>
                <h3 class="text-[10px] text-[#00e599] tracking-[0.3em] font-bold mb-8 uppercase">
                    System Identity
                </h3>

                <div class="border border-white/5 rounded-2xl overflow-hidden bg-[#05110c] mb-12 shadow-2xl">
                    <div class="h-20 bg-[#00e599] relative p-4 flex justify-end">
                        <Link :href="route('profile')" class="bg-white text-black hover:bg-gray-100 text-[9px] font-bold px-4 py-2 rounded-lg tracking-widest uppercase transition-colors h-max">
                            Enter Dashboard
                        </Link>
                    </div>
                    <div class="px-6 relative pb-6">
                        <div class="absolute -top-10 left-6">
                            <Avatar class="w-20 h-20 ring-[6px] ring-[#05110c] border border-white/10 rounded-xl overflow-hidden">
                                <AvatarImage :src="'/storage/' + user.profile" alt="User Profile" class="object-cover" />
                                <AvatarFallback class="bg-[#0a1e16] text-3xl text-[#00e599] rounded-xl">{{ user.name.charAt(0) }}</AvatarFallback>
                            </Avatar>
                            <div class="absolute bottom-1 right-1 w-3.5 h-3.5 bg-[#00e599] rounded-full border-[3px] border-[#05110c]"></div>
                        </div>

                        <div class="mt-12 flex justify-between items-start">
                            <div>
                                <h2 class="text-white font-bold text-lg">{{ user.name || 'Alex Rivera' }}</h2>
                                <p class="text-[#00e599] text-xs mt-0.5">{{ user.email || 'arivera@email.com' }}</p>
                            </div>
                            <Link
                                method="post"
                                as="button"
                                :href="route('logout')"
                                class="text-[10px] font-bold text-red-500/60 hover:text-red-500 tracking-widest uppercase mt-1 transition"
                            >
                                Logout
                            </Link>
                        </div>

                        <div class="flex justify-between mt-8 border-t border-white/5 pt-5">
                            <div class="text-center">
                                <div class="text-white font-bold text-sm">842</div>
                                <div class="text-[8px] text-gray-500 tracking-[0.15em] mt-1 uppercase">Following</div>
                            </div>
                            <div class="text-center">
                                <div class="text-white font-bold text-sm">1.2K</div>
                                <div class="text-[8px] text-gray-500 tracking-[0.15em] mt-1 uppercase">Followers</div>
                            </div>
                            <div class="text-center">
                                <div class="text-white font-bold text-sm">354</div>
                                <div class="text-[8px] text-gray-500 tracking-[0.15em] mt-1 uppercase">Logs</div>
                            </div>
                        </div>
                    </div>
                </div>

                <nav class="space-y-6 px-2">
                    <a href="#" class="flex items-center gap-4 text-xs font-bold text-gray-400 hover:text-[#00e599] transition tracking-widest uppercase">
                        <Bell class="w-4 h-4 text-[#00e599]" /> Feed Alerts
                    </a>
                    <a href="#" class="flex items-center gap-4 text-xs font-bold text-gray-400 hover:text-[#00e599] transition tracking-widest uppercase">
                        <MessageSquare class="w-4 h-4 text-[#00e599]" /> Direct Link
                    </a>
                    <a href="#" class="flex items-center gap-4 text-xs font-bold text-gray-400 hover:text-[#00e599] transition tracking-widest uppercase">
                        <Bookmark class="w-4 h-4 text-[#00e599]" /> Saved Logs
                    </a>
                </nav>
            </div>

            <div class="px-2">
                <div class="flex gap-6 mb-4">
                    <a href="#" class="text-[9px] text-gray-600 tracking-widest hover:text-white transition uppercase">Privacy</a>
                    <a href="#" class="text-[9px] text-gray-600 tracking-widest hover:text-white transition uppercase">Terms</a>
                </div>
                <p class="text-[9px] text-gray-700 tracking-[0.2em] uppercase">
                    &copy; 2026 OURTAKEONE ARCHIVE
                </p>
            </div>
        </div>
    </div>
</template>