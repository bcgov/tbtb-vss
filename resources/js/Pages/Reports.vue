<style scoped>
[type='checkbox']:checked, [type='radio']:checked {
    background-size: initial;
}
</style>
<template>
    <Head title="Reports" />

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
                                VSS Area and Program Report
                            </div>
                            <div class="card-body">
                                <form @submit.prevent="reportFormSubmit" class="m-3">
                                    <div class="row mb-3">
                                        <div class="col-md-6">
                                            <BreezeLabel class="form-label" for="inputStartDate" value="Start Date" />
                                            <BreezeInput type="date" id="inputStartDate" class="form-control" v-model="reportForm.inputStartDate" />
                                        </div>
                                        <div class="col-md-6">
                                            <BreezeLabel class="form-label" for="inputEndDate" value="End Date" />
                                            <BreezeInput type="date" id="inputEndDate" class="form-control" v-model="reportForm.inputEndDate" />
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-auto">
                                            <div class="form-check">
                                                <BreezeInput @click="changeType('overaward')" type="radio" name="radioReport" id="radioReport1" class="form-check-input" value="overaward" />
                                                <BreezeLabel class="col-auto form-check-label" for="radioReport1" value="Over Award Report" />
                                            </div>
                                            <div class="form-check">
                                                <BreezeInput @click="changeType('prevented')" type="radio" name="radioReport" id="radioReport2" class="form-check-input" value="prevented" />
                                                <BreezeLabel class="col-auto form-check-label" for="radioReport2" value="Prevented Report" />
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-auto">
                                            <BreezeButton type="submit" class="btn btn-primary" :class="{ 'opacity-25': reportForm.processing }" :disabled="reportForm.processing">
                                                Search
                                            </BreezeButton>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-8 mt-3">
                        <div class="card mb-3">
                            <div class="card-header">
                                VSS Reports
                            </div>
                            <div class="card-body">
                                <div v-if="results != null" class="table-responsive pb-3">
                                    <ReportChart :results="results.pre" title="Pre Audit Report" bg="#0d6efd" />
                                    <hr/>
                                    <ReportChart :results="results.post" title="Post Audit Report" bg="#198754" />
                                    <hr/>
                                    <ReportChart :results="results.total" title="Totals Report" bg="#0dcaf0" />
                                </div>
                                <h1 v-else class="lead">No results</h1>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>

</template>
<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import {Head, Link, useForm} from '@inertiajs/inertia-vue3';
import BreezeInput from "@/Components/Input";
import BreezeSelect from "@/Components/Select";
import BreezeLabel from "@/Components/Label";
import BreezePagination from "@/Components/Pagination";
import BreezeButton from "@/Components/Button";
import ReportChart from "@/Components/ReportChart";

export default {
    name: 'Reports',
    components: {
        BreezeAuthenticatedLayout, Head, Link, BreezeInput, BreezeSelect, BreezeLabel, BreezePagination, BreezeButton, ReportChart
    },
    props: {
        results: Object
    },
    data() {
        return {
            reportForm: {
                inputEndDate: '',
                inputStartDate: '',
                type: 'prevented',
            },
            //results: null,
            showSuccessMsg: false,

        }
    },
    methods: {
        changeType: function (type){
            this.reportForm.type = type;
        },
        reportFormSubmit: function (){
            if(this.reportForm.inputEndDate === '' || this.reportForm.inputStartDate === '') return false;

            let activeUsersForm = useForm(this.reportForm);
            activeUsersForm.post(route('reports-search'), {
                onSuccess: (response) => {

                    //this.results = response.data.results;

                },
                preserveState: false
            });

        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
                vm.noChanges = true;
            }, 5000);
        },
    },
    watch: {
    },
    computed: {
    },
    mounted() {
    }
}
</script>
