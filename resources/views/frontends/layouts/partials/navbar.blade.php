<nav class="navbar navbar-expand-lg bg-body-tertiary">
    <div class="container">
        <a class="navbar-brand" href="#"><img src="{{asset('frontends/Assets/Logo.png')}}" alt="Logo"></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup"
            aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            @if (Route::has('login'))
                @auth
                    <div class="navbar-nav">
                        <a class="nav-link" aria-current="page" href="{{ url('/') }}">Beranda</a>
                        <a class="nav-link" href="{{route('products.page')}}">Produk</a>
                        <a class="nav-link" href="#"><i class="fa-solid fa-cart-shopping text-success"></i></a>
                        <div class="vr"></div>
                        <div class="nav-item dropdown ps-3">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                                aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('logout') }}"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Keluar
                                        Akun</a></li>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </ul>
                        </div>
                    </div>

                @else
                    <div class="navbar-nav">
                        <a class="nav-link" aria-current="page" href="{{url('/')}}">Beranda</a>
                        <a class="nav-link" href="{{route('products.page')}}">Produk</a>
                        <a class="btn btn-outline-success" href="{{url('/login')}}">Login</a>
                    </div>
                @endauth
            @endif
        </div>
    </div>
</nav>
