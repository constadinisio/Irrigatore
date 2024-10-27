$(document).ready(function() {
    // Set the timer to check for new data every 10 seconds
    setInterval(function() {
        // Send an AJAX request to your PHP script
        $.ajax({
            type: 'GET',
            url: 'update_data.php',
            dataType: 'json',
            success: function(data) {
                // Update the HTML elements with the new data
                $('#numeroCiclo').text(data.numeroCiclo);
                $('#tempAmbiente').text(data.temperatura + ' °C');
                $('#humAmbiente').text(data.humedad_aire + ' %');
                $('#porcHumedad').text(data.humedad_tierra + ' %');
                $('#estadoRele').text(data.estado_rele);
                $('#creacion').text(data.creacion);
            }
        });
    }, 10000); // 10 seconds
});