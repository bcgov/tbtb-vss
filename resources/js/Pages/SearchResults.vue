<template>

</template>
<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import CaseSearchBox from '@/Components/CaseSearch.vue';
import CasesHeader from '@/Components/CasesHeader.vue';
import BreezePagination from '@/Components/Pagination.vue';

defineProps({
    results: Object,
});
</script>
