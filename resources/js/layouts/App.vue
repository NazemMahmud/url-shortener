<template>
   <router-view />
</template>

<script>

import {createShortUrl} from "../services/urlShortener.service";

export default {
    name: "App",
    components: {},
    data() {
        return {
            show: true
        }
    },
    methods: {
        // on form submit action
        isLoading: function() {
            this.show = !this.show
            // return this.show;
        },
    }
}
</script>
