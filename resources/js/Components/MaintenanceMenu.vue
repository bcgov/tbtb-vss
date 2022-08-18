<template>

    <div class="list-group">
        <Link :href="route('maintenance.area-of-audit.index')" class="list-group-item list-group-item-action" :class="page === 'area-of-audit' ? 'active' : ''">
            Areas of Audit
        </Link>
        <Link :href="route('maintenance.school.index')" class="list-group-item list-group-item-action" :class="page === 'school' ? 'active' : ''">Schools</Link>
        <a href="/maintenance/school" class="list-group-item list-group-item-action" :class="page === 'nature-of-offence' ? 'active' : ''">Nature of Offence</a>
        <a href="/maintenance/school" class="list-group-item list-group-item-action" :class="page === 'referral-sources' ? 'active' : ''">Referral Sources</a>
        <a href="/maintenance/school" class="list-group-item list-group-item-action" :class="page === 'sanction-types' ? 'active' : ''">Sanction Types</a>
        <a href="/maintenance/staff" class="list-group-item list-group-item-action" :class="(page === 'staff' || page === 'staff-edit') ? 'active' : ''">Staff</a>
    </div>
</template>
<script setup>
import { Link } from '@inertiajs/inertia-vue3';

defineProps({
    page: String,
});

</script>
