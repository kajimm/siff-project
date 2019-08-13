<template>
	<div class="table-container">
	  <table class="table is-fullwidth">
	    <thead>
	    	<th>Nombre</th>
	    	<th>Apellido</th>
	    	<th>Correo</th>
	    	<th>Celular</th>
	    	<th>Fecha de registro</th>
	    	<th>Ultima actualización</th>
	    	<th>Acciones</th>
	    </thead>
	    <tbody>
	    	<tr v-for="(items, index) in usuarios" :key="index">
	    		<td>{{ items.nombre }}</td>
	    		<td>{{ items.apellido }}</td>
	    		<td>{{ items.correo }}</td>
	    		<td>{{ items.telefono }}</td>
	    		<td>{{ items.created_at }}</td>
	    		<td>{{ items.updated_at }}</td>
	    		<td>
	    			<a class="button is-warning">
					    <span class="icon is-small">
					      <i class="fa fa-user-edit" style="color: white;"></i>
					    </span>
					  </a>
					  <a class="button is-primary">
					    <span class="icon is-small">
					      <i class="far fa-eye"></i>
					    </span>
					  </a>
					   <a class="button is-danger">
					    <span class="icon is-small">
					      <i class="fas fa-trash"></i>
					    </span>
					  </a>
	    		</td>
	    	</tr>
	    </tbody>
	  </table>
	</div>
</template>
<script>
const axios = require('axios');

	export default {
		data(){
			return {
				usuarios: []
			}		
		},
		created(){
			axios.get('/admin/usuarios/get')
			.then(response => {
				this.usuarios = response.data;
			});
		}
	};
</script>