<script setup>
import { Head, Link, useForm } from "@inertiajs/vue3";

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

    <div class="p-10 w-dvw h-full flex justify-between items-center">
        <div class="w-[40%] h-full flex justify-center items-center flex-col">
            <p v-if="status">{{ status }}</p>
            <form @submit.prevent="submit">
                <button :disabled="form.processing">
                    Resend Verification Email
                </button>
            </form>

            <Link
                :href="route('logout')"
                method="post"
                as="button"
                class="underline text-sm text-gray-600 hover:text-gray-900"
            >
                Log Out
            </Link>
        </div>
    </div>
</template>
