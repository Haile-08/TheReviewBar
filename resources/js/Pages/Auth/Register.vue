<script setup lang="ts">
import { toTypedSchema } from "@vee-validate/zod";
import { useForm } from "vee-validate";
import { z } from "zod";
import { watch } from "vue";
import { router } from "@inertiajs/vue3";
import { usePage } from "@inertiajs/vue3";

import { Button } from "@/components/ui/button";
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

const SIZE = 5000000;
const TYPES = ["image/jpeg", "image/jpg", "image/png", "image/webp"];

const formSchema = toTypedSchema(
    z
        .object({
            name: z.string().min(2, "Name too short at least 2 characters."),
            email: z.string().email("Please enter a correct email address."),
            password: z
                .string()
                .min(8, "Password too short at least 2 characters."),
            confirm_password: z.string(),
            profile: z
                .any()
                .refine(
                    (file) => file instanceof File,
                    "Profile photo is required.",
                )
                .refine(
                    (file) => file?.size <= SIZE,
                    `Profile photo to to large at least 5MB.`,
                )
                .refine(
                    (file) => TYPES.includes(file?.type),
                    "Profile photo format not supported (.jpg, .jpeg, .png and .webp).",
                ),
        })
        .refine((data) => data.password === data.confirm_password, {
            message: "Passwords does not match make sure it matchs.",
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

const change = (e: Event) => {
    const target = e.target as HTMLInputElement;
    const file = target.files?.[0];
    setFieldValue("profile", file);
};

const submit = handleSubmit((values) => {
    console.log(values);
    router.post("/register", values);
});
</script>

<template>
    <div class="p-10 w-dvw h-full flex justify-between items-center">
        <div class="w-[40%] h-full flex justify-center items-center">
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
                <FormField name="profile">
                    <FormItem>
                        <FormLabel>Profile Picture</FormLabel>
                        <FormControl>
                            <Input
                                type="file"
                                accept="image/*"
                                @change="change"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

                <FormField v-slot="{ componentField }" name="name">
                    <FormItem>
                        <FormLabel>Name</FormLabel>
                        <FormControl>
                            <Input
                                type="text"
                                placeholder="shadcn"
                                v-bind="componentField"
                            />
                        </FormControl>
                        <FormMessage />
                    </FormItem>
                </FormField>

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

                <FormField v-slot="{ componentField }" name="confirm_password">
                    <FormItem>
                        <FormLabel>Confirm Password</FormLabel>
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

                <Button type="submit">Register</Button>
            </form>
        </div>
    </div>
</template>
