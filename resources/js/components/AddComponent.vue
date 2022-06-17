<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-body" >
                <b-form inline @submit.prevent="onSubmit" style="height: 62px;">
                        <b-form-input
                            id="inline-form-input-name"
                            class="mb-2 mr-sm-2 mb-sm-0 w-75"
                            required
                            placeholder="https://example.com"
                            v-model="$v.form.originalUrl.$model"
                            :state="validateState('originalUrl')"></b-form-input>

                    <b-button variant="primary" class="ml-5" size="sm" :disabled="!isFormValid"
                              :class="{disable: !isFormValid}"
                              type="submit">Submit</b-button>

                    <b-form-invalid-feedback
                        id="input-1-live-feedback">
                        This field is required and must be an url
                    </b-form-invalid-feedback>
                </b-form>
            </div>
        </div>
    </div>

</template>

<script>
import { validationMixin } from "vuelidate";
import { required, helpers } from "vuelidate/lib/validators";

import {createShortUrl} from "../services/urlShortener.service";

const url = helpers.regex('url', /^(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})$/i)

export default {
    name: "AddComponent",
    props: ['items'],
    mixins: [validationMixin],
    data() {
        return {
            isFormValid: false,
            form: {
                originalUrl: ""
            },
        }
    },
    validations: {
        form: {
            originalUrl: {
                required,
                url
            },
        }
    },
    methods: {
        validateState(name) {
            const { $dirty, $error } = this.$v.form[name];
            this.isFormValid = $dirty ? !$error : null;
            return this.isFormValid;
        },
        // on form submit action
        onSubmit: async function() {
            this.$emit('loadStart'); // loading starts
            await createShortUrl({original_url: this.form.originalUrl}).then(res => {
                this.$emit('updateList', res.data.data); // send data to main parent
            }).catch(error => {
                this.$emit('handleError', error.response.data.error); // send data to main parent
            });
        },
    },
}
</script>

<style scoped>
    .disable {
        cursor: not-allowed;
    }
</style>
