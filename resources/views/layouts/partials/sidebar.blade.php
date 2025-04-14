<aside class="d-flex flex-column flex-shrink-0 text-bg-dark bg-white aside"
    style="background-color: #00954A !important;border-right:1px solid #ddd;height: 87vh;margin-top: 6%;border-radius: 25px;">
    <br><br>

    <ul class="nav nav-pills flex-column mb-auto" style="padding: 30px">
        <li class="nav-item">
            <a href="{{ url('superadmin/dashboard') }}"
                class="nav-link d-flex align-items-center {{ Request::is('superadmin/dashboard*') ? 'text-white active' : '' }}"
                aria-current="page">
                <img src="{{ asset('dashboard.png') }}" style="width: 20px; height: 20px; margin-right: 10px;" />
                <!-- Adjust size and spacing -->
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('superadmin/admin') }}"
                class="nav-link d-flex align-items-center {{ Request::is('superadmin/admin*') ? 'text-white active' : '' }}"
                aria-current="page">
                <img src="{{ asset('users.png') }}" style="width: 20px; height: 20px; margin-right: 10px;" />
                <!-- Adjust size and spacing -->
                <span>Administrator</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="#reportMenu" class="nav-link d-flex justify-content-between align-items-center"
                data-bs-toggle="collapse" aria-expanded="{{ Request::is('report*') ? 'true' : 'false' }}">
                <span>
                    <img src="{{ asset('Screen.png') }}" />
                    Management
                </span>
                <i class="bi bi-caret-down-fill"></i>
            </a>
            <div class="collapse {{ Request::is('superadmin*') ? 'show' : '' }}" id="reportMenu">

                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="nav-item submenu-item">
                        <a href="{{ url('superadmin/master/zonning') }}"
                            class="nav-link {{ Request::is('superadmin/master/zonning*') ? 'text-white active' : '' }}">
                            <img src="{{ asset('location-pin.png') }}" />
                            zoning
                        </a>
                    </li>

                    <li class="nav-item submenu-item">
                        <a href="{{ url('superadmin/master/category') }}"
                            class="nav-link {{ Request::is('superadmin/master/category*') ? 'text-white active' : '' }}">
                            <img src="{{ asset('box 2.png') }}" />
                            Category
                        </a>
                    </li>

                    <li class="nav-item submenu-item">
                        <a href="{{ url('superadmin/property') }}"
                            class="nav-link {{ Request::is('superadmin/property*') ? 'text-white active' : '' }}">
                            <img src="{{ asset('Category 2.png') }}" />
                            Property
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        {{-- <li class="nav-item">
            <a href="{{ url('superadmin/property') }}"
                class="nav-link d-flex align-items-center {{ Request::is('superadmin/property*') ? 'text-white active' : '' }}"
                aria-current="page">
                <img src="{{ asset('Category 2.png') }}" style="width: 20px; height: 20px; margin-right: 10px;" />
                <!-- Adjust size and spacing -->
                <span>Property</span>
            </a>
        </li> --}}
        <li class="nav-item">
            <a href="{{ url('superadmin/message') }}"
                class="nav-link d-flex align-items-center {{ Request::is('superadmin/message*') ? 'text-white active' : '' }}"
                aria-current="page">
                <img src="{{ asset('mail.png') }}" style="width: 20px; height: 20px; margin-right: 10px;" />
                <!-- Adjust size and spacing -->
                <span>Message</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('superadmin/log-activity') }}"
                class="nav-link d-flex align-items-center {{ Request::is('superadmin/log-activity*') ? 'text-white active' : '' }}"
                aria-current="page">
                <img src="{{ asset('approval.png') }}" style="width: 20px; height: 20px; margin-right: 10px;" />
                <!-- Adjust size and spacing -->
                <span>Actifity Logs</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ url('superadmin/landing-page') }}"
                class="nav-link d-flex align-items-center {{ Request::is('superadmin/landing-page*') ? 'text-white active' : '' }}"
                aria-current="page">
                <img src="{{ asset('laptop.png') }}" style="width: 20px; height: 20px; margin-right: 10px;" />
                <!-- Adjust size and spacing -->
                <span>Landing Page</span>
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ url('superadmin/panduan') }}"
                class="nav-link d-flex align-items-center {{ Request::is('superadmin/panduan*') ? 'text-white active' : '' }}"
                aria-current="page">
                <img src="{{ asset('assets/rtype.png') }}" style="width: 20px; height: 20px; margin-right: 10px;" />
                <!-- Adjust size and spacing -->
                <span>Panduan Website</span>
            </a>
        </li>
    </ul>
    <div class="user-info">
        {{-- <b>
            <h6> {{ Session::get('data.user.admin_name') }} </h6>
            <span>{{ Session::get('data.user.role.role_name') }}</span>
        </b> --}}

    </div>

    <a href="{{ url('logout') }}" class="nav-link-icon">
        <button type="button" class="btn btn-block btn-logout full-width">
            <img src="{{ asset('assets/logout.png') }}" />
            Logout
        </button>
    </a>
</aside>

<style>
    /* Gaya untuk Link Utama (nav-link) */
    .nav-link {
        color: white;
        /* Teks menjadi putih */
        text-decoration: none;
        display: flex;
        align-items: center;
        transition: color 0.3s ease;
    }

    .nav-link img {
        filter: brightness(0) invert(1);
        /* Membuat ikon menjadi putih */
        width: 20px;
        /* Sesuaikan ukuran ikon */
        height: 20px;
        margin-right: 10px;
        /* Memberikan jarak antara ikon dan teks */
    }

    /* Gaya untuk Icon Collapse */
    .nav-link .bi-caret-down-fill {
        color: white;
        /* Ikon caret menjadi putih */
    }

    /* Submenu Links */
    .submenu-item .nav-link {
        color: white;
        /* Teks submenu menjadi putih */
        padding-left: 30px;
        /* Memberikan padding untuk hierarki submenu */
    }

    .submenu-item .nav-link img {
        filter: brightness(0) invert(1);
        /* Ikon submenu menjadi putih */
        width: 18px;
        /* Ukuran lebih kecil dari ikon utama */
        margin-right: 10px;
    }

    /* Aktif Link */
    .nav-link.active,
    .nav-link.active img {
        color: #FFD700;
        /* Warna khusus untuk link aktif (misalnya emas) */
        filter: brightness(1);
        /* Ikon tetap terlihat pada state aktif */
    }
</style>
