<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <b-form inline @submit.prevent="onSubmit">
                    <b-form-input
                        id="inline-form-input-name"
                        class="mb-2 mr-sm-2 mb-sm-0 w-75"
                        placeholder="Enter URL..."
                        v-model="form.original_url"></b-form-input>

                    <b-button variant="primary" class="ml-5" size="sm" :disabled="!isFormValid" type="submit">Submit</b-button>
                </b-form>
            </div>
        </div>
    </div>

</template>

<script>

import {createShortUrl} from "../services/urlShortener.service";

export default {
    name: "AddComponent",
    props: ['items'],
    data() {
        return {
            isFormValid: true,
            form: {
                original_url: '',
            },
        }
    },
    methods: {
        // on form submit action
        onSubmit: async function() {
            this.$emit('loadStart');
            await createShortUrl({original_url: this.form.original_url}).then(res => {
                this.$emit('updateList', res.data.data); // send data to main parent
            }).catch(error => {
                console.log('error: ', error);
            });
        },
    },
}
</script>

<style scoped>

</style>
