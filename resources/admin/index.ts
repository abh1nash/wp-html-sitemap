import { createApp } from 'vue';
import "primevue/resources/themes/saga-blue/theme.css";
import "primevue/resources/primevue.min.css";
import "primeicons/primeicons.css";
import 'primeflex/primeflex.css';
import PrimeVue from 'primevue/config';
import ToastService from 'primevue/toastservice';
import App from './App.vue';

const app = createApp(App);
app.use(PrimeVue);
app.use(ToastService);
app.mount("#ak-html-sitemap-editor-app-root");