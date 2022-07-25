<template>
    <div class="card">
        <div class="card-header">
            <div>Areas of Audit</div>
        </div>
        <div class="card-body">
            <div>Areas</div>
        </div>
    </div>

</template>
<script>
import {computed} from "vue";
import { Head, Link, useForm } from '@inertiajs/inertia-vue3';
import BreezeInput from '@/Components/Input.vue';
import {Inertia} from "@inertiajs/inertia";

export default {
    name: 'MaintenanceAreas',
    components: {
        BreezeInput
    },
    props: {
        result: Object,
    },
    data() {
        return {
            noChanges: true,
            searchType: '',
            searchResults: '',
            updates: [],
            newRows: [],
            showSuccessMsg: false,
        }
    },
    methods: {
        deleteFund: function (index, row)
        {
            if(confirm("Are you sure you want to delete this Case Funding record?")){
                const form = useForm({});
                form.delete(route('case-funding.destroy', row.id), {
                    onSuccess: () => {
                        this.showSuccessAlert();
                    }
                });
            }
        },
        updateAllFunds: function ()
        {
            const form = useForm({
                old_rows: this.result.funds,
                new_rows: this.newRows
            });
            form.put(route('case-funding.update', this.result.id), {
                onSuccess: () => {
                    this.showSuccessAlert();
                }
            });
            // form.wasSuccessful();
        },
        showSuccessAlert: function ()
        {
            this.showSuccessMsg = true;
            let vm = this;
            setTimeout(function (){
                vm.showSuccessMsg = false;
                vm.noChanges = true;
                Inertia.get(route('case-funding.show', [vm.result.id]), {preserveState: true});

            }, 5000);
        },
        newFund: function(){
            this.newRows.push({
                'application_number': this.result.application_number,
                'funding_type': '',
                'fund_entry_date': this.now,
                'over_award': 0,
                'prevented_funding': 0
            });
            this.noChanges = false;
        },
        back: function()
        {
            window.history.back();
        },
        updateFunds: function (index)
        {
            if(this.updates.slice(index).length === 0){
                this.updates.push(index);
            }
            this.noChanges = false;
        },

        formatMoney: function (value, decimals = 2, separator = ','){
            // console.log(value);
            let result = value;
            if(value != null){
                result = parseFloat(value);
                result = result.toFixed(decimals);
                result = result.toString();
                if(separator) {
                    result = result.replace(/\B(?=(\d{3})+(?!\d))/g, separator);
                }
            }

            return result;
        },
        rowTotal: function (clmnA, clmnB){
            return parseFloat(clmnA) + parseFloat(clmnB);
        },
    },
    watch: {
        updated: function(newVal, oldVal){

        }
    },
    computed: {
        totalOverAward: function (){
            let total = 0;
            for(let i=0; i<this.result.funds.length; i++){
                total += parseFloat(this.result.funds[i].over_award);
            }
            return total.toLocaleString();
        },
        totalPrevented: function (){
            let total = 0;
            for(let i=0; i<this.result.funds.length; i++){
                total += parseFloat(this.result.funds[i].prevented_funding);
            }
            return total.toLocaleString();
        },
        totals: function (){
            let total = 0;
            for(let i=0; i<this.result.funds.length; i++){
                total += parseFloat(this.result.funds[i].over_award) + parseFloat(this.result.funds[i].prevented_funding);
            }
            return total.toLocaleString();
        },

    }
}
//
// defineProps({
//     : Object,
// });
// let totalOverAward = computed( () => {
//     let total = 0;
//     for(let i=0; i<result.funds.length; i++){
//         total += result.funds[i].over_award;
//     }
//     return total;
// }
// );
</script>
