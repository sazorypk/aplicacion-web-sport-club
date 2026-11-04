<header class="public-header">
    <nav class="public-header__nav" aria-label="Navegación principal">
        <a href="{{ route('inicio') }}" class="public-header__logo">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Sport Club" class="public-header__logo-image">
        </a>
        <ul class="public-header__menu public-header__desktop" id="landing-menu">
            <li><a href="{{ route('inicio') }}" class="public-header__link">Inicio</a></li>
            <li><a href="{{ route('disciplinas') }}" class="public-header__link">Disciplinas</a></li>
            <li><a href="{{ route('planes') }}" class="public-header__link">Planes</a></li>
            <li><a href="{{ route('sedes') }}" class="public-header__link">Sedes y horarios</a></li>
            <li><a href="{{ route('contacto') }}" class="public-header__link">Contacto</a></li>
        </ul>
        <div class="public-header__actions public-header__desktop">
            <a href="{{ route('login') }}" class="public-header__link">Iniciar sesión</a>
            <a href="{{ route('register') }}" class="public-header__button">Únete ahora</a>
        </div>
        <details class="public-header__mobile">
            <summary class="public-header__toggle" aria-label="Abrir menú de navegación"><span aria-hidden="true"></span><span aria-hidden="true"></span><span aria-hidden="true"></span></summary>
            <div class="public-header__mobile-panel">
                <ul class="public-header__mobile-menu">
                    <li><a href="{{ route('inicio') }}">Inicio</a></li>
                    <li><a href="{{ route('disciplinas') }}">Disciplinas</a></li>
                    <li><a href="{{ route('planes') }}">Planes</a></li>
                    <li><a href="{{ route('sedes') }}">Sedes y horarios</a></li>
                    <li><a href="{{ route('contacto') }}">Contacto</a></li>
                </ul>
                <div class="public-header__mobile-actions"><a href="{{ route('login') }}" class="public-header__link">Iniciar sesión</a><a href="{{ route('register') }}" class="public-header__button">Únete ahora</a></div>
            </div>
        </details>
    </nav>
</header>
