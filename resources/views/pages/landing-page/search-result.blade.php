@extends('layouts-landing-page.app')

@section('title', 'Search.... ')

@section('content')
    <!-- Section 1 -->
    <div class="container mx-auto mt-10 px-20">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            {{-- Card --}}
            {{-- <div class="bg-white shadow-md  border overflow-hidden">
                <div class="relative">
                    <img alt="Aerial view of industrial buildings" class="w-full h-48 object-cover" height="300"
                        src="https://storage.googleapis.com/a1aa/image/MIaH5qWGnIoXGhV8zDfV3QZgbR0hRmneXFk2jU8mTuBCWU7TA.jpg"
                        width="400" />
                    <div
                        class="absolute top-4 left-4 bg-green-600 text-white px-2 py-1 rounded flex items-center text-sm font-bold">
                        <i class="fas fa-gamepad mr-1"></i> SEE VIRTUAL TOUR
                    </div>
                </div>
                <div class="p-4">
                    <h2 class="text-xl font-semibold">
                        1963 S
                    </h2>
                    <p class="text-gray-600">
                        Hills, CA 90210
                    </p>
                    <div class="flex items-center space-x-4 mt-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-ruler-combined text-green-600">
                            </i>
                            <span class="text-gray-600">
                                3200 m
                                <sup>
                                    2
                                </sup>
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-ruler-combined text-green-600">
                            </i>
                            <span class="text-gray-600">
                                3200 m
                                <sup>
                                    2
                                </sup>
                            </span>
                        </div>
                    </div>
                    <a href="{{ url('product/100') }}">
      <button class="bg-green-600 text-white py-2 px-4 rounded-md mt-4 w-full">
                        Detail
                     </button>
                 </a>
     </div>
            </div> --}}
            {{-- Card --}}
            {{-- <div class="bg-white shadow-md  border overflow-hidden">
                <div class="relative">
                    <img alt="Aerial view of industrial buildings" class="w-full h-48 object-cover" height="300"
                        src="https://storage.googleapis.com/a1aa/image/MIaH5qWGnIoXGhV8zDfV3QZgbR0hRmneXFk2jU8mTuBCWU7TA.jpg"
                        width="400" />
                    <div
                        class="absolute top-4 left-4 bg-green-600 text-white px-2 py-1 rounded flex items-center text-sm font-bold">
                        <i class="fas fa-gamepad mr-1"></i> SEE VIRTUAL TOUR
                    </div>
                </div>
                <div class="p-4">
                    <h2 class="text-xl font-semibold">
                        1963 S
                    </h2>
                    <p class="text-gray-600">
                        Hills, CA 90210
                    </p>
                    <div class="flex items-center space-x-4 mt-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-ruler-combined text-green-600">
                            </i>
                            <span class="text-gray-600">
                                3200 m
                                <sup>
                                    2
                                </sup>
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-ruler-combined text-green-600">
                            </i>
                            <span class="text-gray-600">
                                3200 m
                                <sup>
                                    2
                                </sup>
                            </span>
                        </div>
                    </div>
                    <button class="bg-green-600 text-white py-2 px-4 rounded-md mt-4 w-full">
                        Detail
                    </button>
                </div>
            </div> --}}
            {{-- Card --}}
            {{-- <div class="bg-white shadow-md  border overflow-hidden">
                <div class="relative">
                    <img alt="Aerial view of industrial buildings" class="w-full h-48 object-cover" height="300"
                        src="https://storage.googleapis.com/a1aa/image/MIaH5qWGnIoXGhV8zDfV3QZgbR0hRmneXFk2jU8mTuBCWU7TA.jpg"
                        width="400" />
                    <div
                        class="absolute top-4 left-4 bg-green-600 text-white px-2 py-1 rounded flex items-center text-sm font-bold">
                        <i class="fas fa-gamepad mr-1"></i> SEE VIRTUAL TOUR
                    </div>
                </div>
                <div class="p-4">
                    <h2 class="text-xl font-semibold">
                        1963 S
                    </h2>
                    <p class="text-gray-600">
                        Hills, CA 90210
                    </p>
                    <div class="flex items-center space-x-4 mt-4">
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-ruler-combined text-green-600">
                            </i>
                            <span class="text-gray-600">
                                3200 m
                                <sup>
                                    2
                                </sup>
                            </span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <i class="fas fa-ruler-combined text-green-600">
                            </i>
                            <span class="text-gray-600">
                                3200 m
                                <sup>
                                    2
                                </sup>
                            </span>
                        </div>
                    </div>
                    <button class="bg-green-600 text-white py-2 px-4 rounded-md mt-4 w-full">
                        Detail
                    </button>
                </div>
            </div> --}}
            {{-- End Card --}}
            @foreach ($result as $product)
                <div class="bg-white shadow-md  border overflow-hidden">
                    <div class="relative">
                        <img alt="Aerial view of industrial buildings" class="w-full h-48 object-cover" height="300"
                            {{-- src="https://storage.googleapis.com/a1aa/image/MIaH5qWGnIoXGhV8zDfV3QZgbR0hRmneXFk2jU8mTuBCWU7TA.jpg" --}} src="{{ asset('layouts/' . $product['layout']) }}" width="400" />
                        @if ($product['type_upload'] == 'link')
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
    </div>
