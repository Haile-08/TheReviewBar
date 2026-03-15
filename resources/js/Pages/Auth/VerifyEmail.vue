<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";
import AuthLayout from "@/components/layout/AuthLayout.vue";
import { Button } from "@/components/ui/button";
import { Alert, AlertDescription } from "@/components/ui/alert";
import { CheckCircle2 as CheckCircleIcon } from "lucide-vue-next";

defineProps({
    status: String,
});

const form = useForm({});

const submit = () => {
    form.post(route("verification.send"));
};
</script>

<template>
    <Head title="Email Verification" />

    <AuthLayout
    title="Secure Your Account"
        subtitle="Don't worry, it happens to the best of us."
        form-title="Verify Your Email"
        form-subtitle="We've sent a verification link to your email address. Please click the link in the message to activate your account and continue your setup."
        :steps="[
            'Create <br /> account',
            'Verify <br /> identity',
            'Access <br /> dashboard',
        ]"
        :current-step="1"
    >
        <div class="flex flex-col items-center text-center space-y-6 w-full max-w-sm mx-auto">
            <Alert
                v-if="status === 'verification-link-sent'"
                class="bg-emerald-500/10 border-emerald-500/20 text-emerald-400 text-left"
            >
                <CheckCircleIcon class="h-4 w-4 text-emerald-500" />
                <AlertDescription class="font-medium text-sm ml-2">
                    A new verification link has been sent!
                </AlertDescription>
            </Alert>

            <div class="w-full space-y-3 pt-2">
                <form @submit.prevent="submit" class="w-full">
                    <Button 
                        :disabled="form.processing"
                        type="submit" 
                        variant="outline"
                        class="w-full h-12 bg-emerald-600 hover:bg-emerald-500 text-white flex items-center justify-center gap-2 transition-colors rounded-xl font-semibold"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="23 4 23 10 17 10"></polyline>
                            <path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"></path>
                        </svg>
                        Resend Verification Link
                    </Button>
                </form>
            </div>

            <div class="mt-4">
                <Link
                    :href="route('logout')"
                    method="post"
                    as="button"
                    class="text-xs text-zinc-500 hover:text-emerald-500 font-medium hover:underline transition-colors"
                >
                    Log Out
                </Link>
            </div>
            
        </div>
    </AuthLayout>
</template>