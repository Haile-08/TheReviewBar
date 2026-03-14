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
import { computed } from "vue";

const formSchema = toTypedSchema(
    z.object({
        email: z.string().email("Please enter a correct email address."),
    }),
);

const { handleSubmit, setErrors } = useForm({
    validationSchema: formSchema,
    initialValues: {
        email: "",
    },
});

const page = usePage();

const status = computed(() => page.props.status);

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
    router.post("/forgot-password", values);
});

console.log(page.props.errors);
</script>

<template>
    <Head title="Forgot Password" />
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
                    <AlertTitle>Reset Password Failed</AlertTitle>
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
            <p v-if="status">{{ status }}</p>
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
                <Button type="submit">reset</Button>
            </form>
        </div>
    </div>
</template>
