<template>
    <div class="container mt-4">
        <LoaderComponent :isLoading="isLoading" />
        <div class="row justify-content-center">
            <AddComponent v-on:updateList="updateList($event)"
                          v-on:loadStart="updateLoader($event)"
                          v-on:handleError="handleError($event)"
                          ref="addForm"/>
        </div>

        <div class="mt-4">
            <ListComponent :items="urlsList" />
        </div>

    </div>
</template>

<script>

import LoaderComponent from "../components/LoaderComponent";
import AddComponent from "../components/AddComponent";
import ListComponent from "../components/ListComponent";
import { getUrlsList } from "../services/urlShortener.service";

export default {
    name: "Main",
    components: {
        AddComponent,
        ListComponent,
        LoaderComponent
    },
    data() {
        return {
            urlsList: [],
            form: {
                original_url: '',
            },
            isLoading: false
        }
    },
    methods: {
        // get all items list
        getItemsList: async function () {
            this.updateLoader();
            // get all urls list
            await getUrlsList().then(res => {
                this.urlsList = res.data.data;
                this.updateLoader();
            }).catch(error => {
                this.handleToast('Something went wrong', 'danger');
                this.updateLoader();
            });
        },
        updateList: function (data) {
            // update list item when new url shorten is added,
            // if URL is already added, just show toast message
            let message = 'This Data already exist';
            let variant = 'info';
            if (!data.hasOwnProperty('message')) {
                message = 'Data created successfully';
                variant = 'success';
            } else {
                this.urlsList = this.urlsList.filter(item => item.original_url !== data.original_url);
            }
            this.urlsList.unshift(data);
            this.updateLoader();
            this.handleToast(message, variant);
            this.$refs.addForm.resetForm();
        },
        // show / hide loader
        updateLoader: function () {
            this.isLoading = !this.isLoading;
        },
        // show error toast message/s
        handleError: function (message) {
            typeof message === 'object' ? message.forEach(elem => this.handleToast(elem, 'danger')) : this.handleToast(message, 'danger');
            this.updateLoader();
        },
        // show hide toast box
        handleToast: function (message, variant = 'default') {
            this.$bvToast.toast(message, {
                autoHideDelay: 2000,
                variant: variant,
                appendToast: false,
                noCloseButton: true
            })
        }
    },
    created() {
        this.getItemsList();
    }
}
</script>
