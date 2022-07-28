<template>

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('bySin')" class="nav-link active" id="sin-tab" data-bs-toggle="tab" data-bs-target="#sin-tab-pane" type="button" role="tab" aria-controls="sin-tab-pane" aria-selected="true">SIN</button>
        </li>
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byName')" class="nav-link" id="name-tab" data-bs-toggle="tab" data-bs-target="#name-tab-pane" type="button" role="tab" aria-controls="name-tab-pane" aria-selected="false">Name</button>
        </li>
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byActiveUser')" class="nav-link" id="active-user-tab" data-bs-toggle="tab" data-bs-target="#active-user-tab-pane" type="button" role="tab" aria-controls="active-user-tab-pane" aria-selected="false">Active User</button>
        </li>
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byCancelledUser')" class="nav-link" id="cancelled-user-tab" data-bs-toggle="tab" data-bs-target="#cancelled-user-tab-pane" type="button" role="tab" aria-controls="cancelled-user-tab-pane" aria-selected="false">Cancelled User</button>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="sin-tab-pane" role="tabpanel" aria-labelledby="sin-tab" tabindex="0">
            <form @submit.prevent="sinFormSubmit" class="m-3">
                <div class="row g-3 align-items-center">
                    <div class="col-auto">
                        <BreezeLabel for="inputSin" value="SIN" />
                    </div>
                    <div class="col-auto">
                        <BreezeInput type="number" id="inputSin" class="form-control" maxlength="9" v-model="sinForm.inputSin" required />
                    </div>
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': sinForm.processing }" :disabled="sinForm.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="name-tab-pane" role="tabpanel" aria-labelledby="name-tab" tabindex="1">
            <form @submit.prevent="nameFormSubmit" class="m-3">
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="inputLastName" value="Last Name" />
                    <div class="col-auto">
                        <BreezeInput type="text" id="inputLastName" class="form-control" v-model="nameForm.inputLastName" />
                    </div>
                </div>
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="inputFirstName" value="First Name" />
                    <div class="col-auto">
                        <BreezeInput type="text" id="inputFirstName" class="form-control" v-model="nameForm.inputFirstName" />
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': nameForm.processing }" :disabled="nameForm.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="active-user-tab-pane" role="tabpanel" aria-labelledby="active-user-tab" tabindex="2">
            <form @submit.prevent="activeUsersFormSubmit" class="m-3">
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="selectActiveUser" value="User" />
                    <div class="col-auto">
                        <BreezeSelect id="selectActiveUser" class="form-control" v-model="activeUsersForm.selectActiveUser">
                            <template v-for="(u, i) in activeUsers">
                                <option :value="u.user_id">{{ u.user_id }} | {{ u.first_name }} {{ u.last_name}}</option>
                            </template>
                        </BreezeSelect>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': activeUsersFormSubmit.processing }" :disabled="activeUsersFormSubmit.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>
        <div class="tab-pane fade" id="cancelled-user-tab-pane" role="tabpanel" aria-labelledby="cancelled-user-tab" tabindex="3">
            <form @submit.prevent="cancelledUsersFormSubmit" class="m-3">
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="selectCancelledUser" value="User" />
                    <div class="col-auto">
                        <BreezeSelect id="selectCancelledUser" class="form-control" v-model="cancelledUsersForm.selectCancelledUser">
                            <template v-for="(u, i) in cancelledUsers">
                                <option :value="u.user_id">{{ u.user_id }} | {{ u.first_name }} {{ u.last_name}}</option>
                            </template>
                        </BreezeSelect>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary shadow-none" :class="{ 'opacity-25': cancelledUsersFormSubmit.processing }" :disabled="cancelledUsersFormSubmit.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>
<script>
import { defineComponent, h, PropType } from 'vue'

import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    CategoryScale,
    Plugin
} from 'chart.js'

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    LinearScale,
    PointElement,
    CategoryScale
)

export default defineComponent({
    name: 'ReportChart',
    components: {
        Line
    },
    props: {
        results: Object,
        title: String,
        bg: String,
        chartId: {
            type: String,
            default: 'line-chart'
        },
        width: {
            type: Number,
            default: 400
        },
        height: {
            type: Number,
            default: 400
        },
        cssClasses: {
            default: '',
            type: String
        },
        styles: {
            type: Object,
            default: () => {}
        },
        plugins: {
            type: Array,
            default: () => []
        }
    },
    setup(props) {
        let totals = [];
        for(let i=0; i<Object.values(props.results).length; i++){
            totals.push(Object.values(props.results)[i].TOTAL);
        }
        const chartData = {
            labels: Object.keys(props.results),
            datasets: [
                {
                    label: props.title,
                    backgroundColor: props.bg,
                    data: totals
                }
            ]
        }

        const chartOptions = {
            responsive: true,
            maintainAspectRatio: false
        }

        return () =>
            h(Line, {
                chartData,
                chartOptions,
                chartId: props.chartId,
                width: props.width,
                height: props.height,
                cssClasses: props.cssClasses,
                styles: props.styles,
                plugins: props.plugins
            })
    }
});



</script>
