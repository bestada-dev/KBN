<section class="py-12 bg-white" style="border-top-left-radius: 80px;border-top-right-radius: 80px;margin-top: 0;z-index: 999;position: relative;">
    <div class="container mx-auto py-4 w-11/12">
        <h2 class="text-3xl text-green-600 vollkorn-400">
        A Comfortable 
        <span class="text-green-900 vollkorn-800 underline underline-offset-8">
            Place For You
        </span>
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
                                    src="/uploads/{{ $attachment->detail_photo ?? 'https://storage.googleapis.com/a1aa/image/hHuyL8WgPCYxEptrHY6JE0CFa9z6tDHy35P4tXK5yx3bhAeJA.jpg' }}"
                                    width="600" />
                                <div class="p-4 flex justify-between">
                                    <p class="text-gray-700">
                                        19930
                                    </p>
                                    <p class="text-green-600">
                                        <i class="fas fa-map-marker-alt text-green-700 mr-2"></i>
                                        {{ $item->propery_address ?? 'Hills, CA 90210' }}
                                    </p>
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