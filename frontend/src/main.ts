import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'
import './assets/main.css'

import {createApp} from 'vue'
import createBootstrap from 'bootstrap-vue-next'
import App from './App.vue'
import router from './router'
import {Api} from "@/Flexhub.api";

const app = createApp(App)
const api = new Api({baseApiParams: {headers: {'Accept': 'application/json'}}});

app.use(router)
app.use(createBootstrap({components: true, directives: true}))

app.provide('flexhubApi', api);

app.mount('#app')
