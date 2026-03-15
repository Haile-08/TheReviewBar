<script setup>
import { ref, onMounted, onUnmounted } from 'vue';
import { Head } from "@inertiajs/vue3";
import NavLink from "@/components/shared/NavLink.vue";

const reviews = [
    {
        handle: "@CINEPHILE_MAX",
        role: "CRITIC",
        time: "2 hours ago",
        highlightTime: false,
        text: "“A total mind-bend that defines modern sci-fi. Nolan's layering remains a masterpiece of practical effects.”",
        avatar: "https://i.pravatar.cc/150?img=11",
        image: "https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=400&auto=format&fit=crop", 
    },
    {
        handle: "@CLARA_WRITES",
        role: "EDITOR",
        time: "Just now",
        highlightTime: true,
        isRecommended: true,
        text: "“The architecture of dread is palpable here. Every shot is a masterclass in composition and lighting.”",
        avatar: "https://i.pravatar.cc/150?img=5",
        image: "https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=400&auto=format&fit=crop",
    },
    {
        handle: "@MARCUS_V",
        role: "JOURNALIST",
        time: "1 day ago",
        highlightTime: false,
        text: "“A bold return to form for the genre. While pacing is occasionally uneven, the visual payoff is immense.”",
        avatar: "https://i.pravatar.cc/150?img=12",
        image: "https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=400&auto=format&fit=crop",
    },
    {
        handle: "@SARAH_FILMS",
        role: "DIRECTOR",
        time: "2 days ago",
        highlightTime: false,
        text: "“Incredible sound design. The tension is built not just through the visuals, but through the overwhelming auditory experience.”",
        avatar: "https://i.pravatar.cc/150?img=9",
        image: "https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=400&auto=format&fit=crop",
    },
    {
        handle: "@DAVID_REVIEWS",
        role: "CRITIC",
        time: "5 hours ago",
        highlightTime: true,
        isRecommended: true,
        text: "“Perhaps the most visceral cinema experience of the decade. It refuses to hold your hand, and is all the better for it.”",
        avatar: "https://i.pravatar.cc/150?img=14",
        image: "https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=400&auto=format&fit=crop",
    },
    {
        handle: "@ELENA_G",
        role: "CONTRIBUTOR",
        time: "3 days ago",
        highlightTime: false,
        text: "“Stunningly gorgeous, though it asks a lot of its audience's patience. A rewarding slow burn.”",
        avatar: "https://i.pravatar.cc/150?img=20",
        image: "https://images.unsplash.com/photo-1626814026160-2237a95fc5a0?q=80&w=400&auto=format&fit=crop",
    }
];

const activeIndex = ref(0);
let autoplayInterval;

const startAutoplay = () => {
    autoplayInterval = setInterval(() => {
        activeIndex.value = (activeIndex.value + 1) % reviews.length;
    }, 3500); 
};

const stopAutoplay = () => {
    clearInterval(autoplayInterval);
};

const getCardClasses = (index) => {
    const N = reviews.length;
    const active = activeIndex.value;
    const diff = (index - active + N) % N;

    if (diff === 0) {
        return 'z-30 scale-100 md:scale-[1.15] opacity-100 -translate-x-1/2 border-green-500/50 shadow-[0_20px_70px_-10px_rgba(34,197,94,0.25)] cursor-default';
    } else if (diff === 1) {
        return 'z-20 scale-[0.85] md:scale-[0.85] opacity-40 translate-x-[15%] md:translate-x-[75%] border-white/5 cursor-pointer hover:opacity-70';
    } else if (diff === N - 1) {
        return 'z-20 scale-[0.85] md:scale-[0.85] opacity-40 -translate-x-[115%] md:-translate-x-[175%] border-white/5 cursor-pointer hover:opacity-70';
    } else if (diff === 2) {
        return 'z-10 scale-75 opacity-0 translate-x-[160%] pointer-events-none';
    } else {
        return 'z-10 scale-75 opacity-0 -translate-x-[260%] pointer-events-none';
    }
};

onMounted(() => {
    startAutoplay();
});

onUnmounted(() => {
    stopAutoplay();
});
</script>

