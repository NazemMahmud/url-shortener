<template>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <AddComponent v-on:updateList="updateList($event)" />
        </div>

        <div class="mt-4">
            <ListComponent :items="urlsList" />
        </div>

    </div>
</template>

<script>
import AddComponent from "../components/AddComponent";
import ListComponent from "../components/ListComponent";
import { getUrlsList } from "../services/urlShortener.service";

export default {
    name: "Main",
    components: {
        AddComponent,
        ListComponent
    },
    data() {
        return {
            urlsList: [],
            isFormValid: true,
            form: {
                original_url: '',
            },
        }
    },
    methods: {
        getItemsList: async function () {
            // get all urls list
            await getUrlsList().then(res => {
                this.urlsList = res.data.data;
            }).catch(error => {
                console.log('error: ', error);
            });
        },
        updateList(data) {
            // update list item when new url shorten is added
            this.urlsList.unshift(data);
        }
    },
    created() {
        this.getItemsList();
    }
}
</script>
