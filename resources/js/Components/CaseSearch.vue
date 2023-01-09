<template>
<!--    <Link :href="href" :class="classes">-->
<!--        <slot />-->
<!--    </Link>-->

    <ul class="nav nav-tabs" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('bySin')" class="nav-link active" id="sin-tab" data-bs-toggle="tab" data-bs-target="#sin-tab-pane" type="button" role="tab" aria-controls="sin-tab-pane" aria-selected="true">SIN</button>
        </li>
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byName')" class="nav-link" id="name-tab" data-bs-toggle="tab" data-bs-target="#name-tab-pane" type="button" role="tab" aria-controls="name-tab-pane" aria-selected="false">Name</button>
        </li>
        <li class="nav-item" role="presentation">
            <button @click="switchSearchTerm('byStatus')" class="nav-link" id="status-tab" data-bs-toggle="tab" data-bs-target="#status-tab-pane" type="button" role="tab" aria-controls="status-tab-pane" aria-selected="false">Status</button>
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
                        <BreezeInput type="number" id="inputSin" class="form-control" maxlength="9" v-model="sinForm.filter_sin" required />
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
                        <BreezeInput type="text" id="inputLastName" class="form-control" v-model="nameForm.filter_lname" />
                    </div>
                </div>
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="inputFirstName" value="First Name" />
                    <div class="col-auto">
                        <BreezeInput type="text" id="inputFirstName" class="form-control" v-model="nameForm.filter_fname" />
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

        <div class="tab-pane fade" id="status-tab-pane" role="tabpanel" aria-labelledby="name-tab" tabindex="2">
            <form @submit.prevent="statusFormSubmit" class="m-3">
                <div class="row mb-3">
                    <div class="col-auto">
                        <BreezeLabel class="col-auto col-form-label" for="selectCaseStatus" value="Case Status" />
                    </div>
                    <div class="col-auto">
                        <BreezeSelect id="selectCaseStatus" class="form-control" v-model="statusForm.filter_status">
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                            <option value="Re-activated">Re-activated</option>
                        </BreezeSelect>
                    </div>
                    <div class="col-auto">
                        <BreezeButton class="btn btn-primary" :class="{ 'opacity-25': statusForm.processing }" :disabled="statusForm.processing">
                            Search
                        </BreezeButton>
                    </div>
                </div>
            </form>
        </div>

        <div class="tab-pane fade" id="active-user-tab-pane" role="tabpanel" aria-labelledby="active-user-tab" tabindex="3">
            <form @submit.prevent="activeUsersFormSubmit" class="m-3">
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="selectActiveUser" value="User" />
                    <div class="col-auto">
                        <BreezeSelect id="selectActiveUser" class="form-control" v-model="activeUsersForm.filter_user">
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

        <div class="tab-pane fade" id="cancelled-user-tab-pane" role="tabpanel" aria-labelledby="cancelled-user-tab" tabindex="4">
            <form @submit.prevent="cancelledUsersFormSubmit" class="m-3">
                <div class="row mb-3">
                    <BreezeLabel class="col-auto col-form-label" for="selectCancelledUser" value="User" />
                    <div class="col-auto">
                        <BreezeSelect id="selectCancelledUser" class="form-control" v-model="cancelledUsersForm.filter_user">
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
<script setup>
import BreezeInput from '@/Components/Input.vue';
import BreezeSelect from '@/Components/Select.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeButton from '@/Components/Button.vue';

import { ref, onMounted } from 'vue'
import { useForm } from '@inertiajs/inertia-vue3'
import axios from "axios";

let searchType = ref('bySin');


// const activeUsers = await fetch(route('fetch-active-users'));
const activeUsers = ref([]);
const cancelledUsers = ref([]);
onMounted(async () => {
    axios.get(route('fetch-active-users'))
        .then(res => {
            activeUsers.value = res.data.users;
        });
    axios.get(route('fetch-cancelled-users'))
        .then(res => {
            cancelledUsers.value = res.data.users;
        });
});

const switchSearchTerm = function (type){
    this.searchType = type;
    Object.assign(sinForm, sinFormTemplate);
    Object.assign(nameForm, nameFormTemplate);
}

const sinFormTemplate = {
    filter_sin: '',
};
const sinForm = useForm(sinFormTemplate);
const sinFormSubmit = () => {
    sinForm.get(route('cases.index'), {
        onFinish: () => sinForm.reset('inputSin'),
    });
};

const nameFormTemplate = {
    filter_fname: '',
    filter_lname: '',
};
const nameForm = useForm(nameFormTemplate);
const nameFormSubmit = () => {
    nameForm.get(route('cases.index'), {
        onFinish: () => nameForm.reset('inputLastName', 'inputFirstName'),
    });
};
const statusFormTemplate = {
    filter_status: 'Active',
};
const statusForm = useForm(statusFormTemplate);
const statusFormSubmit = () => {
    statusForm.get(route('cases.index'), {
        onFinish: () => statusForm.reset('filter_status'),
    });
};

const activeUsersFormTemplate = {
    filter_user: activeUsers.value,
};
const activeUsersForm = useForm(activeUsersFormTemplate);
const activeUsersFormSubmit = () => {
    activeUsersForm.get(route('cases.index'), {
        onFinish: () => activeUsersForm.reset('selectActiveUser'),
    });
};

const cancelledUsersFormTemplate = {
    filter_user: activeUsers.value,
};
const cancelledUsersForm = useForm(cancelledUsersFormTemplate);
const cancelledUsersFormSubmit = () => {
    cancelledUsersForm.get(route('cases.index'), {
        onFinish: () => cancelledUsersForm.reset('selectCancelledUser'),
    });
};


</script>
