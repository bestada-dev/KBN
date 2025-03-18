@extends('layouts-landing-page.app')

@section('title', 'Home ')

@section('content')

    <div class="relative">
        <img alt="Warehouse with green wall" class="w-full h-screen object-cover" height="1080"
            src="{{ file_exists(public_path('storage/' . $section1['photo'])) ? Storage::url($section1['photo']) : asset($section1['photo']) }}"
            width="1920">
        <!-- <img alt="Warehouse with green wall" class="w-full h-screen object-cover" height="1080"
                                                                                                                                                        src="assets/1c26bea18a426890aaabc01e143bd175.jpeg" width="1920"> -->
        <div class="absolute top-5 z-10 w-full ">
            <div class="flex justify-between w-10/12 mx-auto  items-center ">
                <img alt="Company Logo" src="{{ asset('assets/for-landing-page/logo.png') }}" width="100" />
                <div class="flex items-center space-x-4 text-white font-bold ">
                    <i class="fas fa-phone-alt"></i>

                    <span>{{ $section7['phone'] ?? '' }}</span>
                    <span>|</span>
                    <div class="flex items-center gap-4">
                        <!-- <img src="https://flagsapi.com/ID/shiny/24.png"> -->
                        <img
                            src="https://flagsapi.com/{{ strtoupper(app()->getLocale() == 'en' ? 'US' : app()->getLocale()) }}/shiny/24.png">
                        <span>
                            <button type="button" onclick="changeLanguage('en')"
                                class="outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300">EN</button>
                            |
                            <button type="button" onclick="changeLanguage('id')"
                                class="outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300 transition-all duration-300">ID</button>
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div
            class="absolute inset-0  bg-black bg-opacity-50 flex flex-col items-center justify-center text-center text-white gap-10">

            <h2 class="text-sm uppercase tracking-widest mb-2 poppins-normal">
                {{ $section1['title1_' . app()->getLocale()] ?? '' }}</h2>
            <h1 class="text-4xl md:text-6xl font-bold mb-2 vollkorn-700 w-3/4">{!! formattingTitle($section1['description1_' . app()->getLocale()]) ?? '' !!}
            </h1>
            <div class="flex gap-5">
                <h3>
                    <a class="text-3xl font-bold underline" href="https://my.mpskin.com/en/tour/ng3j4ppxp" target="_blank">KBN
                        Cakung</a>
                </h3>
                <h3>
                    <a class="text-3xl font-bold underline" 
                        href="https://my.mpskin.com/en/tour/gecn7zf927" 
                        target="_blank"
                    >KBN
                        Marunda</a>
                </h3>
            </div>
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

                <form action="" method="get" data-filter="search">
                    <input type="hidden" name="category" value="">
                    <input type="hidden" name="zone" value="">
                    <!-- Tab Content -->
                    <!-- Factory -->
                    <div
                        class="tab-content flex flex-wrap items-end w-full gap-6 py-4 px-2 bg-white rounded-b-lg  rounded-r-lg items-center">
                        <div class="flex items-center mb-4 md:mb-0 w-1/4" data-section="selectpicker">
                            <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                            <select
                                class="selectpicker text-black border border-gray-300 rounded-lg p-2 w-full outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  transition-all duration-300 rounded-md">
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
                                <label for="general" class="text-xs text-green-700">Non Bounded</label>
                            </div>
                        </div>

                        <button type="submit"
                            class="bg-green-600 text-white px-4 py-2 rounded-lg flex items-center hover:bg-green-800 focus:bg-green-800 outline-none focus:ring-2 hover:ring-2 focus:ring-green-300 hover:ring-green-300  p-3 transition-all duration-300">
                            <i class="fas fa-search mr-2"></i>
                            Search
                        </button>
                    </div>
                </form>
                {{-- OLD 
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
                 --}}
            </div>
        </div>
    </div>

    @if ($isSearch == false)
        <!-- Comfortable Place Section -->
        <section class="py-12 bg-white"
            style="border-top-left-radius: 80px;border-top-right-radius: 80px;margin-top: 0;z-index: 999;position: relative;"
            data-section="landing-page">
            <div class="container mx-auto py-4 w-11/12">
                <h2 class="text-3xl text-green-600 vollkorn-400">
                    {!! formattingTitle($section2['title1_' . app()->getLocale()], 'green-900') ?? '' !!}
                </h2>

                <div class="splide-gallery-wrap mt-8">
                    <div id="splide-gallery" class="splide" aria-label="Beautiful Images">
                        <div class="splide__track">
                            <ul class="splide__list">
                                @foreach ($product_property as $item)
                                    <li class="splide__slide relative">
                                        <!-- Card 1 -->
                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                            @php
                                                // Get the first attachment or null if none exists
                                                $attachment = $item->getAttachment->first();
                                            @endphp
                                            <img alt="View of skyscrapers from below with an airplane in the sky"
                                                class="w-full h-48 object-cover" height="400"
                                                src="/uploads/{{ $attachment->detail_photo }}"
                                                onerror="this.onerror=null; this.src='{{ asset('assets/no-images.jpg') }}';"
                                                width="600" />
                                            <div class="p-4 flex justify-between">
                                                <p class="text-gray-700">
                                                    {{ $item->type }}
                                                </p>
                                                <p class="text-green-600">
                                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                                                    {{ $item->block ?? '' }}
                                                </p>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach


                            </ul>
                        </div>
                    </div>
                </div>
                <!-- OLD
                                                    <div class="splide-gallery-wrap mt-8">
                                                        <div id="splide-gallery" class="splide" aria-label="Beautiful Images">
                                                            <div class="splide__track">
                                                                <ul class="splide__list">
                                                                    <li class="splide__slide relative">
                                                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                                                            <img alt="View of skyscrapers from below with an airplane in the sky"
                                                                                class="w-full h-48 object-cover" height="400"
                                                                                src="https://storage.googleapis.com/a1aa/image/hHuyL8WgPCYxEptrHY6JE0CFa9z6tDHy35P4tXK5yx3bhAeJA.jpg"
                                                                                width="600" />
                                                                            <div class="p-4 flex justify-between">
                                                                                <p class="text-gray-700">
                                                                                    19930
                                                                                </p>
                                                                                <p class="text-green-600">
                                                                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i> Hills, CA 90210
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="splide__slide relative">
                                                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                                                            <img alt="View of a residential building with multiple floors and balconies"
                                                                                class="w-full h-48 object-cover" height="400"
                                                                                src="https://storage.googleapis.com/a1aa/image/qsb1FeZjCq0TGC5nNvAB40ZQyonFlGBdw8KWjCFBpDP3CB8JA.jpg"
                                                                                width="600" />
                                                                            <div class="p-4 flex justify-between">
                                                                                <p class="text-gray-700">
                                                                                    19930
                                                                                </p>
                                                                                <p class="text-green-600">
                                                                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i> Hills, CA 90210
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="splide__slide relative">
                                                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                                                            <img alt="View of skyscrapers from below with an airplane in the sky"
                                                                                class="w-full h-48 object-cover" height="400"
                                                                                src="https://images.unsplash.com/photo-1682685797769-481b48222adf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                                                                                width="600" />
                                                                            <div class="p-4 flex justify-between">
                                                                                <p class="text-gray-700">
                                                                                    19930
                                                                                </p>
                                                                                <p class="text-green-600">
                                                                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i> Hills, CA 90210
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="splide__slide relative">
                                                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                                                            <img alt="View of skyscrapers from below with an airplane in the sky"
                                                                                class="w-full h-48 object-cover" height="400"
                                                                                src="https://storage.googleapis.com/a1aa/image/hHuyL8WgPCYxEptrHY6JE0CFa9z6tDHy35P4tXK5yx3bhAeJA.jpg"
                                                                                width="600" />
                                                                            <div class="p-4 flex justify-between">
                                                                                <p class="text-gray-700">
                                                                                    19930
                                                                                </p>
                                                                                <p class="text-green-600">
                                                                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i> Hills, CA 90210
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="splide__slide relative">
                                                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                                                            <img alt="View of a residential building with multiple floors and balconies"
                                                                                class="w-full h-48 object-cover" height="400"
                                                                                src="https://storage.googleapis.com/a1aa/image/qsb1FeZjCq0TGC5nNvAB40ZQyonFlGBdw8KWjCFBpDP3CB8JA.jpg"
                                                                                width="600" />
                                                                            <div class="p-4 flex justify-between">
                                                                                <p class="text-gray-700">
                                                                                    19930
                                                                                </p>
                                                                                <p class="text-green-600">
                                                                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i> Hills, CA 90210
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                    <li class="splide__slide relative">
                                                                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                                                                            <img alt="View of skyscrapers from below with an airplane in the sky"
                                                                                class="w-full h-48 object-cover" height="400"
                                                                                src="https://storage.googleapis.com/a1aa/image/hHuyL8WgPCYxEptrHY6JE0CFa9z6tDHy35P4tXK5yx3bhAeJA.jpg"
                                                                                width="600" />
                                                                            <div class="p-4 flex justify-between">
                                                                                <p class="text-gray-700">
                                                                                    19930
                                                                                </p>
                                                                                <p class="text-green-600">
                                                                                    <i class="fas fa-map-marker-alt text-green-700 mr-2"></i> Hills, CA 90210
                                                                                </p>
                                                                            </div>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div> -->
            </div>
        </section>

        <!-- Property with VR Section -->
        <section class="h-[520px] relative flex items-center" data-section="landing-page">
            <div class="bg-green-100 absolute top-0 left-0 h-full w-[85%] z-[-1]"></div>
            <div class="container mx-auto  px-6 py-8 w-11/12 flex items-center">
                <div class=" w-1/3">
                    <h2 class="text-3xl font-bolder vollkorn-500 text-green-900">
                        {!! formattingTitle($section3['title1_' . app()->getLocale()], 'green-900') ?? '' !!}
                    </h2>
                    <p class="mt-4 text-green-600">
                        {{ $section3['description1_' . app()->getLocale()] }}
                    </p>
                </div>
                <div class="parent absolute w-1/2 top-[2rem] bottom-0">
                    <button
                        class="bg-white border border-green-200 text-white h-[50px] w-[50px] rounded-full absolute bottom-20 z-40 right-[13rem] test bottom-[50%] hover:shadow-lg shadow-md"
                        id="btn-next-slide">
                        <i class="fas fa-arrow-right text-green-600 "></i>
                    </button>
                    <button
                        class="bg-white border border-green-200 text-white h-[50px] w-[50px] rounded-full absolute bottom-20 z-40 left-[-16rem] test bottom-[50%]  hover:shadow-lg shadow-md"
                        id="btn-prev-slide">
                        <i class="fas fa-arrow-left text-green-600 "></i>
                    </button>
                    <div class="card-carousel -mr-[20rem]">
                        @foreach ($product_property as $index => $item)
                            <div class="my-card {{ $loop->first ? 'active' : 'next' }}">
                                {{-- <p>
                                    {{ json_encode($item) }}
                                </p> --}}
                                <div class="relative w-60 bg-white overflow-hidden shadow-lg">
                                    <img alt="{{ $item->block }}"
                                        class="w-full h-[450px] object-cover"
                                        src="https://storage.googleapis.com/a1aa/image/x4PHqzKseU0jdyXrUnJAccUfkR86t1ke8gNfEU2nxAfxQQAfE.jpg"
                                        {{-- src="{{ file_exists(public_path('storage/' . $item['layout'])) ? Storage::url($item['layout']) : asset($item['layout']) }}" --}}
                                        >
                                        <div
                                            class="absolute top-4 left-4 bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center">
                                            <a 
                                            {{-- href="{{ $item["url"] }}"  --}}
                                            href="https://my.matterport.com/show/?m=X1vtwGXYedD" 
                                            class="inline-block">
                                                <i class="fas fa-vr-cardboard mr-2"></i>
                                                VIRTUAL TOUR
                                            </a>
                                        </div>
                                    <div
                                        class="absolute bottom-4 left-4 right-4 bg-white bg-opacity-75 p-2 flex justify-between items-center">
                                        <div>
                                            <h2 class="text-sm font-semibold">{{ $item->block }}</h2>
                                            <div class="flex items-center mt-2 text-gray-600">
                                                <i class="fas fa-ruler-combined mr-2"></i>
                                                <span class="text-sm">{{ $item->land_area }} m2</span>
                                                <i class="fas fa-ruler-combined ml-4 mr-2"></i>
                                                <span>{{ $item->building_area }} m2</span>
                                            </div>
                                        </div>
                                        <a class="bg-green-600 text-white p-2 rounded-full"
                                            href="{{ route('product.show', $item->id) }}">
                                            <i class="fas fa-arrow-right"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>

        <section class="pt-20  bg-white"
            style="border-top-left-radius: 80px;border-top-right-radius: 80px;margin-top: 0;z-index: 999;position: relative;"
            data-section="landing-page">
            <div class="mx-auto w-11/12">
                <div class="flex flex-col md:flex-row items-start gap-10">
                    <div class="w-full md:w-1/2  ring-2 ring-green-300" id="left-our-facilities">
                        <img src="{{ $benefit_title->image ?? '' }}" class="h-full w-full object-cover">
                    </div>
                    <div class="w-full md:w-1/2" id="right-our-facilities">
                        <!-- <h2 class="text-green-600 text-sm font-semibold">OUR FACILITIES</h2> -->
                        <h1 class="text-3xl font-bold text-gray-800 mt-2 vollkorn-700">
                            {{ $benefit_title->title ?? '' }}
                        </h1>
                        <p class="text-green-800 mt-6 text-sm">
                            {{ $benefit_title->sub_title ?? '' }}
                        </p>
                        <div class="mt-6 grid grid-cols-2 gap-2">
                            @foreach ($benefit_list as $item)
                                <div class="flex items-center">
                                    <i class="fas fa-check text-green-600 mr-2"></i>
                                    <span class="text-lg">{{ $item->content }}</span>
                                </div>
                            @endforeach
                        </div>
                        <button class="mt-6 bg-green-600 text-white py-2 px-8 hover:bg-green-700 font-bold">Detail</button>
                    </div>
                </div>
            </div>
        </section>

        {{-- OLD 
    <!-- Our Facilities Section -->
    <section class="pt-20  bg-white test"
        style="border-top-left-radius: 80px;border-top-right-radius: 80px;margin-top: 0;z-index: 999;position: relative;">
        <div class="mx-auto w-11/12">
            <div class="flex flex-col md:flex-row items-start gap-10">
                <div class="w-full md:w-1/2  ring-2 ring-green-300" id="left-our-facilities">
                    <img src="https://storage.googleapis.com/a1aa/image/qsb1FeZjCq0TGC5nNvAB40ZQyonFlGBdw8KWjCFBpDP3CB8JA.jpg"
                        class="h-full w-full object-cover">
                    <!-- <div class="relative test">
                                                                                                                  <video class="w-full h-auto" controls="">
                                                                                                                    <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                                                                                                                        <source src="https://www.w3schools.com/html/mov_bbb.ogg" type="video/ogg">
                                                                                                                    Your browser does not support the video tag.
                                                                                                                  </video>
                                                                                                                  <div class="absolute inset-0 flex items-center justify-center">
                                                                                                                    <button class="bg-green-600 text-white rounded-full p-4">
                                                                                                                      <i class="fas fa-play text-2xl"></i>
                                                                                                                    </button>
                                                                                                                  </div>
                                                                                                                </div> -->
                </div>
                <div class="w-full md:w-1/2" id="right-our-facilities">
                    <!-- <h2 class="text-green-600 text-sm font-semibold">OUR FACILITIES</h2> -->
                    <h1 class="text-3xl font-bold text-gray-800 mt-2 vollkorn-700">1965 S Crescent Warehouse</h1>
                    <p class="text-green-800 mt-6 text-sm">
                        Agent hen an unknown printer took a galley of type and scrambled it to make a type specimen book. It
                        has survived not only five centuries, but also the leap into electronic.
                    </p>
                    <div class="mt-6 grid grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            <span class="text-lg">Parking Space</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            <span class="text-lg">Parking Space</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            <span class="text-lg">Parking Space</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            <span class="text-lg">Parking Space</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            <span class="text-lg">Parking Space</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-check text-green-600 mr-2"></i>
                            <span class="text-lg">Parking Space</span>
                        </div>
                    </div>
                    <button class="mt-6 bg-green-600 text-white py-2 px-8 hover:bg-green-700 font-bold">Detail</button>
                </div>
            </div>
        </div>
    </section>
    --}}

        <!-- Our Popular Properties Section -->
        <section class="pt-16 pb-3 bg-white"
            style="border-top-left-radius: 80px;border-top-right-radius: 80px;margin-top: 0;z-index: 999;position: relative;"
            data-section="landing-page">
            <div class="container mx-auto py-4 w-11/12">
                <h2 class="text-2xl  poppins-semibold mb-2">
                    {!! formattingTitle($section4['title1_' . app()->getLocale()], 'green-900') ?? '' !!}
                </h2>

                <div class="splide-our-popular-properties-wrap mt-8 h-[390px]">
                    <div id="splide-our-popular-properties" class="splide" aria-label="Beautiful Images">
                        <div class="splide__track">
                            {{-- {{ json_encode($product_property) }} --}}
                            <ul class="splide__list">
                                @foreach ($product_property as $item)
                                    <li class="splide__slide relative">
                                        <!-- Card 1 -->
                                        {{-- {{ json_encode($item) }} --}}
                                        <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                            <div class="relative">
                                                <img alt="Aerial view of a large warehouse with trucks and greenery around"
                                                    class="w-full h-48 object-cover" height="200"
                                                    src="https://storage.googleapis.com/a1aa/image/x4PHqzKseU0jdyXrUnJAccUfkR86t1ke8gNfEU2nxAfxQQAfE.jpg"
                                                    {{-- src="{{ file_exists(public_path('storage/' . $item['layout'])) ? Storage::url($item['layout']) : asset($item['layout']) }}" --}}
                                                    width="400" />
                                                    {{-- <span>{{ json_encode($item["layout"]) }}</span> --}}
                                                    {{-- <span>{{ file_exists(public_path('storage/' . $item['layout'])) ? Storage::url($item['layout']) : asset($item['layout']) }}</span> --}}
                                                <span
                                                    class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                                    {{ $item->type }}
                                                </span>
                                            </div>
                                            <div class="p-4">
                                                <h2 class="text-xl font-semibold">
                                                    {{ $item->block ?? '' }}
                                                </h2>
                                                <p class="text-gray-600">
                                                    <i class="fas fa-map-marker-alt text-green-500">
                                                    </i>
                                                    {{ $item->type }}
                                                </p>
                                                <div
                                                    class="flex lg:flex-row flex-col justify-between items-center mt-4 text-gray-600">
                                                    <div class="flex items-center">
                                                        <i class="fas fa-ruler-combined text-green-500">
                                                        </i>
                                                        <span class="ml-2">
                                                            {{ $item->land_area }} m2
                                                        </span>
                                                    </div>
                                                    <div class="flex items-center">
                                                        <i class="fas fa-ruler-combined text-green-500">
                                                        </i>
                                                        <span class="ml-2">
                                                            {{ $item->building_area }} m2
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
                <!-- OLD
                                                     <div class="splide-our-popular-properties-wrap mt-8 h-[390px]">
                                                    <div id="splide-our-popular-properties" class="splide" aria-label="Beautiful Images">
                                                        <div class="splide__track">
                                                            <ul class="splide__list">
                                                                <li class="splide__slide relative">
                                        <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                        <div class="relative">
                                        <img alt="Aerial view of a large warehouse with trucks and greenery around" class="w-full h-48 object-cover" height="200" src="https://storage.googleapis.com/a1aa/image/x4PHqzKseU0jdyXrUnJAccUfkR86t1ke8gNfEU2nxAfxQQAfE.jpg" width="400"/>
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                         Cooling warehouse
                                        </span>
                                        </div>
                                        <div class="p-4">
                                        <h2 class="text-xl font-semibold">
                                         1963 S Crescent Warehouse
                                        </h2>
                                        <p class="text-gray-600">
                                         <i class="fas fa-map-marker-alt text-green-500">
                                         </i>
                                         Hills, CA 90210
                                        </p>
                                        <div class="flex justify-between items-center mt-4 text-gray-600">
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                        </div>
                                        </div>
                                        </div>
                                                                </li>
                                                                <li class="splide__slide relative">
                                                                     <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                        <div class="relative">
                                        <img alt="Aerial view of a large warehouse with trucks and greenery around" class="w-full h-48 object-cover" height="200" src="https://storage.googleapis.com/a1aa/image/hHuyL8WgPCYxEptrHY6JE0CFa9z6tDHy35P4tXK5yx3bhAeJA.jpg" width="400"/>
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                         Cooling warehouse
                                        </span>
                                        </div>
                                        <div class="p-4">
                                        <h2 class="text-xl font-semibold">
                                         1963 S Crescent Warehouse
                                        </h2>
                                        <p class="text-gray-600">
                                         <i class="fas fa-map-marker-alt text-green-500">
                                         </i>
                                         Hills, CA 90210
                                        </p>
                                        <div class="flex justify-between items-center mt-4 text-gray-600">
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                        </div>
                                        </div>
                                        </div>
                                                                </li>
                                                                <li class="splide__slide relative">
                                                                   <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                        <div class="relative">
                                        <img alt="Aerial view of a large warehouse with trucks and greenery around" class="w-full h-48 object-cover" height="200" src="https://images.unsplash.com/photo-1682685797769-481b48222adf?ixlib=rb-4.0.3&ixid=M3wxMjA3fDF8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80" width="400"/>
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                         Cooling warehouse
                                        </span>
                                        </div>
                                        <div class="p-4">
                                        <h2 class="text-xl font-semibold">
                                         1963 S Crescent Warehouse
                                        </h2>
                                        <p class="text-gray-600">
                                         <i class="fas fa-map-marker-alt text-green-500">
                                         </i>
                                         Hills, CA 90210
                                        </p>
                                        <div class="flex justify-between items-center mt-4 text-gray-600">
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                        </div>
                                        </div>
                                        </div>
                                                                </li>
                                                                <li class="splide__slide relative">
                                                                    <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                        <div class="relative">
                                        <img alt="Aerial view of a large warehouse with trucks and greenery around" class="w-full h-48 object-cover" height="200" src="https://storage.googleapis.com/a1aa/image/EMx6eeR7G2r7pkYHrl1LDVaOXqSquoQeciXy8wpM1scExDwnA.jpg" width="400"/>
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                         Cooling warehouse
                                        </span>
                                        </div>
                                        <div class="p-4">
                                        <h2 class="text-xl font-semibold">
                                         1963 S Crescent Warehouse
                                        </h2>
                                        <p class="text-gray-600">
                                         <i class="fas fa-map-marker-alt text-green-500">
                                         </i>
                                         Hills, CA 90210
                                        </p>
                                        <div class="flex justify-between items-center mt-4 text-gray-600">
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                        </div>
                                        </div>
                                        </div>
                                                                </li>
                                                                <li class="splide__slide relative">
                                                                     <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                        <div class="relative">
                                        <img alt="Aerial view of a large warehouse with trucks and greenery around" class="w-full h-48 object-cover" height="200" src="https://storage.googleapis.com/a1aa/image/EMx6eeR7G2r7pkYHrl1LDVaOXqSquoQeciXy8wpM1scExDwnA.jpg" width="400"/>
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                         Cooling warehouse
                                        </span>
                                        </div>
                                        <div class="p-4">
                                        <h2 class="text-xl font-semibold">
                                         1963 S Crescent Warehouse
                                        </h2>
                                        <p class="text-gray-600">
                                         <i class="fas fa-map-marker-alt text-green-500">
                                         </i>
                                         Hills, CA 90210
                                        </p>
                                        <div class="flex justify-between items-center mt-4 text-gray-600">
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                        </div>
                                        </div>
                                        </div>
                                                                </li>
                                                                <li class="splide__slide relative">
                                                                     <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                        <div class="relative">
                                        <img alt="Aerial view of a large warehouse with trucks and greenery around" class="w-full h-48 object-cover" height="200" src="https://storage.googleapis.com/a1aa/image/EMx6eeR7G2r7pkYHrl1LDVaOXqSquoQeciXy8wpM1scExDwnA.jpg" width="400"/>
                                        <span class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                         Cooling warehouse
                                        </span>
                                        </div>
                                        <div class="p-4">
                                        <h2 class="text-xl font-semibold">
                                         1963 S Crescent Warehouse
                                        </h2>
                                        <p class="text-gray-600">
                                         <i class="fas fa-map-marker-alt text-green-500">
                                         </i>
                                         Hills, CA 90210
                                        </p>
                                        <div class="flex justify-between items-center mt-4 text-gray-600">
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                         <div class="flex items-center">
                                          <i class="fas fa-ruler-combined text-green-500">
                                          </i>
                                          <span class="ml-2">
                                           3200 m²
                                          </span>
                                         </div>
                                        </div>
                                        </div>
                                        </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div> -->
            </div>
        </section>

        <!-- What We Offer Section -->
        <section class="pt-14" data-section="landing-page">
            <div class="container relative mx-auto  px-6 py-8 w-11/12">

                <h2 class="text-3xl text-green-600 vollkorn-400 relative z-10">
                    {!! formattingTitle($section5['title1_' . app()->getLocale()], 'green-900') ?? '' !!}
                </h2>
                <div class="bg-green-100 absolute w-full  h-64 left-0 top-0 z-0  rounded-lg"></div>
                <p class="w-3/5 text-gray-600 relative z-10 mt-2">
                    {{ $section5['description1_' . app()->getLocale()] ?? '' }}
                </p>

                <div class="relative z-10">


                    <div class="mt-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                        @foreach ($childBagian5 as $idx => $item)
                            <div
                                class="bg-white py-8 p-4 rounded shadow flex flex-col items-center rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300">
                                <div class="text-center flex flex-col items-center">
                                    <div
                                        style="width: 70px;height: 70px;background: #22c55e;border-radius: 100%;display: flex;justify-content: center;align-items: center;">
                                        <img src="{{ file_exists(public_path('storage/' . $item['image'])) ? Storage::url($item['image']) : asset($item['image']) }}"
                                            alt="">
                                    </div>
                                    <h3 class="mt-2 font-bold text-green-600">
                                        {{ $item['title1_' . app()->getLocale()] }}
                                    </h3>
                                </div>
                                <p class="mt-2 text-sm text-center">
                                    {{ $item['description1_' . app()->getLocale()] }}
                                </p>
                            </div>
                        @endforeach

                        <!-- OLD -->
                        <!-- <div class="bg-white py-8 p-4 rounded shadow flex flex-col items-center rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300">
                                            <div class="text-center flex flex-col items-center">
                                                <div style="width: 70px;height: 70px;background: #22c55e;border-radius: 100%;display: flex;justify-content: center;align-items: center;"> <img src="{{ asset('assets/for-landing-page/chat.png') }}" width="35"></div>
                                                <h3 class="mt-2 font-bold text-green-600">
                                                    Office Motion
                                                </h3>
                                            </div>
                                                <p class="mt-2 text-sm text-center">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                </p>
                                                </div>
                                                <div class="bg-white py-8 p-4 rounded shadow flex flex-col items-center rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300">
                                            <div class="text-center flex flex-col items-center">
                                                <div style="width: 70px;height: 70px;background: #22c55e;border-radius: 100%;display: flex;justify-content: center;align-items: center;"> <img src="{{ asset('assets/for-landing-page/lock.png') }}" width="35"></div>
                                                <h3 class="mt-2 font-bold text-green-600">
                                                    Office Motion
                                                </h3>
                                            </div>
                                                <p class="mt-2 text-sm text-center">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                </p>
                                                </div>
                                                <div class="bg-white py-8 p-4 rounded shadow flex flex-col items-center rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300">
                                            <div class="text-center flex flex-col items-center">
                                                <div style="width: 70px;height: 70px;background: #22c55e;border-radius: 100%;display: flex;justify-content: center;align-items: center;"> <img src="{{ asset('assets/for-landing-page/pellan.png') }}" width="35"></div>
                                                <h3 class="mt-2 font-bold text-green-600">
                                                    Office Motion
                                                </h3>
                                            </div>
                                                <p class="mt-2 text-sm text-center">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                </p>
                                                </div>
                                                <div class="bg-white py-8 p-4 rounded shadow flex flex-col items-center rounded-xl shadow-xl hover:shadow-2xl transition-all duration-300">
                                            <div class="text-center flex flex-col items-center">
                                                <div style="width: 70px;height: 70px;background: #22c55e;border-radius: 100%;display: flex;justify-content: center;align-items: center;"> <img src="{{ asset('assets/for-landing-page/bulet.png') }}" width="35"></div>
                                                <h3 class="mt-2 font-bold text-green-600">
                                                    Office Motion
                                                </h3>
                                            </div>
                                                <p class="mt-2 text-sm text-center">
                                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.
                                                </p>
                                                </div> -->
                    </div>
                </div>
            </div>
            <div></div>
            <div></div>
        </section>
    @else
        <section data-section="search-result">
            <div class="container mx-auto mt-10 px-20">
                {{-- Card --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach ($searchResult as $product)
                        <div class="bg-white shadow-md  border overflow-hidden">
                            <div class="relative">
                                <img alt="Aerial view of industrial buildings" class="w-full h-48 object-cover"
                                    height="300" {{-- src="https://storage.googleapis.com/a1aa/image/MIaH5qWGnIoXGhV8zDfV3QZgbR0hRmneXFk2jU8mTuBCWU7TA.jpg" --}}
                                    src="{{ asset('layouts/' . $product['layout']) }}" width="400" />
                                @if ($product['type_upload'] == 'link' && $product['url'] != null)
                                    <div
                                        class="absolute top-4 left-4 bg-green-600 text-white px-2 py-1 rounded flex items-center text-sm font-bold">
                                        <a href="{{ $product['url'] }}" target="_black">
                                            <i class="fas fa-gamepad mr-1"></i> SEE VIRTUAL TOUR
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h2 class="text-xl font-semibold">
                                    {{ $product['block'] }}
                                </h2>
                                <p class="text-gray-600">
                                    {{ $product['property_address'] }}
                                </p>
                                <div class="flex items-center space-x-4 mt-4">
                                    <div class="flex items-center space-x-2">
                                        <i class="fas fa-ruler-combined text-green-600">
                                        </i>
                                        <span class="text-gray-600">
                                            {{ $product['land_area'] }} m
                                            <sup>
                                                2
                                            </sup>
                                        </span>
                                    </div>
                                    @if (isset($product['building_area']))
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-ruler-combined text-green-600">
                                            </i>
                                            <span class="text-gray-600">
                                                {{ $product['building_area'] }} m
                                                <sup>
                                                    2
                                                </sup>
                                            </span>
                                        </div>
                                    @endif
                                </div>
                                <a href="{{ url('product') . '/' . $product['id'] }}" class="cursor-pointer">
                                    <button class="bg-green-600 text-white py-2 px-4 rounded-md mt-4 w-full cursor-pointer"
                                        data-btn-detail="{{ $product['id'] }}">
                                        Detail
                                    </button>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{-- End Card --}}
            </div>
        </section>
    @endif
@endsection
@section('js')
    <!-- jqueryUI -->
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {

            // Select picker on change
            $('.selectpicker').select2();
            $('.selectpicker').on('change', function() {
                $('input[name=zone]').val($(this).val())
            });
            try {
                // Setting Height Di Section 3 bagian Kiri
                const h = document.getElementById('right-our-facilities').scrollHeight * 1.2 + 'px';
                document.getElementById('left-our-facilities').style.cssText = `height:${h}`
            } catch (error) {
                console.log("Error", error);
            }

            // OLD
            // function creatHiddenInput(name) {
            //     const inputElement = document.createElement("input");
            //     inputElement.type = "hidden";
            //     inputElement.name = name;
            //     return inputElement;
            // }
            // const formSearch = $('[data-filter=search]');

            // // for initial render
            // const typeInputElement = creatHiddenInput("category");
            // $('.tab-content').each(function() {
            //     if (!$(this).hasClass("hidden")) {
            //         typeInputElement.value = $(this).attr('data-tab-content');
            //     }
            // });
            // formSearch.append(typeInputElement);

            // const contentSection = $('[data-tab-content]');
            // $('[data-tab]').each(function() {
            //     $(this).on('focus', function() {
            //         const sectionName = $(this).attr('data-tab');
            //         $('[data-tab-content]').each(function() {
            //             if ($(this).attr('data-tab-content') == sectionName) {
            //                 $(this).removeClass('hidden');
            //                 typeInputElement.value = sectionName;
            //             } else {
            //                 $(this).addClass('hidden');
            //             }
            //         })
            //     })
            // })

            /** Input Slider */
            // $(".range-slider").each(function() {
            //     var slider = $(this);
            //     var valueDisplayId = "#" + slider.attr('id') +
            //         "-value"; // Dynamically generate the corresponding value display ID
            //     slider.slider({
            //         range: true,
            //         min: 0,
            //         max: 3200,
            //         values: [0, 3200],
            //         slide: function(event, ui) {
            //             const inputName = slider.attr("data-name-input");
            //             let alreadyElement = $(formSearch).find(`[name=${inputName}]`);
            //             if (alreadyElement.length < 1) {
            //                 alreadyElement = creatHiddenInput(inputName);
            //                 alreadyElement.setAttribute('value',
            //                     `${ui.values[0]}-${ui.values[1]}`)
            //                 formSearch.append(alreadyElement);
            //             } else {
            //                 $(alreadyElement[0]).attr('value',
            //                     `${ui.values[0]}-${ui.values[1]}`)
            //             }
            //             // Update the corresponding value display based on the slider ID
            //             $(valueDisplayId).text(ui.values[0] + " m²  - " + ui.values[1] +
            //                 " m²");
            //         }
            //     });
            // });

            // Initialize all sliders with the common class .range-slider
            // $(".range-slider").each(function() {
            //     var slider = $(this);
            //     var valueDisplayId = "#" + slider.attr('id') +
            //     "-value"; // Dynamically generate the corresponding value display ID

            //     slider.slider({
            //         range: true,
            //         min: 0,
            //         max: 3200,
            //         values: [0, 3200],
            //         slide: function(event, ui) {
            //             // Update the corresponding value display based on the slider ID
            //             $(valueDisplayId).text(ui.values[0] + " m²  - " + ui.values[1] + " m²");
            //         }
            //     });
            // });

            // $('[data-section=landing-page]').hide();
        });
    </script>

    <script>
        if (document.getElementById('splide-gallery')) {
            var splide = new Splide('#splide-gallery', {
                type: 'slide',
                perPage: 3,
                perMove: 1,
                gap: '20px',
                padding: '4rem',
                pagination: false
                // width : 'min(1200px, 100% - 60px)',
                // // rewind     : true,
                // breakpoints: {
                //   992: {
                //     perPage: 2,
                //   }, 
                //   480: {
                //     perPage: 1,
                //     rewind : true,
                //   },
                // }
            });
            splide.mount();
        }



        if (document.getElementById('splide-our-popular-properties')) {
            var splide = new Splide('#splide-our-popular-properties ', {
                type: 'slide',
                perPage: 3,
                perMove: 1,
                // gap: '20px',
                // padding: '4rem',
                pagination: true
                // width : 'min(1200px, 100% - 60px)',
                // // rewind     : true,
                // breakpoints: {
                //   992: {
                //     perPage: 2,
                //   }, 
                //   480: {
                //     perPage: 1,
                //     rewind : true,
                //   },
                // }
            });
            splide.mount();
        }
    </script>


    <!-- SLIDESHOW VR -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            $num = $('.my-card').length;
            $even = $num / 2;
            $odd = ($num + 1) / 2;

            if ($num % 2 == 0) {
                $('.my-card:nth-child(' + $even + ')').addClass('active');
                $('.my-card:nth-child(' + $even + ')').prev().addClass('prev');
                $('.my-card:nth-child(' + $even + ')').next().addClass('next');
                $('.my-card:nth-child(' + $even + ')').next().next().addClass('next');
            } else {
                $('.my-card:nth-child(' + $odd + ')').addClass('active');
                $('.my-card:nth-child(' + $odd + ')').prev().addClass('prev');
                $('.my-card:nth-child(' + $odd + ')').next().addClass('next');
                $('.my-card:nth-child(' + $odd + ')').next().next().addClass('next');
            }

            const cards = document.querySelectorAll('.my-card');
            let currentIndex = 0;


            const updateCards = () => {
                cards?.forEach((card, index) => {
                    card.classList.remove('active', 'next', 'prev');
                    if (index === currentIndex) {
                        card.classList.add('active');
                    } else if (index === (currentIndex + 1) % cards.length) {
                        card.classList.add('next');
                    } else if (index === (currentIndex + 2) % cards.length) {
                        card.classList.add('next');
                    }
                    //  else if (index === (currentIndex - 1 + cards.length) % cards.length) {
                    //   card.classList.add('prev');
                    // }
                });
            };

            // Initialize the carousel
            updateCards();

            // const cards = document.querySelectorAll('.my-card');
            const carousel = document.querySelector('.card-carousel'); // Carousel container
            const nextButton = document.getElementById('btn-next-slide');
            const prevButton = document.getElementById('btn-prev-slide');

            let activeIndex = 0;

            const updateCardss = () => {
                cards.forEach((card, index) => {
                    card.classList.remove('active', 'prev', 'next');
                    // if (index === activeIndex) {
                    //   card.classList.add('active');
                    // } else if (index === activeIndex - 1 || (activeIndex === 0 && index === cards.length - 1)) {
                    //   card.classList.add('prev');
                    // } else if (index === activeIndex + 1 || (activeIndex === cards.length - 1 && index === 0)) {
                    //   card.classList.add('next');
                    // }

                    console.log(activeIndex, (cards.length - 1));
                    if (index === activeIndex) {
                        card.classList.add('active');
                    }
                    // else if (activeIndex <= (cards.length - 1)) {
                    if (activeIndex <= (cards.length - 1) && index === (activeIndex + 1) % cards
                        .length) {
                        card.classList.add('next');
                    } else if (activeIndex <= (cards.length - 3) && index === (activeIndex + 2) % cards
                        .length) {
                        card.classList.add('next');
                    }
                    // }
                });
            };

            const animateCarousel = (direction) => {
                const activeCard = document.querySelector('.active'); // Active card
                const slideWidth = activeCard.offsetWidth; // Width of active card

                if (direction === 'next') {
                    $(carousel).stop(false, true).animate({
                        left: `-=${slideWidth}` // Move carousel left for "next"
                    });
                } else if (direction === 'prev') {
                    $(carousel).stop(false, true).animate({
                        left: `+=${slideWidth}` // Move carousel right for "prev"
                    });
                }
            };

            nextButton.addEventListener('click', () => {
                if (activeIndex < (cards.length - 1)) {
                    activeIndex = (activeIndex + 1) % cards.length;
                    animateCarousel('next'); // Animate carousel for next
                    updateCardss();
                }
                console.log(activeIndex, 'OKE1');
            });

            prevButton.addEventListener('click', () => {
                if (activeIndex !== 0) {
                    activeIndex = (activeIndex - 1 + cards.length) % cards.length;
                    animateCarousel('prev'); // Animate carousel for prev
                    updateCardss();
                    console.log(activeIndex, 'OKE2');
                } else {
                    console.log(activeIndex, 'OKE');
                }
            });

            // updateCardss(); // Initial update to set classes
        });

        //     document.addEventListener('DOMContentLoaded', function () {
        //   const cards = document.querySelectorAll('.my-card');
        //   const nextButton = document.getElementById('btn-next-slide');
        //   const prevButton = document.getElementById('btn-prev-slide');

        //   let activeIndex = 0;

        //   const updateCards = () => {
        //     cards.forEach((card, index) => {
        //       card.classList.remove('active', 'prev', 'next');
        //       if (index === activeIndex) {
        //         card.classList.add('active');
        //       } else if (index === activeIndex - 1 || (activeIndex === 0 && index === cards.length - 1)) {
        //         card.classList.add('prev');
        //       } else if (index === activeIndex + 1 || (activeIndex === cards.length - 1 && index === 0)) {
        //         card.classList.add('next');
        //       }
        //     });
        //   };

        //   nextButton.addEventListener('click', () => {
        //     activeIndex = (activeIndex + 1) % cards.length;
        //     updateCards();
        //   });

        //   prevButton.addEventListener('click', () => {
        //     activeIndex = (activeIndex - 1 + cards.length) % cards.length;
        //     updateCards();
        //   });

        //   // updateCards(); // Initial update to set classes
        // });


        // $('.my-card').click(function () {
        //   $slide = $('.active').width();
        //   console.log($('.active').position().left);

        //   if ($(this).hasClass('next')) {
        //     $('.card-carousel').stop(false, true).animate({
        //       left: '-=' + $slide
        //     });
        //   } else if ($(this).hasClass('prev')) {
        //     $('.card-carousel').stop(false, true).animate({
        //       left: '+=' + $slide
        //     });
        //   }

        //   $(this).removeClass('prev next');
        //   $(this).siblings().removeClass('prev active next');

        //   $(this).addClass('active');
        //   $(this).prev().addClass('prev');
        //   $(this).next().addClass('next');
        //   $(this).next().next().addClass('next');
        // });
    </script>
    <script>
        $(document).ready(function() {
            // alert('ready')
            const formSearch = $('form[data-filter=search]');
            const doesntHaveBuilding = ['industial-land', 'container-yard'];
            // Button tab conten
            $('button.tab').on('click', function() {
                const valueTab = $(this).attr('data-tab');
                $('input[name=category]').val(valueTab);

                let activeClasses = 'bg-white ring-2 ring-green-300 text-black'

                $('button.tab').each(function() {
                    if ($(this).attr('data-tab') == valueTab) {
                        $(this).addClass(activeClasses);
                        $(this).find('span').removeClass('text-white');
                        $(this).find('span').addClass('text-black');
                    } else {
                        $(this).removeClass(activeClasses);
                        $(this).find('span').removeClass('text-black');
                        $(this).find('span').addClass('text-white');
                    }
                })

                if (doesntHaveBuilding.includes(valueTab)) {
                    $('.building-area-slider').addClass('hidden');
                    $('input[name=building-area-range]').val('');
                } else {
                    $('.building-area-slider').removeClass('hidden');
                }
            });

            // Initial slide
            $('.range-slider').each(function() {
                let slider = $(this);
                let valueDisplayId = "#".concat(slider.attr('id'), '-value');

                slider.slider({
                    range: true,
                    min: 0,
                    max: 3200,
                    values: [0, 3200],
                    slide: function(event, ui) {

                        //set to input elem
                        const inputName = slider.attr('id');
                        const inputElem = $(`input[name=${inputName}]`);
                        inputElem.val(`${ui.values[0]}-${ui.values[1]}`);

                        // update ui
                        $(valueDisplayId).text(String(ui.values[0]).concat(' m² - ', ui.values[
                            1], ' m²'))
                    }
                })
            })
        })
    </script>
@endsection
