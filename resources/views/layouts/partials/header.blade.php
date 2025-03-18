<header class="app-bar">
    <div class="toolbar">
        <div class="title">
            <img src="{{asset('img2.png')}}" alt="Logo KBN">
        </div>
        <div class="user-info mt-auto d-flex align-items-center">
            {{-- <nav class="breadcrumbs">
                @if(!empty($title))
                    <a>{{ $title }}</a>
                    <span>
                        <svg width="6" height="11" viewBox="0 0 6 11" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.25 1.41669L4.08061 4.71907C4.4658 5.16846 4.4658 5.83158 4.08061 6.28097L1.25 9.58335" stroke="#999999" stroke-width="2" stroke-linecap="round"/>
                        </svg>
                    </span>
                @endif
                <a href="#" class="active">{{ $subtitle }}</a>
            </nav> --}}
            <img src="{{ asset('user.png') }}" class="user-avatar" alt="User Image">
        </div>
    </div>
</header>
