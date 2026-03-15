<script setup lang="ts">
import { ref, watch } from "vue";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { z } from "zod";
import { router, Head, Link, usePage } from "@inertiajs/vue3";

import { Button } from "@/components/ui/button";
import { Checkbox } from "@/components/ui/checkbox";
import {
    FormControl,
    FormField,
    FormItem,
    FormLabel,
    FormMessage,
} from "@/components/ui/form";
import { Input } from "@/components/ui/input";
import { Alert, AlertDescription } from "@/components/ui/alert";
import {
    AlertCircle as AlertCircleIcon,
    CheckCircle2 as CheckCircleIcon,
    Eye as EyeIcon,
    EyeOff as EyeOffIcon,
} from "lucide-vue-next";
import AuthLayout from "@/components/layout/AuthLayout.vue";

defineProps({
    status: String,
});

const showPassword = ref(false);

const formSchema = toTypedSchema(
    z.object({
        email: z.string().email("Please enter a valid email address."),
        password: z.string().min(8, "Password must be at least 8 characters."),
        remember: z.boolean().default(false),
    }),
);

const { handleSubmit, setErrors } = useForm({
    validationSchema: formSchema,
    initialValues: {
        email: "",
        password: "",
        remember: false,
    },
});

const page = usePage();

watch(
    () => page.props.errors,
    (newErrors) => {
        if (newErrors && Object.keys(newErrors).length > 0) {
            setErrors(newErrors);
        }
    },
    { immediate: true },
);

const submit = handleSubmit((values) => {
    router.post("/login", values);
});
</script>

<template>
    <Head title="Login" />
    <AuthLayout
        title="Welcome Back"
        subtitle="Log in to continue where you left off."
        form-title="Sign In"
        form-subtitle="Enter your credentials to access your account."
        :steps="[
            'Enter <br /> credentials',
            'Verify <br /> identity',
            'Access <br /> dashboard',
        ]"
    >
        <div class="space-y-6 w-full max-w-sm mx-auto">
            
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
                v-if="page.props.errors && Object.keys(page.props.errors).length > 0"
                variant="destructive"
                class="bg-red-500/10 border-red-500/20 text-red-400"
            >
                <AlertCircleIcon class="h-4 w-4" />
                <AlertDescription>
                    <p class="mb-2 font-medium">
                        Please fix the errors below to continue:
                    </p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        <li v-for="(errorMessage, field) in page.props.errors" :key="field">
                            {{ errorMessage }}
                        </li>
                    </ul>
                </AlertDescription>
            </Alert>

            <a :href="route('google')" class="w-full block">
                <Button
                    type="button"
                    variant="outline"
                    class="w-full bg-zinc-900/50 border border-white/5 text-white hover:bg-zinc-800 hover:text-white h-11 flex items-center justify-center gap-3 transition-colors rounded-xl font-medium"
                >
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path
                            d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"
                            fill="#4285F4"
                        />
                        <path
                            d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"
                            fill="#34A853"
                        />
                        <path
                            d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"
                            fill="#FBBC05"
                        />
                        <path
                            d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"
                            fill="#EA4335"
                        />
                    </svg>
                    Continue with Google
                </Button>
            </a>

            <div class="relative flex items-center py-1">
                <div class="flex-grow border-t border-white/10"></div>
                <span class="flex-shrink-0 mx-4 text-zinc-500 text-[10px] uppercase tracking-widest font-bold">
                    OR
                </span>
                <div class="flex-grow border-t border-white/10"></div>
            </div>

            <form class="space-y-4" @submit="submit">
                <FormField v-slot="{ componentField }" name="email">
                    <FormItem>
                        <FormLabel class="text-xs text-zinc-400">Email Address</FormLabel>
                        <FormControl>
                            <Input
                                type="email"
                                placeholder="eg. user@example.com"
                                class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="password">
                    <FormItem>
                        <FormLabel class="text-xs text-zinc-400">Password</FormLabel>
                        <FormControl>
                            <div class="relative">
                                <Input
                                    :type="showPassword ? 'text' : 'password'"
                                    placeholder="••••••••"
                                    class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11 pr-10"
                                    v-bind="componentField"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors focus:outline-none"
                                    @click="showPassword = !showPassword"
                                >
                                    <EyeIcon v-if="!showPassword" class="h-4 w-4" />
                                    <EyeOffIcon v-else class="h-4 w-4" />
                                </button>
                            </div>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <div class="flex items-center justify-between pt-1 pb-2">
                    <FormField v-slot="{ value, handleChange }" name="remember">
                        <FormItem class="flex flex-row items-center space-x-2 space-y-0">
                            <FormControl>
                                <Checkbox
                                    :checked="value"
                                    class="border-zinc-600 rounded bg-zinc-800 data-[state=checked]:bg-emerald-500 data-[state=checked]:border-emerald-500 w-4 h-4"
                                    @update:checked="handleChange"
                                />
                            </FormControl>
                            <FormLabel class="text-zinc-400 text-xs font-normal cursor-pointer mt-0 leading-none">
                                Remember me
                            </FormLabel>
                        </FormItem>
                    </FormField>

                    <Link
                        :href="route('password.request')"
                        class="text-emerald-500 text-xs font-medium hover:text-emerald-400 transition-colors hover:underline"
                    >
                        Forgot password?
                    </Link>
                </div>

                <Button
                    type="submit"
                    class="w-full h-11 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all font-medium mt-2"
                >
                    Sign In
                </Button>

                <div class="text-center mt-6">
                    <p class="text-xs text-zinc-400">
                        Don't have an account?
                        <Link
                            href="/register"
                            class="text-emerald-500 hover:text-emerald-400 font-medium hover:underline transition-colors"
                        >
                            Register
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>