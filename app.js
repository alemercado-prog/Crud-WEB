const formulario = document.getElementById("formulario");
const listaUsuarios = document.getElementById("lista_usuarios");

async function cargarUsuarios() {
  const respuesta = await fetch("api.php");
  const usuarios = await respuesta.json();

  listaUsuarios.innerHTML = "";

  usuarios.forEach((usuario) => {
    listaUsuarios.innerHTML += `
            <tr>
                <td>${usuario.id}</td>
                <td>${usuario.nombre}</td>
                <td>${usuario.apellido}</td>
                <td>${usuario.email}</td>
                <td>${usuario.telefono}</td>
                <td>${usuario.fecha_nacimiento}</td>
                <td>${usuario.nacionalidad}</td>
                <td>
                    <button class="btn-editar" onclick="prepararEdicion(${usuario.id}, '${usuario.nombre}', '${usuario.apellido}', '${usuario.email}', '${usuario.telefono}', '${usuario.fecha_nacimiento}', '${usuario.nacionalidad}')">Editar</button>
                    <button class="btn-eliminar" onclick="eliminarUsuario(${usuario.id})">Eliminar</button>
                </td>
            </tr>
        `;
  });
}

formulario.addEventListener("submit", async (evento) => {
  evento.preventDefault();

  const id = document.getElementById("usuario_id").value;
  const nombre = document.getElementById("nombre").value;
  const apellido = document.getElementById("apellido").value;
  const email = document.getElementById("email").value;
  const telefono = document.getElementById("telefono").value;
  const fecha_nacimiento = document.getElementById("fecha_nacimiento").value;
  const nacionalidad = document.getElementById("nacionalidad").value;
  const metodo = id ? "PUT" : "POST";
  const datos = {
    id: id,
    nombre: nombre,
    apellido: apellido,
    email: email,
    telefono: telefono,
    fecha_nacimiento: fecha_nacimiento,
    nacionalidad: nacionalidad,
  };

  await fetch("api.php", {
    method: metodo,
    body: JSON.stringify(datos),
    headers: { "Content-Type": "application/json" },
  });

  formulario.reset();
  document.getElementById("usuario_id").value = "";

  cargarUsuarios();
});

function prepararEdicion(id, nombre, email) {
  document.getElementById("usuario_id").value = id;
  document.getElementById("nombre").value = nombre;
  document.getElementById("apellido").value = apellido;
  document.getElementById("email").value = email;
  document.getElementById("telefono").value = telefono;
  document.getElementById("fecha_nacimiento").value = fecha_nacimiento;
  document.getElementById("nacionalidad").value = nacionalidad;
}

async function eliminarUsuario(id) {
  if (confirm("¿Estas seguro de que desea eliminar a este usuario?")) {
    await fetch(`api.php?id=${id}`, { method: "DELETE" });
    cargarUsuarios();
  }
}

document.getElementById("buscador").addEventListener("input", (evento) => {
  const textoBuscado = evento.target.value.toLowerCase();
  const filas = document.querySelectorAll("#lista_usuarios tr");

  filas.forEach(fila => {
    const contenidoFila = fila.innerText.toLowerCase();

    if (contenidoFila.includes(textoBuscado)) {
      fila.style.display = "";
    } else {
      fila.style.display = "none";
    }
  });
});

cargarUsuarios();