<template>
    <Head title="Home" />
    
    <div class="relative min-h-screen bg-[#030504] text-white font-sans overflow-hidden selection:bg-emerald-500/30 flex flex-col">
        
        <div class="absolute top-[-10%] left-[-15%] w-[70vw] h-[80vh] bg-[#0d4a33]/50 blur-[160px] rounded-full pointer-events-none"></div>
        <div class="absolute top-[30%] left-[-5%] w-[40vw] h-[60vh] bg-[#165b45]/30 blur-[140px] rounded-full pointer-events-none"></div>
        <div class="absolute bottom-[-20%] right-[-10%] w-[50vw] h-[50vh] bg-[#059669]/10 blur-[150px] rounded-full pointer-events-none"></div>

        <div class="relative z-10 w-full max-w-7xl mx-auto px-6 lg:px-8 flex flex-col flex-grow">
            
            <nav class="flex items-center justify-between py-8">
                <div class="flex items-center gap-3 font-bold text-xl tracking-tight">
                    <div class="grid grid-cols-2 gap-[2px]">
                        <div class="w-2.5 h-2.5 bg-green-500 rounded-sm"></div>
                        <div class="w-2.5 h-2.5 bg-green-500/60 rounded-sm"></div>
                        <div class="w-2.5 h-2.5 bg-green-500/80 rounded-sm"></div>
                        <div class="w-2.5 h-2.5 bg-green-500/40 rounded-sm"></div>
                    </div>
                    ourtakeone
                </div>

                <div class="hidden md:flex items-center gap-6 text-xs font-bold tracking-widest text-gray-400">
                    <NavLink route-name="login" class="hover:text-white transition-colors">LOGIN</NavLink>
                    <NavLink route-name="register" class="bg-white text-black px-6 py-2.5 rounded-lg hover:bg-gray-200 transition-colors">
                        REGISTER
                    </NavLink>
                </div>
            </nav>

            <main class="flex-grow flex flex-col items-center justify-center pt-6 pb-20 w-full gap-12">
                
                <header class="text-center w-full max-w-3xl flex flex-col items-center gap-6 relative z-20">
                    
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-500/10 border border-green-500/20 text-green-400 text-[10px] font-bold tracking-[0.2em]">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        STRUCTURAL INSIGHT
                    </div>
                    
                    <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.05]">
                        Dynamic Flow of <br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-white via-gray-200 to-gray-500">
                            Curated Insight
                        </span>
                    </h1>
                    
                    <p class="text-gray-400/90 text-sm md:text-lg font-light leading-relaxed max-w-2xl">
                        Join a premier community of cinephiles and industry critics. Deconstruct the architecture of modern cinema, build your personal archive, and discover reviews that go beyond the surface.
                    </p>
                </header>

                <div class="w-full max-w-2xl mb-10 h-px bg-gradient-to-r from-transparent via-green-500 to-transparent opacity-80 shadow-[0_0_20px_rgba(34,197,94,0.7)] relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-green-300 to-transparent blur-[1px] opacity-60"></div>
                </div>

                <div 
                    class="relative w-full max-w-6xl h-[550px] perspective-[1200px]"
                    @mouseenter="stopAutoplay"
                    @mouseleave="startAutoplay"
                >
                    <div 
                        v-for="(review, index) in reviews" 
                        :key="index"
                        @click="activeIndex = index"
                        class="absolute top-0 left-1/2 w-[85%] max-w-[340px] md:max-w-[380px] bg-[#0a100c]/60 backdrop-blur-2xl backdrop-saturate-150 border-t border-l border-white/10 border-b-white/5 border-r-white/5 rounded-3xl p-6 md:p-7 flex flex-col gap-5 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] origin-center will-change-transform"
                        :class="getCardClasses(index)"
                    >
                        <div v-if="review.isRecommended" class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-green-400 to-green-600 text-black text-[10px] font-bold px-4 py-1.5 rounded-full tracking-wider shadow-lg shadow-green-500/20">
                            RECOMMENDED
                        </div>

                        <div class="flex justify-between items-start">
                            <div class="flex gap-3.5 items-center">
                                <img :src="review.avatar" alt="Avatar" class="w-11 h-11 rounded-full bg-gray-800 object-cover border border-white/10 shadow-inner" />
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-sm md:text-base font-bold text-white/95">{{ review.handle }}</span>
                                    <span class="text-[9px] md:text-[10px] text-green-400 font-bold tracking-widest">{{ review.role }}</span>
                                </div>
                            </div>
                            
                            <div class="px-2.5 py-1 rounded-md text-[10px] font-medium tracking-wide border border-white/5 backdrop-blur-sm whitespace-nowrap" 
                                 :class="review.highlightTime ? 'bg-green-500/90 text-black border-transparent font-bold' : 'bg-white/5 text-gray-400'">
                                {{ review.time }}
                            </div>
                        </div>

                        <p class="text-gray-300/90 text-sm leading-relaxed flex-grow font-light italic">
                            {{ review.text }}
                        </p>

                        <div class="w-full h-48 md:h-56 rounded-xl overflow-hidden relative border border-white/5 group">
                            <div class="absolute inset-0 bg-gradient-to-t from-[#0a100c] via-black/20 to-transparent z-10 mix-blend-multiply transition-opacity duration-500 group-hover:opacity-50"></div>
                            <img :src="review.image" alt="Scene" class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700 transform group-hover:scale-105" />
                        </div>
                    </div>
                </div>

            </main>

            <footer class="py-8 flex flex-col md:flex-row gap-4 justify-between items-center text-[10px] font-bold tracking-widest text-gray-500 border-t border-white/5 w-full">
                <p>© 2024 OURTAKEONE — STRUCTURAL MINIMALISM</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-gray-200 transition-colors">PRIVACY POLICY</a>
                    <a href="#" class="hover:text-gray-200 transition-colors">TERMS OF SERVICE</a>
                    <a href="#" class="hover:text-gray-200 transition-colors">CONTACT</a>
                </div>
            </footer>
        </div>
    </div>
</template>