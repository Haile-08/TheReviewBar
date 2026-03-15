<script setup>
import { Link, usePage, Head, router } from "@inertiajs/vue3";
import { computed, ref, watch } from "vue";
import axios from "axios";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm, useField } from "vee-validate";
import * as z from "zod";

import { Textarea } from "@/components/ui/textarea";
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
    ChevronDown
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
        onError: (backendErrors) => setErrors(backendErrors)
    });
});
</script>

<template>
    <Head title="OurTakeOne Archive" />
    
    <div class="min-h-screen flex w-full bg-[#030c08] font-sans text-gray-300 selection:bg-[#00e599] selection:text-black">
        
        <div class="flex-1 flex flex-col p-8 lg:p-12 h-screen overflow-y-auto custom-scrollbar relative">
            
            <header class="flex justify-between items-center mb-12">
                <div class="flex items-center gap-4">
                    <div class="grid grid-cols-2 gap-1 w-6 h-6">
                        <div class="bg-[#00e599] rounded-sm"></div>
                        <div class="bg-[#00e599] rounded-sm"></div>
                        <div class="bg-[#00e599]/40 rounded-sm"></div>
                        <div class="bg-[#00e599] rounded-sm"></div>
                    </div>
                    <div>
                        <h1 class="text-white font-bold text-xl tracking-[0.2em] leading-none">OURTAKEONE</h1>
                        <p class="text-[10px] text-[#00e599]/60 tracking-[0.2em] mt-1">DIGITAL ARCHIVE INTERFACE</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <button class="p-3 bg-white/[0.03] border border-white/5 rounded-xl hover:bg-white/10 transition lg:hidden">
                        <Search class="w-5 h-5 text-gray-400" />
                    </button>
                    <Link method="post" as="button" :href="route('logout')" class="text-xs text-red-500/80 hover:text-red-500 tracking-widest uppercase block transition lg:hidden">
                        Logout
                    </Link>
                </div>
            </header>

            <div v-if="status" class="mb-6 p-4 rounded-xl bg-[#00e599]/10 border border-[#00e599]/20 text-[#00e599] flex items-center gap-3">
                <CheckCircle2 class="h-5 w-5" />
                <span class="font-medium text-sm">{{ status }}</span>
            </div>

            <div class="bg-white/[0.02] border border-white/5 rounded-2xl p-4 mb-6 transition-all focus-within:border-white/20 relative z-20">
                <form @submit.prevent="submitPost">
                    <div class="flex items-start gap-4">
                        <Avatar class="h-10 w-10 shrink-0">
                            <AvatarImage :src="'/storage/' + user.profile" alt="User Profile" />
                            <AvatarFallback class="bg-[#0a1e16] text-[#00e599]">{{ user.name.charAt(0) }}</AvatarFallback>
                        </Avatar>
                        
                        <div class="flex-1 space-y-4">
                            <div>
                                <Textarea
                                    v-model="description"
                                    placeholder="Share a new cinematic entry..."
                                    class="w-full resize-none bg-transparent border-none shadow-none focus-visible:ring-0 text-gray-200 placeholder:text-gray-600 p-2 min-h-[40px] text-lg"
                                />
                                <p v-if="errors.description" class="text-red-500/80 text-xs ml-2 mt-1">{{ errors.description }}</p>
                            </div>

                            <div v-if="movie" class="inline-flex items-center gap-3 bg-[#0a1e16] border border-[#00e599]/20 rounded-xl p-2 pr-4">
                                <img
                                    v-if="movie.poster_path"
                                    :src="'https://image.tmdb.org/t/p/w200' + movie.poster_path"
                                    class="w-8 h-12 object-cover rounded shadow-sm"
                                />
                                <div>
                                    <h3 class="font-bold text-white text-sm">{{ movie.title || movie.name }}</h3>
                                    <p class="text-xs text-[#00e599]/70">{{ movie.release_date ? new Date(movie.release_date).getFullYear() : "N/A" }}</p>
                                </div>
                                <button @click="clearMovie" type="button" class="ml-2 text-gray-500 hover:text-red-400 transition">
                                    <X class="h-4 w-4" />
                                </button>
                            </div>
                            <p v-if="errors.movie && !movie" class="text-red-500/80 text-xs ml-2">{{ errors.movie }}</p>
                        </div>

                        <button type="submit" class="shrink-0 flex items-center gap-2 text-xs font-bold text-[#00e599] hover:text-white transition tracking-widest mt-2">
                            QUICK SHARE <ChevronDown class="w-4 h-4" />
                        </button>
                    </div>
                </form>
            </div>

            <div class="relative bg-white/[0.02] border border-white/5 rounded-2xl p-2 pl-6 flex items-center gap-4 mb-10 z-30">
                <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="EXPLORE FILMS & CINEMATIC ENTRIES..." 
                    class="flex-1 bg-transparent border-none text-white outline-none placeholder:text-gray-700 text-sm tracking-widest" 
                />
                <button class="bg-white/5 px-6 py-3 rounded-xl text-xs font-bold text-gray-500 tracking-widest hover:bg-white/10 transition">
                    ARCHIVEK
                </button>

                <div v-if="showDropdown && (results.length || isLoading)" 
                     class="absolute top-full left-0 right-0 mt-2 bg-[#06140e] border border-white/10 rounded-xl shadow-2xl overflow-hidden z-50">
                    <div v-if="isLoading" class="p-4 text-sm text-[#00e599] animate-pulse">
                        Searching archives...
                    </div>
                    <div v-else class="max-h-[300px] overflow-y-auto custom-scrollbar p-2">
                        <div v-for="item in results" :key="item.id"
                            @click="selectMovie(item)"
                            class="flex items-center gap-4 p-2 rounded-lg hover:bg-white/5 cursor-pointer transition-all">
                            <img v-if="item.poster_path" :src="'https://image.tmdb.org/t/p/w200' + item.poster_path" class="w-10 h-14 object-cover rounded shadow-sm" />
                            <div v-else class="w-10 h-14 bg-white/5 flex items-center justify-center rounded text-gray-600"><ImageIcon class="h-4 w-4" /></div>
                            <div>
                                <h3 class="font-bold text-gray-200 text-sm">{{ item.title || item.name }}</h3>
                                <p class="text-xs text-gray-500">{{ item.release_date ? new Date(item.release_date).getFullYear() : "N/A" }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 z-10">
                <div v-for="post in posts" :key="post.id" class="flex flex-col gap-4">
                    <div class="bg-[#05140f] border border-white/5 rounded-2xl overflow-hidden relative group">
                        <div class="absolute top-0 left-0 right-0 p-4 flex justify-between items-center bg-gradient-to-b from-black/60 to-transparent z-10">
                            <span class="text-xs font-bold tracking-widest text-gray-400">Showcase</span>
                            <Search class="w-4 h-4 text-gray-400" />
                        </div>
                        
                        <div class="aspect-[4/3] bg-[#0a1e16] flex items-center justify-center relative overflow-hidden">
                            <img v-if="post.poster" :src="'https://image.tmdb.org/t/p/w500' + post.poster" class="absolute inset-0 w-full h-full object-cover transition duration-500 group-hover:scale-105" />
                            <div class="z-10 text-center p-6 border border-[#00e599]/20 bg-black/60 backdrop-blur-md rounded-xl shadow-2xl transition duration-500 group-hover:bg-black/40">
                                <h2 class="font-serif text-3xl text-white italic">{{ post.movie }}</h2>
                                <p class="text-[10px] tracking-widest text-[#00e599] mt-2 uppercase">Archived Entry</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-start justify-between px-2">
                        <div class="flex gap-3 items-center">
                            <Avatar class="h-10 w-10 border border-white/10">
                                <AvatarImage :src="'/storage/' + user.profile" />
                                <AvatarFallback class="bg-black">{{ post.name ? post.name.charAt(0) : 'U' }}</AvatarFallback>
                            </Avatar>
                            <div>
                                <div class="flex items-center gap-2">
                                    <h4 class="text-white font-bold text-sm">{{ post.name }}</h4>
                                    <span class="text-[#00e599] text-xs">@user</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-0.5">{{ post.description }}</p>
                            </div>
                        </div>
                        <span class="text-xs text-gray-600 uppercase tracking-wider">{{ post.created_at_human }}</span>
                    </div>

                    <div class="flex items-center gap-3 px-2 mt-2">
                        <button class="flex items-center gap-2 bg-white/[0.03] hover:bg-white/[0.08] border border-white/5 rounded-xl px-4 py-2 text-xs font-bold text-white transition">
                            <ArrowUp class="w-4 h-4 text-gray-400" /> 1.2K
                        </button>
                        <button class="flex items-center gap-2 bg-white/[0.03] hover:bg-white/[0.08] border border-white/5 rounded-xl px-4 py-2 text-xs font-bold text-white transition">
                            <ArrowDown class="w-4 h-4 text-gray-400" /> 42
                        </button>
                        <button class="ml-auto text-gray-600 hover:text-white transition">
                            <MoreHorizontal class="w-5 h-5" />
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-[340px] border-l border-white/5 bg-[#04100b] p-10 flex flex-col hidden lg:flex justify-between h-screen sticky top-0">
            <div>
                <h3 class="text-[10px] text-[#00e599] tracking-[0.3em] font-bold mb-12">IDENTITY</h3>
                
                <div class="flex flex-col items-center text-center">
                    <div class="relative mb-6">
                        <Avatar class="w-32 h-32 ring-4 ring-[#00e599]/10 ring-offset-8 ring-offset-[#04100b]">
                            <AvatarImage :src="'/storage/' + user.profile" alt="User Profile" />
                            <AvatarFallback class="bg-[#0a1e16] text-3xl text-[#00e599]">{{ user.name.charAt(0) }}</AvatarFallback>
                        </Avatar>
                        <div class="absolute bottom-2 right-2 w-4 h-4 bg-[#00e599] rounded-full border-4 border-[#04100b]"></div>
                    </div>
                    
                    <h2 class="text-2xl text-white font-bold">{{ user.name }}</h2>
                    <p class="text-sm text-[#00e599]/80 italic mt-1">{{ user.email }}</p>

                    <Link 
                        method="post" 
                        as="button" 
                        :href="route('logout')" 
                        class="text-[10px] font-bold text-red-500/60 hover:text-red-500 tracking-[0.2em] uppercase mt-3 transition"
                    >
                        Logout
                    </Link>

                    <button class="w-full mt-8 bg-[#00e599] hover:bg-[#00c987] text-black font-extrabold tracking-widest text-xs py-4 rounded-xl transition-all shadow-[0_0_20px_rgba(0,229,153,0.15)]">
                        ENTER DASHBOARD
                    </button>
                </div>

                <nav class="mt-16 space-y-6">
                    <a href="#" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-[#00e599] transition tracking-widest">
                        <Bell class="w-5 h-5" /> FEED ALERTS
                    </a>
                    <a href="#" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-[#00e599] transition tracking-widest">
                        <MessageSquare class="w-5 h-5" /> DIRECT LINK
                    </a>
                    <a href="#" class="flex items-center gap-4 text-sm font-bold text-gray-400 hover:text-[#00e599] transition tracking-widest">
                        <Bookmark class="w-5 h-5" /> SAVED LOGS
                    </a>
                </nav>
            </div>

            <div class="border-t border-white/5 pt-8">
                <div class="flex gap-6 mb-4">
                    <a href="#" class="text-[10px] text-gray-600 tracking-widest hover:text-white transition">PRIVACY</a>
                    <a href="#" class="text-[10px] text-gray-600 tracking-widest hover:text-white transition">TERMS</a>
                </div>
                <p class="text-[10px] text-gray-700 tracking-widest uppercase">&copy; 2026 OURTAKEONE ARCHIVE</p>
            </div>
        </div>
    </div>
</template>