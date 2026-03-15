<script setup lang="ts">
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { z } from "zod";
import { watch, computed } from "vue";
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
    CheckCircle2 as CheckCircleIcon,
} from "lucide-vue-next";
import AuthLayout from "@/components/layout/AuthLayout.vue";

const formSchema = toTypedSchema(
    z.object({
        email: z.string().email("Please enter a valid email address."),
    }),
);

const { handleSubmit, setErrors } = useForm({
    validationSchema: formSchema,
    initialValues: {
        email: "",
    },
});

const page = usePage();

const status = computed(() => page.props.status as string);

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
    router.post("/forgot-password", values);
});
</script>

<template>
    <Head title="Forgot Password" />

    <AuthLayout
        title="Secure Your Account"
        subtitle="Don't worry, it happens to the best of us."
        form-title="Reset Password"
        form-subtitle="We'll send a recovery link to your email."
        :steps="[
            'Request <br /> reset',
            'Check your <br /> email',
            'Set new <br /> password',
        ]"
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

            <Alert
                v-if="status"
                class="bg-emerald-500/10 border-emerald-500/20 text-emerald-400"
            >
                <CheckCircleIcon class="h-4 w-4 text-emerald-500" />
                <AlertDescription class="font-medium text-sm ml-2">
                    {{ status }}
                </AlertDescription>
            </Alert>

            <form class="space-y-5" @submit="submit">
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

                <Button
                    type="submit"
                    class="w-full h-11 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl transition-all font-medium mt-2"
                >
                    Send Reset Link
                </Button>

                <div class="text-center mt-6">
                    <p class="text-xs text-zinc-400">
                        Remember your password?
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
