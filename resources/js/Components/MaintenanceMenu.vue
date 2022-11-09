<template>

    <div class="list-group">
        <Link :href="route('maintenance.area-of-audit.index')" class="list-group-item list-group-item-action" :class="page === 'area-of-audit' ? 'active' : ''">Areas of Audit</Link>
        <Link :href="route('maintenance.school.index')" class="list-group-item list-group-item-action" :class="page === 'school' ? 'active' : ''">Schools</Link>
        <Link :href="route('maintenance.nature-offence.index')" class="list-group-item list-group-item-action" :class="page === 'nature-offence' ? 'active' : ''">Nature of Offence</Link>
        <Link :href="route('maintenance.referral-source.index')" class="list-group-item list-group-item-action" :class="page === 'referral-source' ? 'active' : ''">Referral Sources</Link>
        <Link :href="route('maintenance.sanction-type.index')" class="list-group-item list-group-item-action" :class="page === 'sanction-type' ? 'active' : ''">Sanction Types</Link>
        <a href="/maintenance/staff" class="list-group-item list-group-item-action" :class="(page === 'staff' || page === 'staff-edit') ? 'active' : ''">Staff</a>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';
defineProps({
    page: String,
});

</script>
