@extends('layouts-landing-page.app')

@section('title', 'Product Detail ')

@section('content')
    <!-- Section 1 -->
    <div class="container mx-auto mt-5 px-20">
        <!-- Breadcrumb -->
        <div class="text-sm text-gray-600 mb-4">
            <a class="text-green-600" href="#">{{ $property["get_category"]["name"] }}</a> &gt; <span>{{ $property["get_zoning"]["zone_name"] }}</span>
        </div>
        <!-- Main Content -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Left Section -->
            <div class="relative">
                <img alt="Aerial view of a modern factory building" class="w-full max-h-[320px] h-auto object-cover"
                    height="100%"
                    src="{{ asset('detail_photos') . '/' . $property['get_attachment'][0]['detail_photo'] ?? '' }}"
                    height="100%"
                    src="{{ asset('detail_photos') . '/' . $property['get_attachment'][0]['detail_photo'] ?? '' }}"
                    width="100%" />
                <a class="absolute top-4 left-4 bg-green-600 text-white px-2 py-1 rounded flex items-center text-sm"
                    href="#virtual-tour">
                    <i class="fas fa-gamepad mr-1"></i> SEE VIRTUAL TOUR
                </a>
            </div>
            <!-- Right Section -->
            <div class="grid grid-cols-2 gap-4">
                @if (is_array($property['get_attachment']))
                    @php
                        $sliced =
                            count($property['get_attachment']) > 4
                                ? array_slice($property['get_attachment'], 1, 4)
                                : $property['get_attachment'];
                        $lastIndex = count($sliced) > 1 ? count($sliced) - 1 : 0;
                    @endphp
                    @foreach ($sliced as $index => $attachment)
                        @if ($index < 3)
                            <div class="relative">
                                <img alt="Interior view of a factory with machinery"
                                    class="w-full max-h-[150px] object-cover"
                                    src="{{ asset('detail_photos') . '/' . $attachment['detail_photo'] ?? '' }}"
                                    width="300" />
                                @if (count($sliced) < 3 && $index == $lastIndex)
                                    <button
                                        class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded"
                                        id="see-all-photos">
                                        See All Photos
                                    </button>
                                @endif
                            </div>
                        @elseif($index == 3 || $index)
                            <div class="relative">
                                <img alt="Aerial view of a factory complex surrounded by greenery"
                                    class="w-full max-h-[150px] h-auto object-cover"
                                    src="{{ asset('detail_photos') . '/' . $attachment['detail_photo'] ?? '' }} "
                                    width="100%" />
                                <button class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded"
                                    id="see-all-photos">
                                    See All Photos
                                </button>
                            </div>
                        @endif
                    @endforeach
                @endif
                {{-- <img alt="Aerial view of a factory complex surrounded by greenery"
                    class="w-full max-h-[150px] h-auto object-cover"
                    src="https://storage.googleapis.com/a1aa/image/poE3uy6YbhJTHlqrnfkTie0EF6z2nA9EudYmOlIGULzfTV3nA.jpg"
                    width="100%" />
                <img alt="Aerial view of a factory complex surrounded by greenery"
                    class="w-full max-h-[150px] h-auto object-cover"
                    src="https://storage.googleapis.com/a1aa/image/poE3uy6YbhJTHlqrnfkTie0EF6z2nA9EudYmOlIGULzfTV3nA.jpg"
                    width="100%" />
                <div class="relative">
                    <img alt="Aerial view of a factory complex surrounded by greenery"
                        class="w-full max-h-[150px] h-auto object-cover"
                        src="https://storage.googleapis.com/a1aa/image/poE3uy6YbhJTHlqrnfkTie0EF6z2nA9EudYmOlIGULzfTV3nA.jpg"
                        width="100%" />
                    <a class="absolute bottom-4 left-4 bg-black bg-opacity-50 text-white px-4 py-2 rounded"
                        href="#">See All Photos</a> --}}
                {{-- </div> --}}
            </div>
        </div>
    </div>

    <!-- Section 2 -->
    <div class="container mx-auto mt-10 px-20">
        <!-- Main Content -->
        <div class="mb-6">
            <h2 class="text-3xl text-green-600 vollkorn-400">
                Best Property
                <span class="text-green-900 vollkorn-800 underline underline-offset-8">
                    Factory
                </span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-sm">
            <!-- Facilities Section -->
            <div class="bg-white p-6 rounded-lg shadow-lg border">
                <h2 class="text-xl font-bold text-green-700 mb-4">Facilities</h2>
                <div class="flex justify-around mb-4">
                    <div class="text-center">
                        <i class="fas fa-expand-arrows-alt text-3xl text-gray-600"></i>
                        <p>{{ $property['land_area'] }} m²</p>
                        <p>Land Area</p>
                    </div>
                    <div class="text-center">
                        <i class="fas fa-layer-group text-3xl text-gray-600"></i>
                        <p>{{ $property['building_area'] }} m²</p>
                        <p>Building Area</p>
                    </div>
                </div>
                <h3 class="text-lg font-bold text-green-700 mb-2">Other :</h3>
                <ul class="list-none">
                    @foreach ($property['get_facility'] as $facility)
                        <li class="flex items-center mb-2">
                            <i class="fas fa-minus-circle text-green-600 mr-2"></i> {{ $facility['facility'] }}
                        </li>
                    @endforeach
                </ul>
            </div>

            <!-- In the Area Section -->
            <div class="bg-gradient-to-r from-gray-100 to-white p-6 rounded-lg shadow-lg border">
                <h2 class="text-xl font-bold text-green-700 mb-4">In the Area</h2>
                <div class="flex items-center mb-4">
                    <i class="fas fa-map-marker-alt text-green-600 mr-2"></i>
                    <p>{{ $property['property_address'] }}</p>

                    <a href="{{ $property['get_zoning']['link_map'] }}" class="text-green-600 ml-auto" target="blank">See
                        Map</a>
                </div>
                <h3 class="text-lg font-bold text-green-700 mb-2">Strategic Location</h3>
                <ul class="list-none">
                    @if (
                        $property['get_zoning'] &&
                            $property['get_zoning']['strategic_location'] &&
                            is_array($property['get_zoning']['strategic_location']))
                        @foreach ($property['get_zoning']['strategic_location'] as $location)
                            <li class="flex items-center mb-2">
                                <i class="fas fa-map-marker-alt text-green-600 mr-2"></i> Menuju
                                {{ $location['strategic_location'] }} <span class="ml-auto"> {{ $location['distance'] }}
                                    {{ $location['distance_type'] }}</span>
                            </li>
                        @endforeach
                    @endif
                    {{-- <li class="flex items-center mb-2">
                     <i class="fas fa-map-marker-alt text-green-600 mr-2"></i> Menuju Mall cakung <span class="ml-auto">2 km</span>
                  </li>
                  <li class="flex items-center">
                     <i class="fas fa-map-marker-alt text-green-600 mr-2"></i> Menuju RSUD cakung <span class="ml-auto">2 km</span>
                  </li> --}}
                </ul>
            </div>
        </div>

        <!-- Description Section -->
        <div class="bg-white p-6 rounded-lg shadow-lg mt-6 border">
            <h2 class="text-xl font-bold text-green-700 mb-4">Description</h2>
            <p>{{ $property['desc'] }} <a href="#" class="text-green-600">Read More</a></p>
        </div>
    </div>

    <!-- Section 3 -->
    <div class="mt-20 w-full h-3/4" id="virtual-tour">
        @if ($property['type_upload'] == 'link')
            <div class="relative" style="" >
                <div class="relative" style="">
                    <iframe class="w-full h-full" src="{{ $property['url'] }}" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            @else
                <div class="h-full w-full">
                    <video src="{{ asset('videos/' . $property['vidio']) }}" controls
                        class="h-[inherit] w-[inherit]"></video>
                </div>
        @endif
    </div>


    <!-- Section 4 -->
    <div class="container mx-auto mt-10 px-20">
        <div class="flex justify-between gap-10">
            <div class="w-1/2">
                <h2 class="text-3xl text-green-600 vollkorn-400">
                    {{ $property['get_category']['name'] }}
                    <span class="text-green-900 vollkorn-800 underline underline-offset-8">
                        Layout
                    </span>
                </h2>
                <img alt="3D layout of a {{ $property['get_category']['name'] }} with various sections and equipment"
                    class="mt-4 " src="{{ asset('layouts/' . $property['layout']) }}" />
            </div>
            <div class="w-1/2">
                <h2 class="text-3xl text-green-600 vollkorn-400">
                    {{ $property['get_category']['name'] }}
                    <span class="text-green-900 vollkorn-800 underline underline-offset-8">
                        Location point
                    </span>
                </h2>
                <div class="mt-4">
                    <iframe class="w-full h-96" frameborder="0" src="{{ $property['property_location_link'] }}"
                        style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
                </div>
            </div>
        </div>
    </div>

    <div id="modal-image"
        class="fixed z-[20] bg-black bg-opacity-50 top-0 left-0 right-0 bottom-0 bg-orenge-20 grid place-items-center hidden">
        <div class="w-[80%] h-[90%] bg-white rounded-md flex flex-col justify-between">
            <div class="border-b border-black flex justify-end py-1 px-4">
                <button id="close-modal">
                    <span class="font-medium text-3xl">x</span>
                </button>
            </div>
            <div class="flex-1 h-full flex justify-between p-2">
                <div class="min-w-[3rem] flex justify-center items-center">
                    <button id="prev-btn">
                        <span class="inline-block text-5xl">
                            &lt;
                        </span>
                    </button>
                </div>
                <div class="flex-1" style="width: calc(90dvw - 2rem); height: calc(80dvh - 2rem)">
                    <div class="w-full h-full grid place-items-center" id="loading-text">
                        <p class="animate-pulse">Loading ...</p>
                    </div>
                    <img src="" alt="Image" id="detail-image" class="object-contain w-full h-full">
                </div>
                <div class="min-w-[3rem] flex justify-center items-center">
                    <button id="next-btn">
                        <span class="inline-block text-5xl">
                            &gt;
                        </span>
                    </button>
                </div>
            </div>
            <div class="text-center p-3">
                <h3 class="font-medium" id="img-name">-</h3>
            </div>
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
                // updateQueryParam('category', value);
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
                        // updateQueryParam('building-area', '0-3000');
                    }
                }
            });

            // Zone
            $('.selectpicker').on('change', function() {
                // updateQueryParam('zone', $(this).val());
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
                    // updateQueryParam('status', $(this).val());
                }

                const statusInputHid = document.createElement('input');
                statusInputHid.type = "hidden";
                statusInputHid.name = $(this).attr('name');
                statusInputHid.value = $(this).val();
                setHiddenInput(statusInputHid);
            });

            const existingQueryParam = getQueryParam();
            if (existingQueryParam.hasOwnProperty('status')) {
                const statusValue = existingQueryParam['status'];
                $('input[name="status"]').each(function() {
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
                            // updateQueryParam(keyQuery, value);

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

            /** ================================ MODAL DETAIL IMAGE ================================ */

            const modalContainer = $('#modal-image');
            const loadingComp = $('#loading-text');
            const imagePrev = $('#detail-image');
            const imageName = $('#img-name');
            const nextBtn = modalContainer.find('#next-btn');
            const prevBtn = modalContainer.find('#prev-btn');

            let images = [];
            let indexImage = 0;

            // Close btn
            modalContainer.find('#close-modal').on('click', function() {
                modalContainer.hide();
            });

            function setImage(index) {
                const urlPhotos = `{{ url('detail_photos') }}/${images[index]?.detail_photo}`;
                imagePrev.attr('src', urlPhotos)
                imagePrev.attr('alt', images[index].detail_photo);
                imageName.text(images[index]?.detail_photo ?? "-")
            }

            nextBtn.on('click', function() {
                if (images.length == 0) return;
                if ((indexImage + 1) < images.length) {
                    indexImage += 1;
                    setImage(indexImage);
                } else {
                    indexImage = 0;
                    setImage(indexImage);
                }
            });

            prevBtn.on('click', function() {
                if (images.length == 0) return;
                if (indexImage > 0) {
                    indexImage -= 1;
                    setImage(indexImage);
                } else {
                    indexImage = (images.length - 1);
                    setImage(indexImage);
                }
            })


            $('#see-all-photos').on('click', function() {

                const path = window.location.pathname;
                const id = path.split('/').pop();
                const url = `{{ url('/api/product-images/') }}/${id}`;

                modalContainer.show();
                imagePrev.hide();
                loadingComp.show();

                // console.log('Get data');

                $.ajax({
                    url,
                    method: 'GET',
                }).done(function(response) {
                    images = response.data;
                    // console.log(images);
                    const urlPhotos =
                        `{{ url('detail_photos') }}/${images[indexImage]?.detail_photo}`;
                    // console.log({urlPhotos});
                    imagePrev.attr('src', urlPhotos);
                    imagePrev.attr('alt', images[indexImage]?.detail_photo)
                    imageName.text(images[indexImage]?.detail_photo)
                }).fail(function(error) {
                    console.error("error", error)
                }).always(function() {
                    console.log("always block");
                    imagePrev.show();
                    loadingComp.hide();
                })
            });

            $('[data-category]').on('click', function(){
                const categoryValue = $(this).attr('data-category');
                $('input[name=category]').val(categoryValue);
            })

        });
    </script>

@endsection
