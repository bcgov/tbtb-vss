<script setup>
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/inertia-vue3';
</script>
<style scoped>
.bg-bc-gov{
    background: none;
    background-color: #036;
    font-family: 'BCSans', 'Noto Sans', Verdana, Arial, sans-serif;
    color: #036;
}
</style>
<template>
    <div class="min-h-screen text-center flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-bc-gov">
        <h2 class="text-center text-white">Ministry of Advanced Education and Skills Training</h2>

        <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
            <div class="inline-flex mb-5">
                <Link href="/">
                    <BreezeApplicationLogo class="w-32 h-32 inline-flex" />
                </Link>
            </div>

            <slot />
        </div>
    </div>
</template>
