<?php include_once __DIR__ . '/header-dashboard.php'; ?>


<div class="contenedor-sm">
    <div class="contenedor-nueva-tarea">
        <button class="botondeg agregar-tarea" id="agregar-tarea">
            <span class="transition"></span>
            <span class="gradient"></span>
            <span class="label"><i class="ri-add-circle-fill"> </i> Nueva tarea</span>
        </button>

        <div class="filtros" id="filtros">
            
            <div class="radio-inputs"><h2 class="filname">Filtros:</h2>
                <label class="radio">
                    <input type="radio" name="filtro" id="todas" value="" checked>
                    <span class="name">Todas</span>
                </label>
                <label class="radio">
                    <input type="radio" name="filtro" id="completadas" value="1" >
                    <span class="name">Completadas</span>
                </label>

                <label class="radio">
                    <input type="radio" name="filtro" id="pendientes" value="0" >
                    <span class="name">Pendientes</span>
                </label>
            </div>
        </div>


                
                
 
        <ul id="listado-tareas" class="listado-tareas">
            
        </ul>
    </div>
</div>


<?php include_once __DIR__ . '/footer-dashboard.php'; ?>
<?php $script='
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="build/js/bundle.min.js"></script>';?>

