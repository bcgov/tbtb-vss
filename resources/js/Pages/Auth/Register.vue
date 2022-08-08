<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeSelect from '@/Components/Select.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

const form = useForm({
    user_id: '',
    first_name: '',
    last_name: '',
    start_date: '',
    end_date: '',
    access_type: '',
    email: '',
    password: '',
    password_confirmation: '',
    access_code: '',
    terms: false,
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <BreezeGuestLayout>
        <Head title="Register" />

        <BreezeValidationErrors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <BreezeLabel for="user_id" value="User ID" />
                <BreezeInput id="user_id" type="text" class="mt-1 block w-full" v-model="form.user_id" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="first_name" value="First Name" />
                <BreezeInput id="first_name" type="text" class="mt-1 block w-full" v-model="form.first_name" required autofocus autocomplete="first_name" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="last_name" value="Last Name" />
                <BreezeInput id="last_name" type="text" class="mt-1 block w-full" v-model="form.last_name" required autocomplete="last_name" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="email" value="Email" />
                <BreezeInput id="email" type="email" class="mt-1 block w-full" v-model="form.email" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="start_date" value="Start Date" />
                <BreezeInput id="start_date" type="date" class="mt-1 block w-full" placeholder="yyyy-mm-dd" aria-placeholder="yyyy-mm-dd" v-model="form.start_date" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="access_type" value="Access Type" />
                <BreezeSelect id="access_type" type="text" class="mt-1 block w-full" v-model="form.access_type" required>
                    <option value="A">Admin</option>
                    <option value="U">User</option>
                </BreezeSelect>
            </div>

            <div class="mt-4">
                <BreezeLabel for="access_code" value="Access Code" />
                <BreezeInput id="access_code" type="text" class="mt-1 block w-full" v-model="form.access_code" required />
            </div>

            <div class="mt-4">
                <BreezeLabel for="password" value="Password" />
                <BreezeInput id="password" type="password" class="mt-1 block w-full" v-model="form.password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <BreezeLabel for="password_confirmation" value="Confirm Password" />
                <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full" v-model="form.password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-sm text-gray-600 hover:text-gray-900">
                    Already registered?
                </Link>

                <BreezeButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Register
                </BreezeButton>
            </div>
        </form>
    </BreezeGuestLayout>
</template>
