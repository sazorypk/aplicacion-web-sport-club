@extends('layouts.marketing')
@section('title', 'Contacto | Sport Club')
@section('content')
<!-- 5. SECCIÓN CONTACTO -->
        <section class="contacto" id="contacto">

            <!-- INFORMACIÓN DE CONTACTO -->
            <div class="contacto__info">
                <h2 class="contacto__title">¿LISTO PARA SUPERAR TUS LÍMITES?</h2>
                <p class="contacto__description">Da el primer paso y forma parte de nuestra comunidad deportiva. Contáctanos para conocer nuestras disciplinas, instalaciones y planes disponibles.</p>
                <h3 class="contacto__subtitle">Comunícate con nosotros</h3>
                <a href="https://wa.me/573217868266" class="contacto__button" target="_blank" rel="noopener noreferrer">Escríbenos por WhatsApp</a>
            </div>

            <!-- FORMULARIO -->
            <div class="contacto__form-container">
                <h3 class="contacto__form-title">Déjanos tus datos</h3>

                <form id="formulario"class="contacto__form" >

                    <!-- NOMBRE -->
                    <div class="contacto__field">
                        <label for="nombre" class="contacto__label">Nombre completo</label>
                        <input type="text" id="nombre" name="nombre" class="contacto__input" placeholder="Tu nombre" autocomplete="name" required>
                    </div>

                    <!-- CORREO -->
                    <div class="contacto__field">
                        <label for="correo" class="contacto__label">Correo electrónico</label>
                        <input type="email" id="correo" name="correo" class="contacto__input" placeholder="Tu correo electrónico" autocomplete="email" required>
                    </div>

                    <!-- TELÉFONO -->
                    <div class="contacto__field">
                        <label for="telefono" class="contacto__label">Teléfono</label>
                        <input type="tel" id="telefono" name="telefono" class="contacto__input" placeholder="Tu número de contacto" autocomplete="tel" required>
                    </div>

                    <!-- DISCIPLINA -->
                    <div class="contacto__field">
                        <label for="disciplina" class="contacto__label">Disciplina de interés</label>
                        <select id="disciplina" name="disciplina" class="contacto__select" required>
                            <option value="" selected disabled>Selecciona una disciplina</option>
                            <option value="futbol">Fútbol</option>
                            <option value="baloncesto">Baloncesto</option>
                            <option value="natacion">Natación</option>
                            <option value="atletismo">Atletismo</option>
                            <option value="voleibol">Voleibol</option>
                            <option value="gimnasio">Gimnasio</option>
                        </select>
                    </div>

                    <!-- MENSAJE -->
                    <div class="contacto__field">
                        <label for="mensaje" class="contacto__label">Mensaje</label>
                        <textarea id="mensaje" name="mensaje" class="contacto__textarea" rows="5" placeholder="Cuéntanos cómo podemos ayudarte" required></textarea>
                    </div>

                    <!-- BOTÓN ENVIAR -->
                    <button type="submit" class="contacto__submit">Continuar por WhatsApp</button>

                    <p id="respuesta" role="status"></p>

                </form>
            </div>
        </section>
@endsection
@push('scripts')<script src="{{ asset('js/landing.js') }}" defer></script>@endpush
