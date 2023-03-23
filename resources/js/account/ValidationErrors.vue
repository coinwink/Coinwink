<template>
    <div v-if="hasErrors" style="color:red;">
        <div v-for="(error, key) in errors" :key="key" style="margin-top:10px;font-weight:bold;">{{ error[0] }}</div>
    </div>
</template>

<script>
    export default {
        computed: {
            hasErrors() {
                return Object.keys(this.errors).length > 0;
            },
        },
        props: [ 'errors' ]
    }
</script>
