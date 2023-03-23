<script setup>
    const props = defineProps({
        title: String
    })
</script>

<template>
    <div class="rounded-border">
        <b>{{ title }}</b>
        <div style="height:20px;"></div>
        <slot></slot>
    </div>
    <div style="height:20px;"></div>
</template>

<style scoped>
    .rounded-border {
        padding: 20px 10px 20px 10px;
        border: 1px solid #afafaf;
        border-radius: 3px;
        max-width: 300px;
        margin: 0 auto;
    }
</style>