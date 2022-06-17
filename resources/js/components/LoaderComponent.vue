<template>
    <b-overlay :show="isLoading" no-wrap>
    </b-overlay>
</template>

<script>
export default {
    name: "LoaderComponent",
    props: ['isLoading'],
}
</script>

<style scoped>

</style>
