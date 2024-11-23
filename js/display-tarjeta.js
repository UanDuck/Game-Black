document.getElementById('add-card-btn').addEventListener('click', function () {
    var tarjetaDiv = document.getElementById('add-tarjeta');
    if (tarjetaDiv.style.display === 'none' || tarjetaDiv.style.display === '') {
        tarjetaDiv.style.display = 'block'; 
    } else {
        tarjetaDiv.style.display = 'none'; //ocultamos si ya esta visible
    }
});