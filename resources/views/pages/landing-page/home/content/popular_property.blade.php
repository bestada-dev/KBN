<section class="pt-16 pb-3 bg-white"
    style="border-top-left-radius: 80px;border-top-right-radius: 80px;margin-top: 0;z-index: 999;position: relative;">
    <div class="container mx-auto py-4 w-11/12">
        <h2 class="text-2xl  poppins-semibold mb-2">
            Our Popular properties
        </h2>

        <div class="splide-our-popular-properties-wrap mt-8 h-[390px]">
            <div id="splide-our-popular-properties" class="splide" aria-label="Beautiful Images">
                <div class="splide__track">
                    <ul class="splide__list">
                        @foreach ($product_property as $item)  

                        <li class="splide__slide relative">
                            <!-- Card 1 -->
                            <div class="bg-white shadow-lg  overflow-hidden max-w-sm border">
                                <div class="relative">
                                    <img alt="Aerial view of a large warehouse with trucks and greenery around"
                                        class="w-full h-48 object-cover" height="200"
                                        src="https://storage.googleapis.com/a1aa/image/x4PHqzKseU0jdyXrUnJAccUfkR86t1ke8gNfEU2nxAfxQQAfE.jpg"
                                        width="400" />
                                    <span
                                        class="absolute top-2 right-2 bg-green-500 text-white text-xs font-semibold px-2 py-1 rounded">
                                        Cooling warehouse
                                    </span>
                                </div>
                                <div class="p-4">
                                    <h2 class="text-xl font-semibold">
                                        {{ $item->block ?? '1963 S Crescent Warehouse' }}
                                    </h2>
                                    <p class="text-gray-600">
                                        <i class="fas fa-map-marker-alt text-green-500">
                                        </i>
                                        {{ $item->propery_address ?? 'Hills, CA 90210' }}
                                    </p>
                                    <div class="flex lg:flex-row flex-col justify-between items-center mt-4 text-gray-600">
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
    </div>
</section>