<script setup>
import { ref } from 'vue';
import BreezeApplicationLogo from '@/Components/ApplicationLogo.vue';
import BreezeNavLink from '@/Components/NavLink.vue';
import BreezeResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import { Link } from '@inertiajs/inertia-vue3';

const showingNavigationDropdown = ref(false);

</script>
<style scoped>
nav.navbar{
    background-color: #003366;
    border: none;
    border-bottom: 2px solid #fcba19;
    z-index: 99;
}
</style>
<template>
    <nav class="navbar navbar-expand-lg navbar-dark shadow">
        <div class="container-fluid">
            <Link class="navbar-brand" :href="route('dashboard')">
                <BreezeApplicationLogo width="126" height="34" class="d-inline-block align-text-top me-3" />
                VSS - Verification Statistics System
            </Link>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav flex-row flex-wrap ms-md-auto" style="--bs-scroll-height: 100px;">
                    <li class="nav-item">
                        <BreezeNavLink class="nav-link" :href="route('dashboard')" :active="route().current('dashboard')">
                            Dashboard
                        </BreezeNavLink>
                    </li>
                    <li class="nav-item">
                        <BreezeNavLink class="nav-link" :href="route('cases.index')" :active="route().current('cases.*')">
                            Cases
                        </BreezeNavLink>
                    </li>
                    <li class="nav-item">
                        <BreezeNavLink class="nav-link" :href="route('reports')" :active="route().current('reports')">
                            Reports
                        </BreezeNavLink>
                    </li>
                    <li class="nav-item">
                        <BreezeNavLink class="nav-link" :href="route('maintenance.areas-of-audit.list')" :active="route().current('maintenance.*')">
                            Maintenance
                        </BreezeNavLink>
                    </li>
                    <li class="nav-item">
                        <BreezeNavLink class="nav-link" :href="route('archive')" :active="route().current('archive')">
                            Archive
                        </BreezeNavLink>
                    </li>
                    <li class="nav-item dropdown">
                        <BreezeNavLink class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            {{ $page.props.auth.user.user_id }}
                        </BreezeNavLink>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarScrollingDropdown">
                            <li class="dropdown-item px-4">
                                <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                            </li>
                            <li><hr class="dropdown-divider"></li>

                                <li class="dropdown-item mt-3 space-y-1">
                                    <div class="d-grid gap-2">
                                    <BreezeResponsiveNavLink class="text-left" :href="route('logout')" method="post" as="button">
                                        Log Out
                                    </BreezeResponsiveNavLink>
                                    </div>
                                </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
</template>