@endsection
@section('js')
    <!-- jqueryUI -->
    <script src="https://code.jquery.com/ui/1.14.1/jquery-ui.js"></script>
    <script>
        $(document).ready(function() {
            $('.selectpicker').select2();

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

            const searchForm = $('form[data-form-type="search"]');

            function setHiddenInput(el) {
                const elemnt = $(el);
                const searchSelectorElement = `input[name=${elemnt.attr('name')}]`;
                // Jika element input dengan name ini sudah ada, maka set value terbaru nya
                if ($(searchSelectorElement).length) {
                    $(searchSelectorElement).val(elemnt.val());
                    // console.log("set value")
                } else { // Jika belum makan buat element terbaru nya
                    searchForm.append(elemnt);
                    // console.log("Append element baru")
                }
            }

            // Category
            $('a.category-menu').on('click', function() {
                const value = $(this).attr('data-category');
                updateQueryParam('category', value);
                $('input[type=hidden][name=category]').val(value);

                const existingQuery = getQueryParam();
                if (value === "industrial-land" || value === "container-yard") {
                    $('[data-category-input=building-area-range]').addClass('hidden');
                    if (existingQuery.hasOwnProperty('building-area')) {
                        removeQueryParam('building-area');
                    }
                } else {
                    $('[data-category-input=building-area-range]').removeClass('hidden');
                    if (!existingQuery.hasOwnProperty('building-area')) {
                        updateQueryParam('building-area', '0-3000');
                    }
                }
            });

            // Zone
            $('.selectpicker').on('change', function() {
                updateQueryParam('zone', $(this).val());
                const zoneInputElem = document.createElement('input');
                zoneInputElem.type = "hidden";
                zoneInputElem.name = $(this).attr('name');
                zoneInputElem.value = $(this).val();
                // console.log(zoneInputElem);
                setHiddenInput(zoneInputElem)
            });

            // Status
            $('input[name="status"]').on('change', function() {
                if ($(this).is(':checked')) {
                    updateQueryParam('status', $(this).val());
                }

                const statusInputHid = document.createElement('input');
                statusInputHid.type = "hidden";
                statusInputHid.name = $(this).attr('name');
                statusInputHid.value = $(this).val();
                setHiddenInput(statusInputHid);
            });

            const existingQueryParam = getQueryParam();
            if (existingQueryParam.hasOwnProperty('type')) {
                const statusValue = existingQueryParam['type'];
                $('input[name="type"]').each(function() {
                    if ($(this).val() == statusValue) {
                        $(this).prop('checked', true);
                    }
                });
            }

            // TRACKPAD
            $(function() {
                // Initialize all sliders with the common class .range-slider
                $(".range-slider").each(function() {
                    var slider = $(this);
                    var valueDisplayId = "#".concat(slider.attr('id'),
                        "-value"); // Dynamically generate the corresponding value display ID


                    const existingQuery = getQueryParam();
                    if (existingQuery.hasOwnProperty(slider.attr('id'))) {
                        let value = existingQuery[slider.attr('id')];
                        if (value) {
                            value = value.split('-');
                            $(valueDisplayId).text(String(value[0]).concat(" m²  - ", value[1],
                                " m²"));
                        }
                    }

                    // Slider
                    slider.slider({
                        range: true,
                        min: 0,
                        max: 3200,
                        values: [0, 3200],
                        slide: function(event, ui) {
                            const keyQuery = slider.attr('id');
                            const value = String(ui.values[0]).concat('-', ui.values[
                                1]);
                            updateQueryParam(keyQuery, value);

                            const sliderHidElem = document.createElement('input');
                            sliderHidElem.type = "hidden";
                            sliderHidElem.name = slider.attr('name');
                            sliderHidElem.value = value;
                            setHiddenInput(sliderHidElem);

                            // Update the corresponding value display based on the slider ID
                            $(valueDisplayId).text(ui.values[0] + " m²  - " + ui.values[
                                1] + " m²");
                        }
                    });
                });
            });
        })
    </script>

@endsection
