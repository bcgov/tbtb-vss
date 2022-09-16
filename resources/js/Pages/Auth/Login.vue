<script setup>
import BreezeButton from '@/Components/Button.vue';
import BreezeCheckbox from '@/Components/Checkbox.vue';
import BreezeGuestLayout from '@/Layouts/Guest.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';

defineProps({
    loginAttempt: Boolean,
    hasAccess: Boolean,
    status: String,
});


</script>
<style scoped>
.btn-bc-gov{
    background-image: none;
    background-color: #003366;
    border: none;
    border-radius: 4px;
    color: white;
    text-align: center;
    text-decoration: none;
    display: block;
    font-weight: 700;
    letter-spacing: 1px;
    cursor: pointer;
    max-width: 150px;
    margin-left: auto;
    margin-right: auto;
}
.btn-bc-gov:hover{
    background: #ededed !important;
    background-image: none !important;
    text-decoration: underline !important;
    opacity: 0.80 !important;
    color: #036 !important;
}
</style>
<template>
    <BreezeGuestLayout>
        <Head title="Log in" />


        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>


        <div class="">
            <p>Authenticate to <strong>VSS</strong> with:</p>
            <hr/>
            <a :href="route('app-login')" class="btn btn-bc-gov">IDIR</a>
        </div>
    </BreezeGuestLayout>
</template>
