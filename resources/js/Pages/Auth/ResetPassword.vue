<script setup lang="ts">
import { ref, watch } from "vue";
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
    Eye as EyeIcon,
    EyeOff as EyeOffIcon,
} from "lucide-vue-next";
import AuthLayout from "@/components/layout/AuthLayout.vue";

const props = defineProps<{
    email: string;
    token: string;
}>();

const showPassword = ref(false);
const showConfirmPassword = ref(false);

const formSchema = toTypedSchema(
    z
        .object({
            token: z.string(),
            email: z.string().email("Please enter a valid email address."),
            password: z
                .string()
                .min(8, "Password must be at least 8 characters."),
            password_confirmation: z.string(),
        })
        .refine((data) => data.password === data.password_confirmation, {
            message: "Passwords do not match.",
            path: ["password_confirmation"],
        }),
);

const { handleSubmit, setErrors } = useForm({
    validationSchema: formSchema,
    initialValues: {
        token: props.token,
        email: props.email,
        password: "",
        password_confirmation: "",
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
    router.post("/reset-password", values);
});
</script>

<template>
    <Head title="Reset Password" />
    <AuthLayout
        title="Secure Your Account"
        subtitle="Don't worry, it happens to the best of us."
        form-title="Reset Password"
        form-subtitle="Input your new password to reset your account access."
        :steps="[
            'Request <br /> reset',
            'Check your <br /> email',
            'Set new <br /> password',
        ]"
        :current-step="2"
    >
        <div class="space-y-6 w-full max-w-sm mx-auto">
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
                    <p class="mb-2 font-medium">Please fix the errors below:</p>
                    <ul class="list-disc list-inside space-y-1 text-sm">
                        <li
                            v-for="(error, key) in page.props.errors"
                            :key="key"
                        >
                            {{ error }}
                        </li>
                    </ul>
                </AlertDescription>
            </Alert>

            <form class="space-y-4" @submit="submit">
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
                                class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11 text-zinc-400"
                                readonly
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="password">
                    <FormItem>
                        <FormLabel class="text-xs text-zinc-400"
                            >New Password</FormLabel
                        >
                        <FormControl>
                            <div class="relative">
                                <Input
                                    :type="showPassword ? 'text' : 'password'"
                                    placeholder="••••••••"
                                    v-bind="componentField"
                                    class="bg-zinc-800 border-transparent focus:ring-emerald-500 h-11 pr-10"
                                />
                                <button
                                    type="button"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-zinc-500 hover:text-zinc-300 transition-colors focus:outline-none"
                                    @click="showPassword = !showPassword"
                                >
                                    <EyeIcon
                                        v-if="!showPassword"
                                        class="h-4 w-4"
                                    />
                                    <EyeOffIcon v-else class="h-4 w-4" />
                                </button>
                            </div>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField
                    v-slot="{ componentField }"
                    name="password_confirmation"
                >
                    <FormItem>
                        <FormLabel class="text-xs text-zinc-400"
                            >Confirm New Password</FormLabel
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
                                    <EyeOffIcon v-else class="h-4 w-4" />
                                </button>
                            </div>
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <Button
                    type="submit"
                    class="w-full h-11 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all font-medium mt-6"
                >
                    Reset Password
                </Button>

                <div class="text-center mt-6">
                    <p class="text-xs text-zinc-400">
                        Changed your mind?
                        <Link
                            href="/login"
                            class="text-emerald-500 hover:text-emerald-400 font-medium hover:underline transition-colors"
                        >
                            Back to login
                        </Link>
                    </p>
                </div>
            </form>
        </div>
    </AuthLayout>
</template>
