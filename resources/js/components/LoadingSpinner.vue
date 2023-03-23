<script setup>
import { useRoute } from 'vue-router';
const route = useRoute();

const props = defineProps({
  theme: String,
})
</script>

<template>
    <div class="ajax_loader_portfolio ajax_loader_watchlist" v-if="route.name =='Portfolio' || route.name == 'Watchlist'">
        <div v-if="theme == 'matrix'" style="margin-top:30px;margin-bottom:5px;">
            <img src="/img/spinner-matrix.gif" width="60">
        </div>
        <div v-if="theme == 'classic'" style="margin-top:30px;margin-bottom:5px;">
            <img src="/img/spinner-classic.gif" width="60">
        </div>
        <div v-if="theme == 'metaverse'" style="margin-top:15px;margin-bottom:5px;">
            <img src="/img/spinner-metaverse-3.gif" width="60">
        </div>
    </div>
    <div v-else style="margin-top:-15px;height:60px;">
        <div v-if="theme == 'matrix'">
            <img src="/img/spinner-matrix.gif" width="60">
        </div>
        <div v-if="theme == 'classic'">
            <img src="/img/spinner-classic.gif" width="60">
        </div>
        <div v-if="theme == 'metaverse'">
            <img src="/img/spinner-metaverse-3.gif" width="60">
        </div>
    </div>
</template>
    