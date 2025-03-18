@if (Request::is('login'))
    <style>
        a {
            text-decoration: none !important;
        }
    </style>
@endif
<header>
    <nav class="flex justify-between items-center p-4 border border-b w-full px-20">
        <div class="flex items-center">
            <a href="{{ url('/') }}">
                <img alt="KBN logo with a globe and the text 'KBN' in blue and yellow" class="h-12" height="50"
                    src="{{ asset('assets/for-landing-page/logo.png') }}" width="100" />
            </a>
        </div>
        </div>
        <div class="flex items-center text-green-600 relative">
            <div class="flex items-center space-x-4 font-bold text-sm">
                <i class="fas fa-phone-alt"></i>
                <span>{{ $section7['phone'] ?? '' }}</span>
                <span>|</span>
                <div class="flex items-center gap-4">
                    <img
                        src="https://flagsapi.com/{{ strtoupper(app()->getLocale() == 'en' ? 'US' : app()->getLocale()) }}/shiny/24.png">
                    <span>
                        <button onclick="changeLanguage('en')"
                            class="outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300">EN</button>
                        |
                        <button onclick="changeLanguage('id')"
                            class="outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300 transition-all duration-300">ID</button>
                    </span>
                </div>
            </div>
            <!-- <div class="dropdown relative z-20">
                    <i class="fas fa-chevron-down ml-1 cursor-pointer" onclick="toggleDropdown()"></i>
                    <div id="dropdown-content"
                        class="hidden dropdown-content absolute right-0 mt-2 py-2 w-48 bg-white rounded-lg shadow-xl">
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">English</a>
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-200">Indonesian</a>
                    </div>
                </div> -->
        </div>
    </nav>
    <div class="py-4 border-b relative z-10 px-20 text-sm">
        <nav class="space-x-4 ">
            <a data-category="factory" class="category-menu text-green-700 font-semibold cursor-pointer">Factory</a>
            <a data-category="warehouse" class="category-menu text-green-700 font-semibold cursor-pointer">Warehouse</a>
            <a data-category="industrial-land"
                class="category-menu text-green-700 font-semibold cursor-pointer">Industrial Land</a>
            <a data-category="container-yard"
                class="category-menu text-green-700 font-semibold cursor-pointer">Container Yard</a>
        </nav>
    </div>
    <form action="{{ url('/') }}" method="get" data-form-type="search">
        <!-- Factory -->
        <input type="hidden" name="category" value={{ request()->query('category') }}>
        <div class="tab-content flex flex-wrap items-end w-full gap-6  py-4 px-20 bg-white items-center shadow-md"
            data-tab-content="factory">
            <div class="flex items-center mb-4 md:mb-0 w-1/4">
                <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                <input type="hidden" name="zone">
                <select
                    class="selectpicker border border-gray-300 rounded-lg p-2 w-full outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300 rounded-md"
                    name="zone">
                    <option>Select Zone</option>
                    <option value="All">Any Area</option>
                    @foreach ($zones as $zone)
                        <option value="{{ $zone['id'] }}">{{ $zone['zone_name'] }}</option>
                    @endforeach
                    {{-- <option value="2">Zona 2</option>
                <option value="3">Zona 3</option>
                <option value="4">Zona 4</option> --}}
                </select>
            </div>
            <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start" data-category-input="land-area">
                <label class="font-bold text-sm text-green-700" for="land-area-range">Land area</label>
                <div style="display: flex; gap: 1rem;">
                    <div id="land-area-range" class="range-slider" style="min-width:100px"></div>
                    <span class="text-xs text-green-700" id="land-area-range-value">0 m² - 3200 m²</span>
                    <input type="hidden" name="land-area-range">
                </div>
            </div>
            <div class="flex flex-col mb-4 md:mb-0  gap-2 items-start" data-category-input="building-area">
                <label class="font-bold text-sm text-green-700" for="building-area-range">Building area</label>
                <div style="display: flex; gap: 1rem;">
                    <div id="building-area-range" class="range-slider" style="min-width:100px"></div>
                    <span class="text-xs text-green-700" id="building-area-range-value">0 m² - 3200 m²</span>
                    <input type="hidden" name="building-area-range">
                </div>
            </div>
            <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                <label class="font-bold text-sm text-green-700" for="land-area">Status</label>
                <div class="flex items-center">
                    <input class="mr-1" id="bounded" name="type" type="radio" value="bounded">
                    <label class="mr-4 text-xs text-green-700" for="bounded">Bounded</label>
                    <input class="mr-1" id="general" name="type" type="radio" value="general">
                    <label for="general" class="text-xs text-green-700">Non Bounded</label>
                </div>
            </div>
            <button
                class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-800 focus:bg-green-800 outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300">
                <i class="fas fa-search mr-2"></i>
                Search
            </button>
        </div>
    </form>
</header>

<script>
    function toggleDropdown() {
        document.getElementById("dropdown-content").style.display = document.getElementById("dropdown-content")
            .style.display === 'block' ? 'none' : 'block';
    }

    window.onclick = function(event) {
        if (!event.target.matches('.fa-chevron-down')) {
            var dropdowns = document.getElementsByClassName("dropdown-content");
            for (var i = 0; i < dropdowns.length; i++) {
                var openDropdown = dropdowns[i];
                if (openDropdown.style.display === 'block') {
                    openDropdown.style.display = 'none';
                }
            }
        }
    }


    // Mendapatkan semua query;
    function getQueryParam() {
        let search = window.location.search;
        let params = new URLSearchParams(search);
        let queryParams = {};
        params.forEach((value, key) => {
            queryParams[key] = value;
        });
        return queryParams;
    }

    //* Update query
    function updateQueryParam(key, value) {
        let url = new URL(window.location.href);
        url.searchParams.set(key, value);
        window.history.pushState({}, '', url);
    }

    function addQueryParam(key, value) {
        let url = new URL(window.location.href);
        url.searchParams.append(key, value);
        window.history.pushState({}, '', url);
    }

    function removeQueryParam(key) {
        let url = new URL(window.location.href);
        url.searchParams.delete(key);
        window.history.pushState({}, '', url);
    }
</script>
