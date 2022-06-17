<template>
    <div class="mt-5 mb-5">
        <b-table bordered hover="hover" class="mb-0"
                 :items="items"
                 :fields="fields">
            <template #cell(url_hash)="data">
                <a target="_blank" v-bind:href="getLink(data.value)">{{ data.value }}</a>
            </template>
        </b-table>
    </div>
</template>

<script>

export default {
    name: "ListComponent",
    props: ['items'],
    data() {
        return {
            baseURL: window.location.origin,
            fields: [
                {key: 'original_url', label: 'Main URL'},
                {key: 'url_hash', label: 'Short Code'},
            ],
        }
    },
    methods: {
        getLink(url) {
            // make full URL with short code
            return this.baseURL + '/' + url;
        },
    },
}
</script>

<style scoped>

</style>
