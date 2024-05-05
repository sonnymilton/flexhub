import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue-next/dist/bootstrap-vue-next.css'
import './assets/main.css'

import {createApp} from 'vue'
import createBootstrap from 'bootstrap-vue-next'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import VueAxios from 'vue-axios'


const app = createApp(App)

app.use(router)
app.use(createBootstrap({components: true, directives: true}))
app.use(VueAxios, axios)

app.provide('axios', app.config.globalProperties.axios)

app.mount('#app')
