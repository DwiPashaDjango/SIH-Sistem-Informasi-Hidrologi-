<nav class="navbar navbar-expand-lg" style="">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('home')}}">Tinggi Muka Air</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('guest.pos.crh')}}">Curah Hujan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('guest.pos.klimatologi')}}">Klimatologi</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="{{route('guest.pos.quality')}}">Uji Kualitas Air</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Telemetri
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{route('telementri.tma')}}">Tinggi Muka Air</a></li>
                        <li><a class="dropdown-item" href="{{route('telementri.crh')}}">Curah Hujan</a></li>
                    </ul>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0 mr-5" style="margin-right: 30px">
                @guest
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{route('login')}}">Login</a>
                    </li>
                @endguest
                @auth
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="{{route('dashboard')}}">Dashboard</a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
