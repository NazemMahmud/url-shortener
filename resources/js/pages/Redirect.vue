<template>

    <div class="container mt-8" v-if="!isLoading">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-body">
                        <h1> 404 | Page not found</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>

<script>

import {getOriginalUrl} from "../services/urlShortener.service";

export default {
    name: "Redirect",
    components: {},
    data() {
        return {
            isLoading: true
        }
    },
    methods: {
        getMainUrl: async function () {
            // get original URL of respective short code
            await getOriginalUrl(this.$route.params.hashCode).then(res => {
                window.location.href = res.data.data.original_url;
            }).catch(error => {
                console.log('error: ', error);
                this.isLoading = !this.isLoading;
            });
        },
    },
    created() {
        this.getMainUrl();
    }
}
</script>
