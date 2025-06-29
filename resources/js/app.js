require('./bootstrap');

import { createApp } from 'vue';
import EquiposIndex from './components/Equipos/Index.vue';
import Swal from 'sweetalert2';

const app = createApp({});

app.component('equipos-index', EquiposIndex);

app.config.globalProperties.$swal = Swal;

app.mount('#app');