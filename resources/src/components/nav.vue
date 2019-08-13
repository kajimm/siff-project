<template>
    <div class="card">
        <div class="card-header">
            <p class="card-header-title">
                Crear tareas
             </p>
        </div>
        <div class="card-content">
            <form method="POST">
                <div class="field">
                  <label class="label">Nombre</label>
                  <div class="control">
                    <input class="input" type="text" placeholder="Nombre de la tarea">
                  </div>
                </div>

                <div class="field">
                  <p class="control">
                    <span class="select">
                      <select>
                        <option selected>Colaborador</option>
                        <option>Martha</option>
                        <option>Julian</option>
                        <option>Andres</option>
                        <option>Pedro</option>
                        <option>Albereto</option>
                      </select>
                    </span>
                  </p>
                </div>
                <div class="field">
                  <label class="label">Entregar</label>
                  <div class="control">
                    <input class="input" type="date" placeholder="Fecha">
                  </div>
                  <p class="help">Fecha en la que se debe entregar</p>
                </div>
                <button class="button is-primary" type="submit">
                    Asignar tarea
                </button>
                <button class="button is-danger" type="reset">
                    Cancelar
                </button>
            </form>
        </div>              
    </div>
</template>
<script type="text/javascript">
export default {
  name: "app"
};
</script>
