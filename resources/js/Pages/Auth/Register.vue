<script setup lang="ts">
import { ref, onBeforeUnmount, watch } from "vue";
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { z } from "zod";
import { router, usePage, Head, Link } from "@inertiajs/vue3";

import { Button } from "@/components/ui/button";
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
    Camera as CameraIcon,
    Eye as EyeIcon,
    EyeOff as EyeOffIcon,
} from "lucide-vue-next";
import AuthLayout from "@/components/layout/AuthLayout.vue";

const SIZE = 5000000;
const TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

const previewUrl = ref<string | null>(null);
const showPassword = ref(false);
const showConfirmPassword = ref(false);

const formSchema = toTypedSchema(
    z
        .object({
            name: z.string().min(2, "Name must be at least 2 characters."),
            email: z.string().email("Please enter a valid email address."),
            password: z
                .string()
                .min(8, "Password must be at least 8 characters."),
            confirm_password: z.string(),
            profile: z
                .any()
                .refine(
                    (file) => file instanceof File,
                    "Profile photo is required.",
                )
                .refine((file) => file?.size <= SIZE, "Max size is 5MB.")
                .refine(
                    (file) => TYPES.includes(file?.type),
                    "Format not supported.",
                ),
        })
        .refine((data) => data.password === data.confirm_password, {
            message: "Passwords do not match.",
            path: ["confirm_password"],
        }),
);

const { handleSubmit, setFieldValue, setErrors } = useForm({
    validationSchema: formSchema,
    initialValues: {
        name: "",
        email: "",
        password: "",
        confirm_password: "",
        profile: undefined,
    },
});

const page = usePage();

watch(
    () => page.props.errors,
    (newErrors) => {
        if (newErrors && Object.keys(newErrors).length > 0)
            setErrors(newErrors);
    },
    { immediate: true },
);

const change = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    if (file) {
        setFieldValue("profile", file);
        if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
        previewUrl.value = URL.createObjectURL(file);
    }
};

onBeforeUnmount(() => {
    if (previewUrl.value) URL.revokeObjectURL(previewUrl.value);
});

const submit = handleSubmit((values) => {
    router.post("/register", values, { forceFormData: true });
});
</script>

<template>
    <Head title="Register" />

    <AuthLayout
        title="Get Started&#10;with Us"
        subtitle="Complete these easy steps to register your account."
        form-title="Create Account"
        form-subtitle="Follow the prompts below to set up your identity."
        :steps="[
            'Sign up your <br /> account',
            'Set up your <br /> workspace',
            'Set up your <br /> profile',
        ]"
        :current-step="0"
    >
        <div class="space-y-6 w-full max-w-lg mx-auto">
            <Alert
                v-if="
                    page.props.errors &&
                    Object.keys(page.props.errors).length > 0
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

            <form class="space-y-5" @submit="submit">
                <div class="border border-white/5 rounded-2xl p-5 space-y-5">
                    <div
                        class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-emerald-500"
                    >
                        <span
                            class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20"
                            >1</span
                        >
                        Your Identity
                    </div>

                    <div class="flex flex-col sm:flex-row gap-5">
                        <FormField name="profile">
                            <FormItem>
                                <FormControl>
                                    <div
                                        class="relative w-24 h-24 mx-auto sm:mx-0 group cursor-pointer border-2 border-dashed border-white/10 rounded-2xl bg-zinc-800 hover:bg-zinc-700 transition-colors flex flex-col items-center justify-center overflow-hidden"
                                    >
                                        <input
                                            type="file"
                                            accept="image/*"
                                            class="absolute inset-0 opacity-0 z-10 cursor-pointer"
                                            @change="change"
                                        />
                                        <img
                                            v-if="previewUrl"
                                            :src="previewUrl"
                                            class="absolute inset-0 w-full h-full object-cover"
                                        />
                                        <div
                                            v-else
                                            class="flex flex-col items-center text-zinc-500"
                                        >
                                            <CameraIcon class="w-6 h-6 mb-1" />
                                            <span
                                                class="text-[9px] font-bold uppercase tracking-tighter"
                                                >Avatar</span
                                            >
                                        </div>
                                    </div>
                                </FormControl>
                                <FormMessage
                                    class="text-[10px] text-center sm:text-left"
                                />
                            </FormItem>
                        </FormField>

                        <div class="flex-1 space-y-3">
                            <FormField v-slot="{ componentField }" name="name">
                                <FormItem>
                                    <FormLabel class="text-xs text-zinc-400"
                                        >Full Name</FormLabel
                                    >
                                    <FormControl>
                                        <Input
                                            placeholder="eg. John Francisco"
                                            v-bind="componentField"
                                            class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11"
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>

                            <FormField v-slot="{ componentField }" name="email">
                                <FormItem>
                                    <FormLabel class="text-xs text-zinc-400"
                                        >Email Address</FormLabel
                                    >
                                    <FormControl>
                                        <Input
                                            type="email"
                                            placeholder="eg. john@example.com"
                                            v-bind="componentField"
                                            class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11"
                                        />
                                    </FormControl>
                                    <FormMessage />
                                </FormItem>
                            </FormField>
                        </div>
                    </div>
                </div>

                <div class="border border-white/5 rounded-2xl p-5 space-y-4">
                    <div
                        class="flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-emerald-500"
                    >
                        <span
                            class="flex h-5 w-5 items-center justify-center rounded-full bg-emerald-500/20"
                            >2</span
                        >
                        Security
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <FormField v-slot="{ componentField }" name="password">
                            <FormItem>
                                <FormLabel class="text-xs text-zinc-400"
                                    >Password</FormLabel
                                >
                                <FormControl>
                                    <div class="relative">
                                        <Input
                                            :type="
                                                showPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            placeholder="••••••••"
                                            v-bind="componentField"
                                            class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11 pr-10"
                                        />
                                        <button
                                            type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors focus:outline-none"
                                            @click="
                                                showPassword = !showPassword
                                            "
                                        >
                                            <EyeIcon
                                                v-if="!showPassword"
                                                class="h-4 w-4"
                                            />
                                            <EyeOffIcon
                                                v-else
                                                class="h-4 w-4"
                                            />
                                        </button>
                                    </div>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>

                        <FormField
                            v-slot="{ componentField }"
                            name="confirm_password"
                        >
                            <FormItem>
                                <FormLabel class="text-xs text-zinc-400"
                                    >Confirm Password</FormLabel
                                >
                                <FormControl>
                                    <div class="relative">
                                        <Input
                                            :type="
                                                showConfirmPassword
                                                    ? 'text'
                                                    : 'password'
                                            "
                                            placeholder="••••••••"
                                            v-bind="componentField"
                                            class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11 pr-10"
                                        />
                                        <button
                                            type="button"
                                            class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors focus:outline-none"
                                            @click="
                                                showConfirmPassword =
                                                    !showConfirmPassword
                                            "
                                        >
                                            <EyeIcon
                                                v-if="!showConfirmPassword"
                                                class="h-4 w-4"
                                            />
                                            <EyeOffIcon
                                                v-else
                                                class="h-4 w-4"
                                            />
                                        </button>
                                    </div>
                                </FormControl>
                                <FormMessage />
                            </FormItem>
                        </FormField>
                    </div>
                </div>

                <Button
                    type="submit"
                    class="w-full h-11 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all font-medium mt-2"
                >
                    Create Account
                </Button>

                <div class="text-center mt-6">
                    <p class="text-xs text-zinc-400">
                        Already have an account?
                        <Link
                            href="/login"
                            class="text-emerald-500 hover:text-emerald-400 font-medium hover:underline transition-colors"
                        >
                            Login
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>
