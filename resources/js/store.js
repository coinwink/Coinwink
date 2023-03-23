// https://vuejs.org/guide/scaling-up/state-management.html#simple-state-management-with-reactivity-api

// store.js
import { reactive } from 'vue'

export const store = reactive({
  alertCreated: false
})