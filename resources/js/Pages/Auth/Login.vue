<script setup lang="ts">
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { z } from "zod";
import { watch } from "vue";
import { usePage } from "@inertiajs/vue3";

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
import { Alert, AlertTitle, AlertDescription } from "@/components/ui/alert";
import { AlertCircle as AlertCircleIcon } from "lucide-vue-next";

const formSchema = toTypedSchema(
    z.object({
        email: z.string().email("Please enter a correct email address."),
        password: z
            .string()
            .min(8, "Password too short at least 2 characters."),
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
    console.log(values);
});
</script>

<template>
    <div class="p-10 w-dvw h-full flex justify-between items-center">
        <div class="w-[40%] h-full flex justify-center items-center flex-col">
            <div
                v-if="
                    page.props.errors &&
                    Object.keys(page.props.errors).length > 0
                "
                class="mb-6"
            >
                <Alert variant="destructive">
                    <AlertCircleIcon class="h-4 w-4" />
                    <AlertTitle>Login Failed</AlertTitle>
                    <AlertDescription>
                        <ul class="list-disc list-inside">
                            <li
                                v-for="(error, key) in page.props.errors"
                                :key="key"
                            >
                                {{ error }}
                            </li>
                        </ul>
                    </AlertDescription>
                </Alert>
            </div>
            <form class="w-2/3 space-y-6" @submit="submit">
                <FormField v-slot="{ componentField }" name="email">
                    <FormItem>
                        <FormLabel>Email</FormLabel>
                        <FormControl>
                            <Input
                                type="email"
                                placeholder="m@example.com"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="password">
                    <FormItem>
                        <FormLabel>Password</FormLabel>
                        <FormControl>
                            <Input
                                type="password"
                                placeholder="••••••••"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>
                <FormField v-slot="{ value, handleChange }" name="remember">
                    <FormItem
                        class="flex flex-row items-start space-x-3 space-y-0 rounded-md border p-4"
                    >
                        <FormControl>
                            <Checkbox
                                :checked="value"
                                @update:checked="handleChange"
                            />
                        </FormControl>
                        <div class="space-y-1 leading-none">
                            <FormLabel>Remember me</FormLabel>
                        </div>
                    </FormItem>
                </FormField>
                <a :href="route('google')">
                    <Button type="button">Google</Button>
                </a>
                <Button type="submit">Register</Button>
            </form>
        </div>
    </div>
</template>
