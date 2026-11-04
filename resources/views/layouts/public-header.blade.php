<header class="public-header">
    <nav class="public-header__nav" aria-label="Navegación principal">
        <a href="{{ route('inicio') }}" class="public-header__logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Sport Club" class="public-header__logo-image">
        </a>
        <ul class="public-header__menu" id="landing-menu">
            <li><a href="{{ route('inicio') }}" class="public-header__link">Inicio</a></li>
            <li><a href="{{ route('nosotros') }}" class="public-header__link">Nosotros</a></li>
            <li><a href="{{ route('disciplinas') }}" class="public-header__link">Disciplinas</a></li>
            <li><a href="{{ route('planes') }}" class="public-header__link">Planes</a></li>
            <li><a href="{{ route('sedes') }}" class="public-header__link">Sedes y horarios</a></li>
            <li><a href="{{ route('contacto') }}" class="public-header__link">Contacto</a></li>
        </ul>
        <div class="public-header__actions">
            <a href="{{ route('login') }}" class="public-header__link">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="public-header__button">Únete ahora</a>
        </div>
    </nav>
</header>
