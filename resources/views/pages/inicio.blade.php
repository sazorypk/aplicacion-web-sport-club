
<!DOCTYPE html>
<html lang="es">

<head>
    <!-- METAETIQUETAS -->
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sport Club, un centro multideportivo donde encontrarás diferentes disciplinas, instalaciones modernas y entrenadores especializados.">
    <title>Sport Club | Centro Multideportivo</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">
    <link rel="stylesheet" href="{{ asset('css/landing.css') }}">
    <script src="{{ asset('js/landing.js') }}" defer></script>
    <link rel="icon" href="{{ asset('assets/img/logo.ico') }}" type="image/x-icon">
</head>

<body>

    <!-- HEADER / NAVBAR -->
    <header class="header">
        <nav class="navbar" aria-label="Navegación principal">

            <!-- LOGO -->
            <a href="#inicio" class="navbar__logo">
                <img src="{{ asset('assets/img/logo.png') }}" alt="Sport Club Logo" class="navbar__logo-image">
            </a>

            @guest
            <ul class="navbar__menu" id="landing-menu">
                <li><a href="#inicio" class="navbar__link">Inicio</a></li>
                <li><a href="#nosotros" class="navbar__link">Nosotros</a></li>
                <li><a href="#disciplinas" class="navbar__link">Disciplinas</a></li>
                <li><a href="#planes" class="navbar__link">Planes</a></li>
                <li><a href="#contacto" class="navbar__link">Contacto</a></li>
            </ul>
            <div class="navbar__actions">
                <a href="{{ route('login') }}" class="navbar__link">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="navbar__button">Únete ahora</a>
            </div>
            @else
            <div class="navbar__actions">
                <a href="{{ auth()->user()->role === 'master' ? route('admin.dashboard') : route('dashboard') }}" class="navbar__button">{{ auth()->user()->role === 'master' ? 'Panel maestro' : 'Mi panel' }}</a>
                <form method="POST" action="{{ route('logout') }}">@csrf<button class="navbar__logout" type="submit">Cerrar sesión</button></form>
            </div>
            @endguest
        </nav>
    </header>

    <!-- CONTENIDO PRINCIPAL -->
    <main>

        <!-- 1. HERO SECTION -->
        <section class="hero" id="inicio">

            <div class="hero__content">
                <h1 class="hero__title">UN SOLO CLUB E INFINITAS FORMAS DE SUPERARTE.</h1>
                <p class="hero__description">Descubre un espacio donde el deporte, la disciplina y la pasión se encuentran. Entrena diferentes modalidades, mejora tus habilidades y alcanza tus objetivos junto a profesionales.</p>
                <a href="#disciplinas" class="hero__button">Ver disciplinas</a>
            </div>
