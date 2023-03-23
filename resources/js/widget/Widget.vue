<script setup>
    const props = defineProps({
        title: String,
        secondary: String
    })
</script>

<template>
    <div class="widget-container">
        <header>
            <h1>{{title}}</h1>
            <span v-if="secondary">
                <div style="margin-top:-18px;font-size:11px;">{{ secondary }}</div>
                <div style="height:14px;"></div>
            </span>
        </header>
        <slot></slot>
        <div style="height:30px;"></div>
    </div>
</template>
