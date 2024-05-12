import { createApp } from 'vue';
const app = createApp({});
import ExampleComponent from './components/ExampleComponent.vue';
app.component('example-component', ExampleComponent);

import CreateComponent from './components/CreateComponent.vue';
app.component('create-component', CreateComponent);

import IndexComponent from './components/IndexComponent.vue';
app.component('index-component', IndexComponent);

import EditComponent from './components/EditComponent.vue';
app.component('edit-component', EditComponent);
app.mount('#app');