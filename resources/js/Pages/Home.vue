<script setup>
import { ref, onMounted, onUnmounted } from "vue";
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
    },
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
        return "z-30 scale-100 md:scale-[1.15] opacity-100 -translate-x-1/2 border-primary/50 shadow-2xl shadow-primary/20 cursor-default";
    } else if (diff === 1) {
        return "z-20 scale-[0.85] opacity-40 -translate-x-[25%] sm:-translate-x-[10%] md:translate-x-[75%] border-border/50 cursor-pointer hover:opacity-70";
    } else if (diff === N - 1) {
        return "z-20 scale-[0.85] opacity-40 -translate-x-[75%] sm:-translate-x-[90%] md:-translate-x-[175%] border-border/50 cursor-pointer hover:opacity-70";
    } else if (diff === 2) {
        return "z-10 scale-75 opacity-0 translate-x-[0%] md:translate-x-[160%] pointer-events-none";
    } else {
        return "z-10 scale-75 opacity-0 -translate-x-[100%] md:-translate-x-[260%] pointer-events-none";
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

    <div
        class="dark relative min-h-screen bg-background text-foreground font-sans overflow-hidden selection:bg-primary/30 flex flex-col"
    >
        <div
            class="absolute top-[-10%] left-[-15%] w-[70vw] h-[80vh] bg-primary/20 blur-[100px] md:blur-[160px] rounded-full pointer-events-none"
        ></div>
        <div
            class="absolute top-[30%] left-[-5%] w-[40vw] h-[60vh] bg-primary/10 blur-[90px] md:blur-[140px] rounded-full pointer-events-none"
        ></div>
        <div
            class="absolute bottom-[-20%] right-[-10%] w-[50vw] h-[50vh] bg-primary/5 blur-[100px] md:blur-[150px] rounded-full pointer-events-none"
        ></div>

        <div
            class="relative z-10 w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col flex-grow"
        >
            <nav
                class="flex flex-wrap items-center justify-between py-6 md:py-8 gap-4"
            >
                <div
                    class="flex items-center gap-3 font-bold text-lg md:text-xl tracking-tight"
                >
                    <div class="grid grid-cols-2 gap-[2px]">
                        <div
                            class="w-2 md:w-2.5 h-2 md:h-2.5 bg-primary rounded-sm"
                        ></div>
                        <div
                            class="w-2 md:w-2.5 h-2 md:h-2.5 bg-primary/60 rounded-sm"
                        ></div>
                        <div
                            class="w-2 md:w-2.5 h-2 md:h-2.5 bg-primary/80 rounded-sm"
                        ></div>
                        <div
                            class="w-2 md:w-2.5 h-2 md:h-2.5 bg-primary/40 rounded-sm"
                        ></div>
                    </div>
                    ourtakeone
                </div>

                <div
                    class="flex items-center gap-3 md:gap-6 text-[10px] md:text-xs font-bold tracking-widest text-muted-foreground"
                >
                    <NavLink
                        route-name="login"
                        class="hover:text-foreground transition-colors"
                    >
                        LOGIN
                    </NavLink>
                    <NavLink
                        route-name="register"
                        class="bg-foreground text-background px-4 py-2 md:px-6 md:py-2.5 rounded-lg hover:bg-foreground/90 transition-colors"
                    >
                        REGISTER
                    </NavLink>
                </div>
            </nav>

            <main
                class="flex-grow flex flex-col items-center justify-center pt-8 pb-16 md:pb-20 w-full gap-8 md:gap-12"
            >
                <header
                    class="text-center w-full max-w-3xl flex flex-col items-center gap-4 md:gap-6 relative z-20"
                >
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-primary/10 border border-primary/20 text-primary text-[9px] md:text-[10px] font-bold tracking-[0.2em]"
                    >
                        <span
                            class="w-1.5 h-1.5 rounded-full bg-primary animate-pulse"
                        ></span>
                        MOVIE OPINION
                    </div>

                    <h1
                        class="text-4xl sm:text-5xl md:text-7xl font-extrabold tracking-tight leading-[1.1] md:leading-[1.05]"
                    >
                        Love Movies?<br />
                        <span
                            class="text-transparent bg-clip-text bg-gradient-to-r from-foreground via-foreground/80 to-muted-foreground"
                        >
                            Talk Here
                        </span>
                    </h1>

                    <p
                        class="text-muted-foreground text-xs sm:text-sm md:text-lg font-light leading-relaxed max-w-2xl px-2"
                    >
                        Join our growing community of movie lovers to chat with
                        people who love film as much as you do. Whether you're a
                        fan of big blockbusters or quiet indie films, this is
                        the place to share your thoughts, discover hidden gems,
                        and connect.
                    </p>
                </header>

                <div
                    class="w-full max-w-2xl mb-6 md:mb-10 h-px bg-gradient-to-r from-transparent via-primary to-transparent opacity-80 shadow-lg shadow-primary/50 relative"
                >
                    <div
                        class="absolute inset-0 bg-gradient-to-r from-transparent via-primary/60 to-transparent blur-[1px] opacity-60"
                    ></div>
                </div>

                <div
                    class="relative w-full max-w-6xl h-[460px] md:h-[550px] perspective-[1200px]"
                    @mouseenter="stopAutoplay"
                    @mouseleave="startAutoplay"
                >
                    <div
                        v-for="(review, index) in reviews"
                        :key="index"
                        class="absolute top-0 left-1/2 w-[90%] max-w-[320px] md:max-w-[380px] bg-card/60 backdrop-blur-2xl backdrop-saturate-150 border-t border-l border-border border-b-border/50 border-r-border/50 rounded-3xl p-5 md:p-7 flex flex-col gap-4 md:gap-5 transition-all duration-700 ease-[cubic-bezier(0.25,1,0.5,1)] origin-center will-change-transform"
                        :class="getCardClasses(index)"
                        @click="activeIndex = index"
                    >
                        <div
                            v-if="review.isRecommended"
                            class="absolute -top-3 left-1/2 -translate-x-1/2 bg-gradient-to-r from-primary/80 to-primary text-primary-foreground text-[9px] md:text-[10px] font-bold px-3 md:px-4 py-1.5 rounded-full tracking-wider shadow-lg shadow-primary/20 whitespace-nowrap"
                        >
                            RECOMMENDED
                        </div>

                        <div class="flex justify-between items-start">
                            <div class="flex gap-3 md:gap-3.5 items-center">
                                <img
                                    :src="review.avatar"
                                    alt="Avatar"
                                    class="w-10 h-10 md:w-11 md:h-11 rounded-full bg-muted object-cover border border-border shadow-inner"
                                />
                                <div class="flex flex-col gap-0.5">
                                    <span
                                        class="text-xs md:text-base font-bold text-foreground/95"
                                    >
                                        {{ review.handle }}
                                    </span>
                                    <span
                                        class="text-[8px] md:text-[10px] text-primary font-bold tracking-widest"
                                    >
                                        {{ review.role }}
                                    </span>
                                </div>
                            </div>

                            <div
                                class="px-2 py-1 md:px-2.5 rounded-md text-[9px] md:text-[10px] font-medium tracking-wide border backdrop-blur-sm whitespace-nowrap"
                                :class="
                                    review.highlightTime
                                        ? 'bg-primary/90 text-primary-foreground border-transparent font-bold'
                                        : 'bg-secondary text-muted-foreground border-border/50'
                                "
                            >
                                {{ review.time }}
                            </div>
                        </div>

                        <p
                            class="text-card-foreground/90 text-xs md:text-sm leading-relaxed flex-grow font-light italic"
                        >
                            {{ review.text }}
                        </p>

                        <div
                            class="w-full h-40 sm:h-48 md:h-56 rounded-xl overflow-hidden relative border border-border/50 group"
                        >
                            <div
                                class="absolute inset-0 bg-gradient-to-t from-card via-black/20 to-transparent z-10 mix-blend-multiply transition-opacity duration-500 group-hover:opacity-50"
                            ></div>
                            <img
                                :src="review.image"
                                alt="Scene"
                                class="w-full h-full object-cover grayscale opacity-60 group-hover:grayscale-0 group-hover:opacity-100 transition-all duration-700 transform group-hover:scale-105"
                            />
                        </div>
                    </div>
                </div>
            </main>

            <footer
                class="py-6 md:py-8 flex flex-col md:flex-row gap-6 md:gap-4 justify-between items-center text-[9px] md:text-[10px] font-bold tracking-widest text-muted-foreground border-t border-border/50 w-full text-center md:text-left"
            >
                <p>© 2024 OURTAKEONE — STRUCTURAL MINIMALISM</p>
                <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                    <a
                        href="#"
                        class="hover:text-foreground/80 transition-colors"
                        >PRIVACY POLICY</a
                    >
                    <a
                        href="#"
                        class="hover:text-foreground/80 transition-colors"
                        >TERMS OF SERVICE</a
                    >
                    <a
                        href="#"
                        class="hover:text-foreground/80 transition-colors"
                        >CONTACT</a
                    >
                </div>
            </footer>
        </div>
    </div>
</template>
