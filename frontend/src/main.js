import './assets/main.css'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router'
import LoadingSpinner from './components/LoadingSpinner.vue'
import MessageAlert from './components/MessageAlert.vue'

const app = createApp(App)

app.component('LoadingSpinner', LoadingSpinner)
app.component('MessageAlert', MessageAlert)

app.use(router)

app.mount('#app')
