<template>
    <Head title="Dashboard" />


    <BreezeAuthenticatedLayout>

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
                            ds
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>



</template>
<script>
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import { Head } from '@inertiajs/inertia-vue3';
import CaseSearchBox from '@/Components/CaseSearch.vue';
import axios from 'axios';

export default {
    name: 'Dashboard',
    components: {
        BreezeAuthenticatedLayout, CaseSearchBox, Head
    },
    props: [],
    data() {
        return {
            searchType: '',
            searchResults: '',
        }
    },
    methods: {
        search: function (data){
            // console.log(data);
            // this.$router.push('/cases?searchData=' + JSON.stringify(data));
        }
    }
}
</script>
