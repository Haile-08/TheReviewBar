<script setup>
defineProps({
    title: { type: String, default: "Welcome\nBack" },
    subtitle: {
        type: String,
        default: "Manage your projects and team in one place.",
    },
    formTitle: { type: String, default: "Sign In" },
    formSubtitle: {
        type: String,
        default: "Enter your credentials to access your account.",
    },
    steps: {
        type: Array,
        default: () => [],
    },
    // Added currentStep prop (0-indexed)
    currentStep: {
        type: Number,
        default: 0, 
    },
});
</script>

<template>
    <div class="min-h-screen flex w-full bg-[#121212] font-sans text-white">
        <div class="hidden lg:flex w-1/2 relative p-16 flex-col justify-between overflow-hidden">
            <div class="absolute inset-0 bg-gradient-to-br from-[#0c4a36] via-[#052b1f] to-[#02120d] z-0"></div>

            <div class="relative z-10 flex items-center gap-3">
                <div class="grid grid-cols-2 gap-[2px]">
                    <div class="w-2.5 h-2.5 bg-green-500 rounded-sm"></div>
                    <div class="w-2.5 h-2.5 bg-green-500/60 rounded-sm"></div>
                    <div class="w-2.5 h-2.5 bg-green-500/80 rounded-sm"></div>
                    <div class="w-2.5 h-2.5 bg-green-500/40 rounded-sm"></div>
                </div>
                <span class="font-bold tracking-tight text-xl">ourtakeone</span>
            </div>

            <div class="relative z-10 max-w-lg xl:ml-12">
                <h1 class="text-5xl font-semibold mb-4 leading-tight whitespace-pre-line">
                    {{ title }}
                </h1>
                <p class="text-emerald-200/70 text-lg mb-16 max-w-xs">
                    {{ subtitle }}
                </p>

                <div v-if="steps.length" class="flex gap-4">
                    <div
                        v-for="(step, index) in steps"
                        :key="index"
                        :class="[
                            'rounded-[1.25rem] p-5 w-36 h-36 flex flex-col justify-between transition-transform hover:-translate-y-1',
                            /* Dynamically highlight the current step */
                            index === currentStep
                                ? 'bg-white shadow-2xl'
                                : 'bg-white/5 backdrop-blur-md border border-white/10',
                        ]"
                    >
                        <div
                            :class="[
                                'w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold',
                                index === currentStep
                                    ? 'bg-black text-white'
                                    : 'bg-white/10 text-white',
                            ]"
                        >
                            {{ index + 1 }}
                        </div>
                        <div
                            :class="[
                                'font-medium leading-snug text-[15px]',
                                index === currentStep ? 'text-black' : 'text-white/70',
                            ]"
                            v-html="step"
                        ></div>
                    </div>
                </div>
            </div>

            <div class="relative z-10 text-emerald-200/40 text-sm">
                &copy; 2026 BrandName Inc.
            </div>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col items-center justify-center p-8 sm:p-12 bg-[#0a0a0a]">
            <div class="w-full max-w-md">
                <div class="text-center mb-10">
                    <h2 class="text-2xl font-semibold mb-2">{{ formTitle }}</h2>
                    <p class="text-zinc-400 text-sm">
                        {{ formSubtitle }}
                    </p>
                </div>

                <slot />
            </div>
        </div>
    </div>
</template>