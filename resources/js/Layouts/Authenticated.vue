<template>
    <div>
        <div class="min-h-screen bg-gray-100">

            <NavBar />

            <!-- Page Heading -->
<!--            <header class="bg-white shadow" v-if="$slots.header">-->
<!--                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">-->
<!--                    <slot name="header" />-->
<!--                </div>-->
<!--            </header>-->

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
<script>
import { ref } from 'vue';
import NavBar from "@/Layouts/NavBar";
import axios from 'axios';
// const showingNavigationDropdown = ref(false);

export default {
    name: 'Authenticated',
    components: {
        NavBar
    },
    props: [],
    data() {
        return {
            showingNavigationDropdown: ref(false),
            searchType: '',
            searchData: '',
        }
    },
    methods: {
        emitSearch: function (data){
            this.$emit('search', data);
        }
    },
    mounted() {

    }
}
</script>
