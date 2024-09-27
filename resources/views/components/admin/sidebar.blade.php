<div class="main-sidebar sidebar-style-2">
    <aside id="sidebar-wrapper">
        <div class="sidebar-brand">
            <a href="{{route('dashboard')}}">
                <img src="{{asset('logo.png')}}" width="200" alt="">
            </a>
        </div>
        <div class="sidebar-brand sidebar-brand-sm">
            <a href="{{route('dashboard')}}">
                <img src="{{asset('icon.ico')}}" width="50" alt="">
            </a>
        </div>
        <hr class="divide">
        <ul class="sidebar-menu">
            <li class="menu-header">Dashboard</li>
            @if(auth()->user()->hasAnyRole(['Admin', 'Pimpinan']))
                <li class="{{Request::routeIs('dashboard') ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('dashboard')}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
                </li>
                <li>
                    <a class="nav-link" href="{{route('home')}}"><i class="fas fa-home"></i> <span>Beranda</span></a>
                </li>
                <li class="menu-header">Data Master</li>
                <li class="{{Request::routeIs('subdas*') ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('subdas')}}"><i class="fas fa-list"></i> <span>Data Subdas</span></a>
                </li>
                <li class="dropdown {{request()->is('post*') ? 'active' : ''}}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-building"></i> <span>Data Pos</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{ (Request::routeIs('pos.tma*') || request()->is('post/create/tmas') || request()->is('post/*/edit/tmas')) ? 'active' : '' }}">
                            <a href="{{ route('pos.tma') }}">Tinggi Muka Air</a>
                        </li>
                        <li class="{{ (Request::routeIs('pos.crh*') || request()->is('post/create/crhs') || request()->is('post/*/edit/crhs')) ? 'active' : '' }}">
                            <a href="{{ route('pos.crh') }}">Curah Hujan</a>
                        </li>
                        <li class="{{ (Request::routeIs('pos.klimatologi*') || request()->is('post/create/klimatologis') || request()->is('post/*/edit/klimatologis')) ? 'active' : '' }}">
                            <a href="{{ route('pos.klimatologi') }}">Klimatologi</a>
                        </li>
                        {{-- <li class="{{Request::routeIs('pos.crh*') || request()->is('post/create/crhs')) ? 'active' : '' }}"><a class="nav-link" href="{{route('pos.crh')}}">Curah Hujan</a></li>
                        <li class="{{Request::routeIs('pos.klimatologi*') || request()->is('post/create/tmas')) ? 'active' : '' }}}}"><a href="{{route('pos.klimatologi')}}">Klimatologi</a></li> --}}
                    </ul>
                </li>
                <li class="{{Request::routeIs('water.quality*') ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('water.quality')}}"><i class="fas fa-air-freshener"></i> <span>Kualitas Air</span></a>
                </li>
                <li class="{{Request::routeIs('users*') ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('users')}}"><i class="fas fa-users"></i> <span>Data Users</span></a>
                </li>
                <li class="dropdown {{request()->is('batch*') ? 'active' : ''}}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-file-export"></i> <span>Batch Input</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{Request::routeIs('batch.batchTMA*') ? 'active' : ''}}"><a href="{{route('batch.batchTMA')}}">Tinggi Muka Air</a></li>
                        <li class="{{Request::routeIs('batch.batchCRH*') ? 'active' : ''}}"><a href="{{route('batch.batchCRH')}}">Curah Hujan</a></li>
                        <li class="{{Request::routeIs('batch.batchKlima*') ? 'active' : ''}}"><a href="{{route('batch.batchKlima')}}">Klimatologi</a></li>
                    </ul>
                </li>
                <li class="dropdown {{request()->is('history*') ? 'active' : ''}}">
                    <a href="#" class="nav-link has-dropdown"><i class="fas fa-history"></i> <span>History Pos</span></a>
                    <ul class="dropdown-menu">
                        <li class="{{Request::routeIs('tma.history') ? 'active' : ''}}"><a href="{{route('tma.history')}}">Tinggi Muka Air</a></li>
                        <li class="{{Request::routeIs('crh.history') ? 'active' : ''}}"><a href="{{route('crh.history')}}">Curah Hujan</a></li>
                        <li class="{{Request::routeIs('klimatologi.history') ? 'active' : ''}}"><a href="{{route('klimatologi.history')}}">Klimatologi</a></li>
                    </ul>
                </li>
                <li class="{{Request::routeIs('recently.pos') ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('recently.pos')}}"><i class="fas fa-trash-alt"></i> <span>Recently Pos</span></a>
                </li>
                <li class="{{Request::routeIs('admin.rekap.absen') ? 'active' : ''}}">
                    <a class="nav-link" href="{{route('admin.rekap.absen')}}"><i class="fas fa-file"></i> <span>Rekap Absensi</span></a>
                </li>
            @endif
            @if(auth()->user()->hasAnyRole(['User']))
                @if (Auth::user()->pos->jenis_id == 1)
                    <li class="{{ Request::routeIs('pos.crh.show') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('pos.crh.show', ['id' => Auth::user()->pos_id])}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{route('home')}}"><i class="fas fa-home"></i> <span>Beranda</span></a>
                    </li>
                    <li class="menu-header">Input Data</li>
                    <li class="{{Request::routeIs('pos.crh.createCRH') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('pos.crh.createCRH', ['id' => Auth::user()->pos_id])}}"><i class="fas fa-pen-square"></i> <span>Input Data</span></a>
                    </li>
                @elseif(Auth::user()->pos->jenis_id == 2)
                    <li class="{{ Request::routeIs('pos.tma.show') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('pos.tma.show', ['id' => Auth::user()->pos_id])}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{route('home')}}"><i class="fas fa-home"></i> <span>Beranda</span></a>
                    </li>
                    <li class="menu-header">Input Data</li>
                    <li class="{{Request::routeIs('pos.tma.addTMA') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('pos.tma.addTMA', ['id' => Auth::user()->pos_id])}}"><i class="fas fa-pen-square"></i> <span>Input Data</span></a>
                    </li>
                @else
                    <li class="{{ Request::routeIs('pos.klimatologi.show') ? 'active' : '' }}">
                        <a class="nav-link" href="{{route('pos.klimatologi.show', ['id' => Auth::user()->pos_id])}}"><i class="fas fa-fire"></i> <span>Dashboard</span></a>
                    </li>
                    <li>
                        <a class="nav-link" href="{{route('home')}}"><i class="fas fa-home"></i> <span>Beranda</span></a>
                    </li>
                    <li class="menu-header">Input Data</li>
                    <li class="{{Request::routeIs('pos.klimatologi.createKlimatologi') ? 'active' : ''}}">
                        <a class="nav-link" href="{{route('pos.klimatologi.createKlimatologi', ['id' => Auth::user()->pos_id])}}"><i class="fas fa-pen-square"></i> <span>Input Data</span></a>
                    </li>
                @endif
            @endif
            <li class="menu-header">Account</li>
            <li class="{{Request::routeIs('account*') ? 'active' : ''}}">
                <a class="nav-link" href="{{route('account')}}"><i class="fas fa-pencil-ruler"></i> <span>Change Password</span></a>
            </li>
        </ul>
    </aside>
</div>