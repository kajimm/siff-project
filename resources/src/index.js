import Vue from 'vue';
import ejemplo from './components/App.vue';
import tarea from './components/nav.vue';
import list from './components/listado_clientes.vue';
import usuarios from './components/Usuarios.vue';

new Vue({
  el: "#app",
  components: {
    ejemplo,
    tarea,
    list,
    usuarios
  }
});

/// los componentes se agregan directamente en el html el app de vue se renderiza en el layout pricipal del cual
/// se extienden las demas plantillas
/// <ejemplo></ejemplo>