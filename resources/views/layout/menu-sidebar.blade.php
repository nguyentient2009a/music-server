<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3">Music Server</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- BÀI HÁT -->

    <li class="nav-item  @if (request()->is('song/*')) active @endif">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseSong" aria-expanded="true">
            <i class="fas fa-music"></i>
            <span>Bài Hát</span>
        </a>
        <div id="collapseSong" class="collapse @if (request()->is('song/*')) show @endif" aria-labelledby="headingUser"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Quản Lý Bài Hát:</h6>
                <a class="collapse-item @if ((request()->is('song/*') || request()->is('song/list')) && !request()->is('song/create')) active @endif"
                    href="{{ url('song') }}">Danh Sách</a>
                <a class="collapse-item @if (request()->is('song/create')) active @endif"
                    href="{{ url('song/create') }}">Tạo Mới</a>
            </div>
        </div>
    </li>
    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">


    <!-- ALBUM- Pages Collapse Menu -->
    <li class="nav-item @if (request()->is('album/*')) active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseAlbum" aria-expanded="true"
            aria-controls="collapseAlbum">
            <i class="fas fa-id-badge"></i>
            <span>Album</span>
        </a>
        <div id="collapseAlbum" class="collapse collapse @if (request()->is('album/*')) show @endif"
            aria-labelledby="headingAlbum" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Quản Lý Album</h6>
                <a class="collapse-item @if ((request()->is('album/*') || request()->is('album/list')) && !request()->is('album/create')) active @endif"
                    href="{{ url('album') }}">Danh Sách</a>
                <a class="collapse-item @if (request()->is('album/create')) active @endif"
                    href="{{ url('album/create') }}">Tạo
                    Mới</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- PLAYLIST- Pages Collapse Menu -->
    <li class="nav-item @if (request()->is('playlist/*')) active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapsePlaylist"
            aria-expanded="true" aria-controls="collapsePlaylist">
            <i class="fas fa-list"></i>
            <span>Playlist</span>
        </a>
        <div id="collapsePlaylist" class="collapse collapse @if (request()->is('playlist/*')) show @endif"
            aria-labelledby="headingPlaylist" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Quản ly playlist</h6>
                <a class="collapse-item @if ((request()->is('playlist/*') || request()->is('playlist/list')) && !request()->is('playlist/create')) active @endif"
                    href="{{ url('playlist') }}">Danh Sách</a>
                <a class="collapse-item @if (request()->is('playlist/create')) active @endif"
                    href="{{ url('playlist/create') }}">Tạo Mới</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- ARTIST- Pages Collapse Menu -->
    <li class="nav-item @if (request()->is('artist/*')) active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseArtist" aria-expanded="true"
            aria-controls="collapseArtist">
            <i class="far fa-smile"></i>
            <span>Nghệ Sĩ</span>
        </a>
        <div id="collapseArtist" class="collapse collapse @if (request()->is('artist/*')) show @endif"
            aria-labelledby="headingArtist" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Quản Lý Nghệ Sĩ</h6>
                <a class="collapse-item @if ((request()->is('artist/*') || request()->is('artist/list')) && !request()->is('artist/create')) active @endif"
                    href="{{ url('artist/') }}">Danh Sách</a>
                <a class="collapse-item @if (request()->is('artist/create')) active @endif"
                    href="{{ url('artist/create') }}">Tạo
                    Mới</a>
            </div>
        </div>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- GENRE- Pages Collapse Menu -->
    {{-- <li class="nav-item @if (request()->is('genre/*')) active @endif">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseGenre" aria-expanded="true"
            aria-controls="collapseGenre">
            <i class="fas fa-guitar"></i>
            <span>Thể Loại</span>
        </a>
        <div id="collapseGenre" class="collapse collapse @if (request()->is('genre/*')) show @endif"
            aria-labelledby="headingGenre" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Quản Lý Thể Loại</h6>
                <a class="collapse-item @if ((request()->is('genre/*') || request()->is('genre/list')) && !request()->is('genre/create')) active @endif"
                    href="{{ url('genre/') }}">Danh Sách</a>
    <a class="collapse-item @if (request()->is('genre/create')) active @endif" href="{{ url('genre/create') }}">Tạo
        Mới</a>
    </div>
    </div> --}}
    </li>

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle" id="sidebarToggle"></button>
    </div>
</ul>