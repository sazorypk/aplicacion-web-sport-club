<footer class="footer">
    <div class="footer__container">
        <div><a href="{{ route('inicio') }}"><img class="footer__logo-image" src="{{ asset('assets/img/logo.png') }}" alt="Sport Club"></a><p class="footer__description">Más que un club, una comunidad donde el deporte y la pasión nos unen.</p></div>
        <div><h2 class="footer__title">Explora Sport Club</h2><ul class="footer__menu"><li class="footer__item"><a class="footer__link" href="{{ route('inicio') }}#nosotros">Nosotros</a></li><li class="footer__item"><a class="footer__link" href="{{ route('disciplinas') }}">Disciplinas</a></li><li class="footer__item"><a class="footer__link" href="{{ route('planes') }}">Planes</a></li><li class="footer__item"><a class="footer__link" href="{{ route('sedes') }}">Sedes y horarios</a></li><li class="footer__item"><a class="footer__link" href="{{ route('contacto') }}">Contacto</a></li></ul></div>
        <div><h2 class="footer__title">Hablemos</h2><p class="footer__phone">WhatsApp: <a class="footer__contact-link" href="https://wa.me/573217868266" target="_blank" rel="noopener noreferrer" aria-label="Abrir WhatsApp al +57 321 786 8266">+57 321 786 8266</a></p></div>
    </div>
    <div class="footer__bottom"><p class="footer__copyright">&copy; {{ date('Y') }} Sport Club. Todos los derechos reservados.</p></div>
</footer>
