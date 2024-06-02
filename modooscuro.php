<div style="position: absolute; top: 10px; right: 10px;">
    <button id="botonModoOscuro">Modo Oscuro</button>
</div>

<script>
       
function alternarModoOscuro() {
    const cuerpo = document.body;
    cuerpo.classList.toggle('modo-oscuro');
    const modoOscuroActivado = cuerpo.classList.contains('modo-oscuro');
    localStorage.setItem('modoOscuro', modoOscuroActivado);
}


// verificar si la página está en modo oscuro al recargarla
document.addEventListener('DOMContentLoaded', (evento) => {
    const modoOscuroGuardado = localStorage.getItem('modoOscuro');
    const cuerpo = document.body;
    if (modoOscuroGuardado === 'true') {
        cuerpo.classList.add('modo-oscuro');
    } else {
        cuerpo.classList.remove('modo-oscuro');
    }

    document.getElementById('botonModoOscuro').addEventListener('click', alternarModoOscuro);
});
</script>


<style>

#botonModoOscuro {
    margin: 10px;
    padding: 8px 16px;
    font-size: 16px;
    background-color: #007bff;
    color: #fff;
    border: none;
    transition: transform 0.1s ease, background-color 0.3s ease;
    }

.modo-oscuro {
    background-color: #121212;
    color: #ffffff;
}

.modo-oscuro .cabecera,
.modo-oscuro .barra-lateral,
.modo-oscuro .contenido,
.modo-oscuro pie {
    background-color: #1e1e1e;
    color: #ffffff;
}

.modo-oscuro input,
.modo-oscuro textarea,
.modo-oscuro select,
.modo-oscuro button {
    background-color: #333333;
    color: #ffffff;
    border: 1px solid #555555;
}

.modo-oscuro a {
    color: #bb86fc;
}

.modo-oscuro a:hover {
    color: #ffffff;
}

.modo-oscuro div {
    background-color: #1e1e1e;
    color: #ffffff;
}

.modo-oscuro .boton-regresar {
    color: #bb86fc;
}

.modo-oscuro .boton-regresar:hover {
    color: #ffffff;
}
</style>
