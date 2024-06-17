(function () {
  obtenerTareas();
  let tareas = [];
  let filtradas = [];
  //boton para mostrar el Modal
  const nuevaTareaBtn = document.querySelector("#agregar-tarea");
  nuevaTareaBtn.addEventListener("click", function () {
    mostrarFormulario();
    console.log("okey");
  });

  //filtros busqueda

  const filtros = document.querySelectorAll('#filtros input[type="radio"]');
  filtros.forEach((radio) => {
    radio.addEventListener("input", filtrarTareas);
  });

  function filtrarTareas(e) {
    const filtro = e.target.value;
    if (filtro !== "") {
      filtradas = tareas.filter((tarea) => tarea.estado === filtro);
    } else {
      filtradas = [];
    }
    mostrarTareas();
  }
  async function obtenerTareas() {
    try {
      const id = obtenerProyecto();
      const url = `api/tareas?id=${id}`;
      const respuesta = await fetch(url);
      const resultado = await respuesta.json();
      tareas = resultado.tareas;
      mostrarTareas();
    } catch (error) {
      console.log(error);
    }
  }

  function mostrarTareas() {
    limpiarTareas();
    totalPendientes();
    totalCompletas();
    const arrayTareas = filtradas.length ? filtradas : tareas;
    if (arrayTareas.length === 0) {
      const contenedorTareas = document.querySelector("#listado-tareas");
      const textoNoTareas = document.createElement("LI");
      textoNoTareas.textContent = "No Hay Tareas";
      textoNoTareas.classList.add("no-tareas");
      contenedorTareas.appendChild(textoNoTareas);
      return;
    }

    const estados = {
      0: "Pendiente",
      1: "Completa",
    };
    arrayTareas.forEach((tarea) => {
      const contenedorTarea = document.createElement("LI");
      contenedorTarea.dataset.tareaId = tarea.id;
      contenedorTarea.classList.add("tarea");

      const nombreTarea = document.createElement("P");
      nombreTarea.textContent = tarea.nombre;

      const editname = document.createElement("I");
      editname.classList.add("ri-pencil-line");
      nombreTarea.appendChild(editname);
      nombreTarea.ondblclick = function () {
        mostrarFormulario((editar = true), { ...tarea });
      };

      const opcionesDiv = document.createElement("DIV");
      opcionesDiv.classList.add("opciones");
      const btnEstadoTarea = document.createElement("BUTTON");
      btnEstadoTarea.classList.add("estado-tarea");
      btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`);
      btnEstadoTarea.textContent = estados[tarea.estado];
      btnEstadoTarea.dataset.estadoTarea = tarea.estado;
      btnEstadoTarea.ondblclick = function () {
        cambiarEstadoTarea({ ...tarea });
      };

      const btnEliminarTarea = document.createElement("BUTTON");
      btnEliminarTarea.classList.add("eliminar-tarea");
      btnEliminarTarea.dataset.idtarea = tarea.id;
      btnEliminarTarea.textContent = "Eliminar";
      btnEliminarTarea.onclick = function () {
        confirmarEliminarTarea({ ...tarea });
      };

      opcionesDiv.appendChild(btnEstadoTarea);
      opcionesDiv.appendChild(btnEliminarTarea);
      contenedorTarea.appendChild(nombreTarea);
      contenedorTarea.appendChild(opcionesDiv);

      const listadoTareas = document.querySelector("#listado-tareas");
      listadoTareas.appendChild(contenedorTarea);
    });
  }

  function totalPendientes() {
    const totalPendientes = tareas.filter((tarea) => tarea.estado === "0");
    const pendientesRadio = document.querySelector("#pendientes");
    const pendientesLabel = pendientesRadio.nextElementSibling;

    pendientesLabel.addEventListener("click", function (event) {
      if (totalPendientes.length === 0) {
        pendientesRadio.disabled = totalPendientes.length === 0;
      } else {
        pendientesRadio.disabled = false;
      }
    });
  }
  function totalCompletas() {
    const totalCompletas = tareas.filter((tarea) => tarea.estado === "1");
    const completasRadio = document.querySelector("#completadas");
    const completasLabel = completasRadio.nextElementSibling;

    completasLabel.addEventListener("click", function (event) {
      if (totalCompletas.length === 0) {
        completasRadio.disabled = totalCompletas.length === 0;
      } else {
        completasRadio.disabled = false;
      }
    });
  }
  function mostrarFormulario(editar = false, tarea = {}) {
    const modal = document.createElement("DIV");
    modal.classList.add("modal");
    modal.innerHTML = `
      
      <form class="formulario nueva-tarea">
    
        <legend> ${editar ? "Editar Tarea" : "Agrega una nueva Tarea"} </legend>
          <div class="campo" >
              <label>Nueva Tarea</label>
              <input type="text" name="tarea" id="tarea" placeholder="${
                tarea.nombre
                  ? "Edita el nombre de la tarea "
                  : "Agrega una tarea al proyecto actual"
              }" value="${tarea.nombre ? tarea.nombre : ""}">
          </div>
          <div class="opciones">
              
              <button type="submit" class="botondeg submit-nueva-tarea azul" id="submit-nueva-tarea">
                <span class="transition verde submit-nueva-tarea"></span>
                <span class="gradient submit-nueva-tarea"></span>
                <span class="label submit-nueva-tarea"><i class="ri-add-circle-fill submit-nueva-tarea"> </i>${
                  editar ? "Guardar Cambios" : "Agregar Tarea"
                }</span>
              </button>
              
              <button type="button" class="botondeg cerrar-modal  naranja">
                <span class="transition rojo cerrar-modal"></span>
                <span class="gradient cerrar-modal"></span>
                <span class="label cerrar-modal"><i class="ri-close-line cerrar-modal"></i> Cancelar</span>
              </button>
              
          </div>
      </form>
      
      `;

    setTimeout(() => {
      const formulario = document.querySelector(".formulario");
      formulario.classList.add("animar");
    }, 0);

    modal.addEventListener("click", function (e) {
      e.preventDefault();

      if (e.target.classList.contains("cerrar-modal")) {
        const formulario = document.querySelector(".formulario");
        formulario.classList.add("cerrar");
        setTimeout(() => {
          modal.remove();
        }, 500);
      }
      if (e.target.classList.contains("submit-nueva-tarea")) {
        const nombreTarea = document.querySelector("#tarea").value.trim();
        if (nombreTarea === "") {
          mostrarAlerta(
            "El nombre de la tarea es obligatorio",
            "error",
            document.querySelector(".formulario legend")
          );
          return;
        }
        if (editar) {
          tarea.nombre = nombreTarea;
          actualizarTarea(tarea);
        } else {
          agregarTarea(nombreTarea);
        }
      }
    });

    document.querySelector(".dashboard").appendChild(modal);
  }

  function mostrarAlerta(mensaje, tipo, referencia) {
    const alertaPrevia = document.querySelector(".alerta");
    if (alertaPrevia) {
      alertaPrevia.remove();
    }
    const alerta = document.createElement("DIV");
    alerta.classList.add("alerta", tipo);
    alerta.textContent = mensaje;
    referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);

    setTimeout(() => {
      alerta.remove();
    }, 4000);
  }

  async function agregarTarea(tarea) {
    //construir peticion
    const datos = new FormData();
    datos.append("nombre", tarea);
    datos.append("proyectoId", obtenerProyecto());

    try {
      const url = `${location.origin}/api/tarea`;
      const respuesta = await fetch(url, {
        method: "POST",
        body: datos,
      });

      const resultado = await respuesta.json();
      swal.fire(resultado.mensaje, "", "success");

      if (resultado.tipo === "exito") {
        const modal = document.querySelector(".modal");
        setTimeout(() => {
          modal.remove();
        }, 0);

        const tareaObj = {
          id: String(resultado.id),
          nombre: tarea,
          estado: "0",
          proyectoId: resultado.proyectoId,
        };

        tareas = [...tareas, tareaObj];
        mostrarTareas();
      }
    } catch (error) {
      console.log(error);
    }
  }
  function cambiarEstadoTarea(tarea) {
    const nuevoEstado = tarea.estado === "1" ? "0" : "1";
    tarea.estado = nuevoEstado;
    actualizarTarea(tarea);
  }
  async function actualizarTarea(tarea) {
    const { estado, id, nombre, proyectoId } = tarea;
    const datos = new FormData();
    datos.append("id", id);
    datos.append("nombre", nombre);
    datos.append("estado", estado);
    datos.append("proyectoId", obtenerProyecto());
    try {
      const url = `${location.origin}/api/tarea/actualizar`;
      const respuesta = await fetch(url, {
        method: "POST",
        body: datos,
      });
      const resultado = await respuesta.json();
      if (resultado.respuesta.tipo === "exito") {
        swal.fire(resultado.respuesta.mensaje, "", "success");
        const modal = document.querySelector(".modal");

        if (modal) {
          modal.remove();
        }

        tareas = tareas.map((tareaMemoria) => {
          if (tareaMemoria.id === id) {
            tareaMemoria.estado = estado;
            tareaMemoria.nombre = nombre;
          }
          return tareaMemoria;
        });

        mostrarTareas();
      }
    } catch (error) {
      console.log(error);
    }

    // for (let valor of datos.values()) {
    //   console.log(valor);
    // }
  }

  function confirmarEliminarTarea(tarea) {
    Swal.fire({
      title: "Estas seguro de eliminar la Tarea?",
      text: "No se podrá revertir esta acción!",
      icon: "warning",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, Eliminarla!",
    }).then((result) => {
      if (result.isConfirmed) {
        Swal.fire({
          title: "Tarea Eliminada!",
          icon: "success",
        });
        eliminarTarea(tarea);
      }
    });
  }
  async function eliminarTarea(tarea) {
    const { estado, id, nombre } = tarea;
    const datos = new FormData();
    datos.append("id", id);
    datos.append("nombre", nombre);
    datos.append("estado", estado);
    datos.append("proyectoId", obtenerProyecto());

    try {
      const url = `${location.origin}/api/tarea/eliminar`;
      const respuesta = await fetch(url, {
        method: "POST",
        body: datos,
      });
      const resultado = await respuesta.json();
      if (resultado.resultado) {
        tareas = tareas.filter((tareaMemoria) => tareaMemoria.id !== tarea.id);
        mostrarTareas();
      }
    } catch (error) {
      console.log(error);
    }
  }

  function obtenerProyecto() {
    const proyectoParams = new URLSearchParams(window.location.search);
    const proyecto = Object.fromEntries(proyectoParams.entries());
    return proyecto.id;
  }
  function limpiarTareas() {
    const listadoTareas = document.querySelector("#listado-tareas");

    while (listadoTareas.firstChild) {
      listadoTareas.removeChild(listadoTareas.firstChild);
    }
  }
})();
