<div class="relative">
    <img alt="Warehouse with green wall" class="w-full h-screen object-cover" height="1080"
        src="{{ asset('assets/for-landing-page/bzLhe8CI0hRdA64R8VyniRzOFEJKleldNB72Lotbgb8lut3TA.jpg') }}" width="1920">
    <!-- <img alt="Warehouse with green wall" class="w-full h-screen object-cover" height="1080"
        src="assets/1c26bea18a426890aaabc01e143bd175.jpeg" width="1920"> -->
    <div class="absolute top-5 z-10 w-full ">
        <div class="flex justify-between w-10/12 mx-auto  items-center ">
            <img alt="Company Logo" src="{{ asset('assets/for-landing-page/logo.png') }}" width="100" />
            <div class="flex items-center space-x-4 text-white font-bold ">
                <i class="fas fa-phone-alt"></i>
                <span>(022) 987 654</span>
                <span>|</span>
                <div class="flex items-center gap-4">
                    <img src="https://flagsapi.com/ID/shiny/24.png">
                    <span>
                        <button
                            class="outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300">EN</button>
                        |
                        <button
                            class="outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300 transition-all duration-300">ID</button>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div
        class="absolute inset-0  bg-black bg-opacity-50 flex flex-col items-center justify-center text-center text-white gap-10">

        <h2 class="text-sm uppercase tracking-widest mb-2 poppins-normal">Shared Space in Your Town</h2>
        <h1 class="text-4xl md:text-6xl font-bold mb-4 vollkorn-700">
            Find Industrial Estate and <span class="underline underline-offset-8">Get
                <br>
                Your Dream</span> Space
        </h1>
        <div class="shadow-lg rounded-lg w-10/12 mx-auto search-section">
            <div class="flex w-4/6 min-w-screen-sm max-w-screen-lg bg-green-700 rounded-t-lg">
                <button
                    class="tab flex flex-row items-center gap-2 hover:bg-white focus:bg-white outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300 group rounded-tl-lg"
                    data-tab="factory">
                    <img src="{{ asset('assets/for-landing-page/1.png') }}" width="24">
                    <span class="text-white text-sm group-hover:text-black group-focus:text-black">
                        Factory
                    </span>
                </button>
                <button
                    class="tab flex flex-row items-center gap-2 hover:bg-white focus:bg-white outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300 group"
                    data-tab="industial-land">
                    <img src="{{ asset('assets/for-landing-page/2.png') }}" width="24">
                    <span class="text-white text-sm group-hover:text-black group-focus:text-black">
                        Industrial Land
                    </span>
                </button>
                <button
                    class="tab flex flex-row items-center gap-2 hover:bg-white focus:bg-white outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300 group"
                    data-tab="warehouse">
                    <img src="{{ asset('assets/for-landing-page/3.png') }}" width="24">
                    <span class="text-white text-sm group-hover:text-black group-focus:text-black">
                        Warehouse
                    </span>
                </button>
                <button
                    class="tab flex flex-row items-center gap-2 hover:bg-white focus:bg-white outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300 group"
                    data-tab="container-yard">
                    <img src="{{ asset('assets/for-landing-page/4.png') }}" width="24">
                    <span class="text-white text-sm group-hover:text-black group-focus:text-black">
                        Container Yard
                    </span>
                </button>
                <button
                    class="tab flex flex-row items-center gap-2 hover:bg-white focus:bg-white outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300 group hidden"
                    data-tab="business-center">
                    <img src="{{ asset('assets/for-landing-page/5.png') }}" width="24">
                    <span class="text-white text-sm group-hover:text-black group-focus:text-black">
                        Business Center
                    </span>
                </button>
            </div>

            <form action="{{ url('search-result') }}" method="get" data-filter="search">
                <input type="hidden" name="category" value="">
                <input type="hidden" name="zone" value="">
                <!-- Tab Content -->
                <!-- Factory -->
                <div
                    class="tab-content flex flex-wrap items-end w-full gap-6  py-4 px-2 bg-white rounded-b-lg  rounded-r-lg items-center">
                    <div class="flex items-center mb-4 md:mb-0 w-1/4" data-section="selectpicker">
                        <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                        <select
                            class="selectpicker border border-gray-300 rounded-lg p-2 w-full outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300 rounded-md">
                            <option>Select Zone</option>
                            <option value="All">Any Area</option>
                            @foreach ($zoning as $zone)
                                <option value="{{ $zone['id'] }}">{{ $zone['zone_name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start land-area-slider">
                        <label class="font-bold text-sm text-green-700" for="land-area">Land area</label>
                        <div style="display: flex; gap: 1rem;">
                            <input type="hidden" name="land-area-range" value="">
                            <div id="land-area-range" class="range-slider" style="min-width:100px"></div>
                            <span class="text-xs text-green-700" id="land-area-range-value">0 m² - 3200 m²</span>
                        </div>
                    </div>
                    <div class="flex flex-col mb-4 md:mb-0  gap-2 items-start building-area-slider">
                        <label class="font-bold text-sm text-green-700" for="land-area">Building area</label>
                        <div style="display: flex; gap: 1rem;">
                            <input type="hidden" name="building-area-range" value="">
                            <div id="building-area-range" class="range-slider" style="min-width:100px"></div>
                            <span class="text-xs text-green-700" id="building-area-range-value">0 m² - 3200 m²</span>
                        </div>
                    </div>
                    <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                        <label class="font-bold text-sm text-green-700" for="land-area">Status</label>
                        <div class="flex items-center">
                            <input class="mr-1" id="bounded" name="type" type="radio" value="Bounded">
                            <label class="mr-4 text-xs text-green-700" for="bounded">Bounded</label>
                            <input class="mr-1" id="general" name="type" type="radio" value="general">
                            <label for="general" class="text-xs text-green-700">General</label>
                        </div>
                    </div>

                    <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-800 focus:bg-green-800 outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300">
                        <i class="fas fa-search mr-2"></i>
                        Search
                    </button>
                </div>
            </form>

            <!-- Tab Content -->
            <!-- Industrial Land -->
            <div class="tab-content flex flex-wrap items-end w-full gap-6  py-4 px-2 bg-white rounded-b-lg  rounded-r-lg items-center hidden"
                data-tab-content="industial-land">
                <div class="flex items-center mb-4 md:mb-0  w-5/12">
                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                    <select
                        class="selectpicker border border-gray-300 rounded-lg p-2 w-full outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300 rounded-md">
                        <option>Select Zone</option>
                        <option value="1">Zona 1</option>
                        <option value="2">Zona 2</option>
                        <option value="3">Zona 3</option>
                        <option value="4">Zona 4</option>
                    </select>
                </div>
                <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                    <label class="font-bold text-sm text-green-700" for="land-area">Land area</label>
                    <div style="display: flex; gap: 1rem;">
                        <div id="land-area-range-warehouse" class="range-slider" style="min-width:100px"></div>
                        <span class="text-xs text-green-700" id="land-area-range-value">0 m² - 3200 m²</span>
                    </div>
                </div>
                <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                    <label class="font-bold text-sm text-green-700" for="land-area">Status</label>
                    <div class="flex items-center">
                        <input class="mr-1" id="bonded" name="status" type="radio" value="bonded">
                        <label class="mr-4 text-xs text-green-700" for="bonded">Bonded</label>
                        <input class="mr-1" id="general" name="status" type="radio" value="general">
                        <label for="general" class="text-xs text-green-700">General</label>
                    </div>
                </div>


                <button
                    class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-800 focus:bg-green-800 outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
            </div>

            <!-- Tab Content -->
            <!-- Warehouse -->
            <div class="tab-content flex flex-wrap items-end w-full gap-6  py-4 px-2 bg-white rounded-b-lg  rounded-r-lg items-center hidden"
                data-tab-content="warehouse">
                <div class="flex items-center mb-4 md:mb-0 w-1/4">
                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                    <select
                        class="selectpicker border border-gray-300 rounded-lg p-2 w-full outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300 rounded-md">
                        <option>Select Zone</option>
                        <option value="1">Zona 1</option>
                        <option value="2">Zona 2</option>
                        <option value="3">Zona 3</option>
                        <option value="4">Zona 4</option>
                    </select>
                </div>
                <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                    <label class="font-bold text-sm text-green-700" for="land-area">Land area</label>
                    <div style="display: flex; gap: 1rem;">
                        <div id="land-area-range-factory" class="range-slider" style="min-width:100px"></div>
                        <span class="text-xs text-green-700" id="land-area-range-value">0 m² - 3200 m²</span>
                    </div>
                </div>
                <div class="flex flex-col mb-4 md:mb-0  gap-2 items-start">
                    <label class="font-bold text-sm text-green-700" for="land-area">Building area</label>
                    <div style="display: flex; gap: 1rem;">
                        <div id="building-area-range-factory" class="range-slider" style="min-width:100px"></div>
                        <span class="text-xs text-green-700" id="building-area-range-value">0 m² - 3200 m²</span>
                    </div>
                </div>
                <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                    <label class="font-bold text-sm text-green-700" for="land-area">Status</label>
                    <div class="flex items-center">
                        <input class="mr-1" id="bonded" name="status" type="radio" value="bonded">
                        <label class="mr-4 text-xs text-green-700" for="bonded">Bonded</label>
                        <input class="mr-1" id="general" name="status" type="radio" value="general">
                        <label for="general" class="text-xs text-green-700">General</label>
                    </div>
                </div>


                <button
                    class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-800 focus:bg-green-800 outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
            </div>

            <!-- Tab Content -->
            <!-- Container Yard -->
            <div class="tab-content flex flex-wrap items-end w-full gap-6  py-4 px-2 bg-white rounded-b-lg  rounded-r-lg items-center hidden"
                data-tab-content="container-yard">
                <div class="flex items-center mb-4 md:mb-0 w-5/12">
                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                    <select
                        class="selectpicker border border-gray-300 rounded-lg p-2 w-full outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300 rounded-md">
                        <option>Select Zone</option>
                        <option value="1">Zona 1</option>
                        <option value="2">Zona 2</option>
                        <option value="3">Zona 3</option>
                        <option value="4">Zona 4</option>
                    </select>
                </div>
                <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                    <label class="font-bold text-sm text-green-700" for="land-area">Land area</label>
                    <div style="display: flex; gap: 1rem;">
                        <div id="land-area-range-warehouse" class="range-slider" style="min-width:100px"></div>
                        <span class="text-xs text-green-700" id="land-area-range-value">0 m² - 3200 m²</span>
                    </div>
                </div>
                <div class="flex flex-col mb-4 md:mb-0 gap-2 items-start">
                    <label class="font-bold text-sm text-green-700" for="land-area">Status</label>
                    <div class="flex items-center">
                        <input class="mr-1" id="bonded" name="status" type="radio" value="bonded">
                        <label class="mr-4 text-xs text-green-700" for="bonded">Bonded</label>
                        <input class="mr-1" id="general" name="status" type="radio" value="general">
                        <label for="general" class="text-xs text-green-700">General</label>
                    </div>
                </div>


                <button
                    class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-800 focus:bg-green-800 outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300">
                    <i class="fas fa-search mr-2"></i>
                    Search
                </button>
            </div>
        </div>
    </div>
</div>