</section>

        <!-- 2. SECCIÓN NOSOTROS -->
        <section class="nosotros" id="nosotros">

            <div class="nosotros__content">
                <h2 class="nosotros__title">MÁS QUE UN CLUB, UNA PASIÓN QUE NOS UNE</h2>
                <p class="nosotros__description">En Sport Club somos un centro multideportivo dedicado a promover el bienestar, la disciplina y el desarrollo de nuestros deportistas. Contamos con diferentes modalidades deportivas, instalaciones modernas y entrenadores especializados que acompañan a niños, jóvenes y adultos en cada etapa de su formación. Nuestro propósito es crear una comunidad donde cada persona encuentre un espacio para entrenar, superar sus límites y disfrutar del deporte.</p>
                <a href="#disciplinas" class="nosotros__button">Explora nuestras disciplinas</a>
            </div>

            <div class="nosotros__image">
                <img src="{{ asset('assets/img/nosotros.webp') }}" alt="Comunidad deportiva de Sport Club" class="nosotros__image-file" loading="lazy">
            </div>
        </section>

        <!-- 3. SECCIÓN DISCIPLINAS -->
        <section class="disciplinas" id="disciplinas">

            <div class="disciplinas__header">
                <h2 class="disciplinas__title">ENCUENTRA TU DISCIPLINA</h2>
                <p class="disciplinas__description">Explora nuestras modalidades deportivas y descubre nuevas formas de superar tus límites.</p>
            </div>

            <!-- CONTENEDOR DE TARJETAS -->
            <div class="disciplinas__grid">

                <!-- TARJETA 1 - FÚTBOL -->
                <article class="disciplina-card disciplina-card--featured">
                    <img src="{{ asset('assets/img/futbol.webp') }}" alt="Entrenamiento de fútbol" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Fútbol</h3>
                        <p class="disciplina-card__description">Desarrolla tu técnica, resistencia y trabajo en equipo en nuestras instalaciones.</p>
                        <a href="#contacto" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 2 - BALONCESTO -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/baloncesto.webp') }}" alt="Entrenamiento de baloncesto" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Baloncesto</h3>
                        <p class="disciplina-card__description">Mejora tu coordinación, agilidad y habilidades mientras disfrutas del deporte en equipo.</p>
                        <a href="#contacto" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 3 - NATACIÓN -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/natacion.webp') }}" alt="Entrenamiento de natación" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Natación</h3>
                        <p class="disciplina-card__description">Fortalece tu cuerpo y desarrolla nuevas habilidades dentro del agua.</p>
                        <a href="#contacto" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 4 - ATLETISMO -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/atletismo.webp') }}" alt="Entrenamiento de atletismo" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Atletismo</h3>
                        <p class="disciplina-card__description">Supera tus marcas personales con entrenamientos enfocados en velocidad y resistencia.</p>
                        <a href="#contacto" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 5 - VOLEIBOL -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/voleibol.webp') }}" alt="Entrenamiento de voleibol" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Voleibol</h3>
                        <p class="disciplina-card__description">Entrena tus reflejos, coordinación y comunicación mientras disfrutas de una disciplina en equipo.</p>
                        <a href="#contacto" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

                <!-- TARJETA 6 - GIMNASIO -->
                <article class="disciplina-card">
                    <img src="{{ asset('assets/img/gimnasio.webp') }}" alt="Entrenamiento en gimnasio" class="disciplina-card__image" loading="lazy">
                    <div class="disciplina-card__content">
                        <h3 class="disciplina-card__title">Gimnasio</h3>
                        <p class="disciplina-card__description">Mejora tu condición física y alcanza tus objetivos con diferentes rutinas de entrenamiento.</p>
                        <a href="#contacto" class="disciplina-card__button">Conoce más</a>
                    </div>
                </article>

            </div>
        </section>

        <!-- TESTIMONIOS -->
        <section class="testimonios" id="testimonios">
            <div class="testimonios__header">
                <h2 class="testimonios__title">LO QUE DICE NUESTRA COMUNIDAD</h2>
                <p class="testimonios__description">Historias de personas que entrenan, crecen y superan sus metas en Sport Club.</p>
            </div>
            <div class="testimonios__grid">
                <article class="testimonio-card">
                    <div class="testimonio-card__avatar" aria-hidden="true">CM</div>
                    <p class="testimonio-card__quote">“Encontré un espacio donde puedo entrenar y compartir mi pasión por el deporte. Los entrenadores siempre están pendientes de mi progreso.”</p>
                    <h3 class="testimonio-card__name">Camila Martínez</h3>
                    <p class="testimonio-card__role">Deportista de natación</p>
                </article>
                <article class="testimonio-card testimonio-card--featured">
                    <div class="testimonio-card__avatar" aria-hidden="true">JR</div>
                    <p class="testimonio-card__quote">“Desde que llegué al club mejoré mi técnica y encontré un equipo que me motiva a dar lo mejor en cada entrenamiento.”</p>
                    <h3 class="testimonio-card__name">Juan Rodríguez</h3>
                    <p class="testimonio-card__role">Jugador de fútbol</p>
                </article>
                <article class="testimonio-card">
                    <div class="testimonio-card__avatar" aria-hidden="true">LP</div>
                    <p class="testimonio-card__quote">“Las instalaciones y la variedad de disciplinas me ayudan a mantenerme activa y disfrutar cada sesión.”</p>
                    <h3 class="testimonio-card__name">Laura Pérez</h3>
                    <p class="testimonio-card__role">Miembro del club</p>
                </article>
            </div>
        </section>
        <!-- 4. SECCIÓN PLANES -->
        <section class="planes" id="planes">

            <div class="planes__header">
                <h2 class="planes__title">ELIGE TU PLAN IDEAL</h2>
                <p class="planes__description">Tenemos diferentes alternativas para que puedas disfrutar del deporte a tu ritmo.</p>
            </div>

            <div class="planes__grid">

                @forelse ($plans as $plan)
                <article class="plan-card {{ $loop->iteration === 2 ? 'plan-card--featured' : '' }}">
                    @if ($loop->iteration === 2)<span class="plan-card__badge">Destacado</span>@endif
                    <h3 class="plan-card__title">{{ $plan->name }}</h3>
                    <p class="plan-card__price">${{ number_format((float) $plan->price, 0, ',', '.') }} <span>/ mes</span></p>
                    <p class="plan-card__description">{{ $plan->description }}</p>
                    <a href="{{ route('mi-membresia.create') }}" class="plan-card__button">Elegir plan</a>
                </article>
                @empty
                <p>Pronto publicaremos nuestros planes.</p>
                @endforelse

            </div>
        </section>

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

    </main>

    <!-- 6. FOOTER -->
    <footer class="footer">

        <div class="footer__container">

            <!-- MARCA -->
            <div class="footer__brand">
                <a href="#inicio" class="footer__logo">
                    <img src="{{ asset('assets/img/logo.png') }}" alt="Sport Club Logo" class="footer__logo-image">
                </a>
                <p class="footer__description">Más que un club, una comunidad donde el deporte y la pasión nos unen.</p>
            </div>

            <!-- ENLACES -->
            <div class="footer__links">
                <h3 class="footer__title">Enlaces rápidos</h3>

                <ul class="footer__menu">
                    <li class="footer__item"><a href="#inicio" class="footer__link">Inicio</a></li>
                    <li class="footer__item"><a href="#nosotros" class="footer__link">Nosotros</a></li>
                    <li class="footer__item"><a href="#disciplinas" class="footer__link">Disciplinas</a></li>
                    <li class="footer__item"><a href="#planes" class="footer__link">Planes</a></li>
                    <li class="footer__item"><a href="#contacto" class="footer__link">Contacto</a></li>
                </ul>
            </div>

            <!-- CONTACTO -->
            <div class="footer__contact">
                <h3 class="footer__title">Contáctanos</h3>
                <p class="footer__phone">WhatsApp: +57 321 786 8266</p>
                <div class="footer__socials" aria-label="Redes sociales">
                    <a href="https://www.instagram.com/" class="footer__social-link" aria-label="Instagram" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-instagram" aria-hidden="true"></i></a>
                    <a href="https://www.facebook.com/" class="footer__social-link" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-facebook-f" aria-hidden="true"></i></a>
                    <a href="https://www.tiktok.com/" class="footer__social-link" aria-label="TikTok" target="_blank" rel="noopener noreferrer"><i class="fa-brands fa-tiktok" aria-hidden="true"></i></a>
                </div>
                <a href="https://wa.me/573217868266" class="footer__contact-link" target="_blank" rel="noopener noreferrer">Envíanos un mensaje</a>
            </div>

        </div>

        <!-- DERECHOS DE AUTOR -->
        <div class="footer__bottom">
            <p class="footer__copyright">&copy; 2026 Sport Club. Todos los derechos reservados.</p>
        </div>

    </footer>
</body>
</html>
