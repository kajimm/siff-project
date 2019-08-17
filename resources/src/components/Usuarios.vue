<template>
	<div class="table-container" style="overflow:hidden;">
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
	    			<a class="button is-warning" :href="'/admin/usuarios/view/'+ items.id_usuario">
					    <span class="icon is-small">
					      <i class="material-icons" style="color: white;">
							create
						  </i>
					    </span>
					  </a>
					   <a class="button is-danger">
					    <span class="icon is-small">
					      <i class="material-icons">
							delete_forever
						 </i>
					    </span>
					  </a>
	    		</td>
	    	</tr>
	    </tbody>
	  </table>
	  <nav class="pagination is-small" role="navigation" aria-label="pagination">
		  <a class="pagination-previous">
		  	<i class="material-icons">
			keyboard_arrow_left
			</i>
		  </a>
		  <a class="pagination-next">
		  	<i class="material-icons">
			keyboard_arrow_right
			</i>
		  </a>
		  <ul class="pagination-list">
		    <li><a class="pagination-link is-current" aria-label="Goto page 1" aria-current="page">1</a></li>
		    <li><a class="pagination-link" aria-label="Goto page 1">2</a></li>
		    <li><a class="pagination-link" aria-label="Goto page 1">3</a></li>
		    <li><a class="pagination-link" aria-label="Goto page 1">4</a></li>
		    <li><a class="pagination-link" aria-label="Goto page 1">5</a></li>
		    <li><a class="pagination-link" aria-label="Goto page 1">6</a></li>
		    <li><a class="pagination-link" aria-label="Goto page 1">7</a></li>
		  </ul>
		</nav>
	
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