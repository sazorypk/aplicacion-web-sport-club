const formulario = document.getElementById('formulario');

formulario.addEventListener('submit', (event) => {
    event.preventDefault();
    const datos = new FormData(formulario);
    const mensaje = `Hola, soy ${datos.get('nombre')}.\nCorreo: ${datos.get('correo')}\nTeléfono: ${datos.get('telefono')}\nDisciplina de interés: ${datos.get('disciplina')}\nMensaje: ${datos.get('mensaje')}`;
    window.open(`https://wa.me/573217868266?text=${encodeURIComponent(mensaje)}`, '_blank', 'noopener,noreferrer');
    document.getElementById('respuesta').textContent = 'Continúa la conversación en WhatsApp para enviar tu mensaje.';
});
