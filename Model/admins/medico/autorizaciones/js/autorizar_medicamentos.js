var medicamentosSeleccionados = [];

document.getElementById("search").addEventListener("keyup", function () {
    var searchTerm = this.value.trim().toLowerCase();
    var rows = document.querySelectorAll("#userTable tr");
    var resultsContainer = document.getElementById("searchResults");

    // Limpiar resultados anteriores
    resultsContainer.innerHTML = "";

    rows.forEach(function (row) {
        var nameColumn = row.querySelector("td:nth-child(2)");
        if (nameColumn) {
            var nameValue = nameColumn.textContent.trim().toLowerCase();
            var presentacionValue = row.querySelector("td:nth-child(3)").textContent.trim().toLowerCase();
            var cantidadValue = 0;

            if (nameValue.includes(searchTerm) || presentacionValue.includes(searchTerm)) {
                // Crear elemento de resultado
                var resultItem = document.createElement("div");
                resultItem.className = "list-group-item list-group-item-action";
                resultItem.textContent = nameColumn.textContent;

                // Guardar datos adicionales en atributos del elemento
                resultItem.setAttribute("data-id", row.querySelector("td:nth-child(1)").textContent);
                resultItem.setAttribute("data-name", nameColumn.textContent);
                resultItem.setAttribute("data-presentacion", presentacionValue);
                resultItem.setAttribute("data-cantidad", cantidadValue);

                // Agregar evento de clic al resultado
                resultItem.addEventListener("click", function () {
                    var selectedId = resultItem.getAttribute("data-id");
                    var selectedName = resultItem.getAttribute("data-name");
                    var selectedPresentacion = resultItem.getAttribute("data-presentacion");
                    var selectedCantidad = resultItem.getAttribute("data-cantidad");

                    // Crear una nueva fila en la tabla de medicamentos seleccionados
                    var selectedTableBody = document.getElementById("selectedMedicamentos").querySelector("tbody");
                    var newRow = document.createElement("tr");

                    newRow.innerHTML = `
                        <td>${selectedId}</td>
                        <td>${selectedName}</td>
                        <td>${selectedPresentacion}</td>
                        <td>
                            <input type="number" name="id_medicamento[]" value="${selectedCantidad}" min="1" max="999" step="1">
                        </td>
                    `;

                    selectedTableBody.appendChild(newRow);

                    // Agregar medicamento seleccionado al array
                    medicamentosSeleccionados.push({
                        id: selectedId,
                        name: selectedName,
                        presentacion: selectedPresentacion,
                        cantidad: selectedCantidad
                    });

                    // Agregar evento de cambio al input de cantidad
                    newRow.querySelector("input[type='number']").addEventListener("change", function () {
                        var updatedCantidad = this.value;
                        var medicamento = medicamentosSeleccionados.find(function (m) {
                            return m.id === selectedId;
                        });
                        if (medicamento) {
                            medicamento.cantidad = updatedCantidad;
                        }

                        // Actualizar el campo de texto con la cadena JSON
                        document.getElementById("cadenaMedicamentos").value = JSON.stringify(medicamentosSeleccionados);
                    });

                    // Actualizar el campo de texto con la cadena JSON
                    document.getElementById("cadenaMedicamentos").value = JSON.stringify(medicamentosSeleccionados);

                    // Limpiar el campo de búsqueda
                    document.getElementById("search").value = "";
                    resultsContainer.innerHTML = "";
                });

                // Agregar el resultado al contenedor
                resultsContainer.appendChild(resultItem);
            }
        }
    });
});

function validarCantidad() {
    var cantidadInputs = document.querySelectorAll("#selectedMedicamentos input[type='number']");
    for (var i = 0; i < cantidadInputs.length; i++) {
        if (cantidadInputs[i].value === "" || parseInt(cantidadInputs[i].value) <= 0) {
            alert("Por favor, ingresa una cantidad válida para todos los medicamentos seleccionados.");
            return false;
        }
    }

    // Convertir array de medicamentos seleccionados a JSON y asignarlo a un campo oculto del formulario
    document.getElementById("cadenaMedicamentos").value = JSON.stringify(medicamentosSeleccionados);

    return true;
}

function validateNumberInput(event) {
    var key = event.key;
    if (!/^[0-9]$/.test(key)) {
        event.preventDefault();
    }
}

function checkCodeExists() {
    var cod_autorizacion = document.getElementById("cod_autorizacion").value;
    if (cod_autorizacion) {
        // Realizar la validación del código de autorización
        // (Aquí puedes implementar una llamada AJAX para verificar en el servidor)
    }
}
