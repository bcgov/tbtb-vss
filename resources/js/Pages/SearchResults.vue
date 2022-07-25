<template>
        <Head title="Search Cases" />

        <BreezeAuthenticatedLayout>
            <template #header>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    VSS - Verification Statistics System
                </h2>
            </template>

            <div class="mt-3">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-4 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    VSS Case Search
                                </div>
                                <div class="card-body">
                                    <CaseSearchBox />
                                </div>
                            </div>
                        </div>
                        <div class="col-md-8 mt-3">
                            <div class="card">
                                <div class="card-header">
                                    VSS Case Search Results
                                </div>
                                <div class="card-body">
                                    <table v-if="results != null" class="table">
                                        <thead>
                                            <tr>
                                                <th scope="col">SIN</th>
                                                <th scope="col">Name</th>
                                                <th scope="col">Institution</th>
                                                <th scope="col">Date Open/Reactivated</th>
                                                <th scope="col">Status</th>
                                                <th scope="col">Severity</th>
                                                <th scope="col">Auditor</th>
                                                <th scope="col">Investigator</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(row, i) in results">
                                                <th scope="row"><Link :href="route('case-funding.show', [row.id])">{{ row.sin }}</Link></th>
                                                <td>{{ row.first_name }} {{ row.last_name}}</td>
                                                <td><span v-if="row.institution != null">{{ row.institution.institution_name }}</span></td>
                                                <td>{{ row.open_date }}</td>
                                                <td>{{ row.incident_status }}</td>
                                                <td>{{ row.severity }}</td>
                                                <td>{{ row.auditor_user_id }}</td>
                                                <td>{{ row.investigator_user_id }}</td>
                                            </tr>

                                        </tbody>
                                    </table>
                                    <h1 v-else class="lead">No results</h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </BreezeAuthenticatedLayout>

</template>
<script setup>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head, Link } from '@inertiajs/inertia-vue3';
import CaseSearchBox from '@/Components/CaseSearch.vue';

defineProps({
    results: Object,
});
</script>
