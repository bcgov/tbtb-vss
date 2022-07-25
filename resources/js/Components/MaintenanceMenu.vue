<template>

    <div class="list-group">
        <Link :href="route('maintenance')" class="list-group-item list-group-item-action" :class="page === 'areas-of-audit' ? 'active' : ''">
            Areas of Audit
        </Link>
        <Link :href="route('maintenance', ['schools'])" class="list-group-item list-group-item-action" :class="page === 'schools' ? 'active' : ''">Schools</Link>
        <a href="/maintenance/schools" class="list-group-item list-group-item-action" :class="page === 'nature-of-offence' ? 'active' : ''">Nature of Offence</a>
        <a href="/maintenance/schools" class="list-group-item list-group-item-action" :class="page === 'referral-sources' ? 'active' : ''">Referral Sources</a>
        <a href="/maintenance/schools" class="list-group-item list-group-item-action" :class="page === 'sanction-types' ? 'active' : ''">Sanction Types</a>
        <a href="/maintenance/schools" class="list-group-item list-group-item-action" :class="page === 'staff' ? 'active' : ''">Staff</a>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    page: String,
});

</script>
