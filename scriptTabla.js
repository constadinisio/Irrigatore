async function cargarRegistros() {
  try {
    const response = await fetch("cargar_registros.php"); // Cambia a la ruta de tu archivo PHP
    const data = await response.json();

    const tabla = document.getElementById("tabla-registros");
    tabla.innerHTML = ""; // Limpiar tabla antes de agregar nuevos registros

    data.forEach((registro) => {
      const fila = document.createElement("tr");
      fila.innerHTML = `
                        <td>${registro.idRegada}</td>
                        <td>${registro.fecha}</td>
                        <td>${registro.hora}</td>
                        <td>${registro.humedad_antes}</td>
                        <td>${registro.humedad_despues}</td>
                    `;
      tabla.appendChild(fila);
    });
  } catch (error) {
    console.error("Error al cargar los registros:", error);
  }
}

// Cargar registros al iniciar la página
window.onload = cargarRegistros;
