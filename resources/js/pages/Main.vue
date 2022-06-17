<template>
    <div class="container mt-4">
        <LoaderComponent :isLoading="isLoading" />
        <div class="row justify-content-center">
            <AddComponent v-on:updateList="updateList($event)" v-on:loadStart="updateLoader($event)" />
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
            isFormValid: true,
            form: {
                original_url: '',
            },
            isLoading: false
        }
    },
    methods: {
        getItemsList: async function () {
            this.updateLoader();
            // get all urls list
            await getUrlsList().then(res => {
                this.urlsList = res.data.data;
                this.updateLoader();
            }).catch(error => {
                console.log('error: ', error);
                this.updateLoader();
            });
        },
        updateList: function (data) {
            // update list item when new url shorten is added
            this.urlsList.unshift(data);
            this.updateLoader();
        },
        updateLoader: function () {
            this.isLoading = !this.isLoading;
        }
    },
    created() {
        this.getItemsList();
    }
}
</script>
