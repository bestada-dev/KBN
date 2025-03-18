<section class="h-[520px] relative flex items-center">
    <div class="bg-green-100 absolute top-0 left-0 h-full w-[100%] z-[-1]"></div>
    <div class="flex flex-col lg:flex-row mx-auto px-6 py-8 max-w-7xl items-center space-y-6 lg:space-y-0">
    
        <div class="flex flex-col w-1/2 lg:mr-[900px]">
        <h2 class="text-3xl font-bolder vollkorn-500 text-green-900">
            Property With VR
        </h2>
        <p class="mt-4 text-green-600">
            Our designer cleverly made a lot of beautiful property of room that inspire you
        </p>
        </div>
    
        <div class="flex">
        <div class="parent absolute w-1/2 top-[2rem] lg:bottom-0">
            <button class="bg-white border border-green-200 text-white h-[50px] w-[50px] rounded-full absolute bottom-20 z-40 right-[13rem] test bottom-[50%] hover:shadow-lg shadow-md" id="btn-next-slide">
            <i class="fas fa-arrow-right text-green-600 "></i>
            </button>
            <button class="bg-white border border-green-200 text-white h-[50px] w-[50px] rounded-full absolute bottom-20 z-40 left-[-16rem] test bottom-[50%]  hover:shadow-lg shadow-md" id="btn-prev-slide">
            <i class="fas fa-arrow-left text-green-600 "></i>
            </button>
    
            <div class="card-carousel mr-[260px]">
            @foreach ($product_property as $index => $item)
                <div class="my-card {{ $loop->first ? 'active' : 'next' }}">
                    <div class="relative w-60 bg-white overflow-hidden shadow-lg">
                        <img alt="Interior of a warehouse with shelves and a forklift" class="w-full h-[450px] object-cover" src="https://storage.googleapis.com/a1aa/image/x4PHqzKseU0jdyXrUnJAccUfkR86t1ke8gNfEU2nxAfxQQAfE.jpg">
                        <div class="absolute top-4 left-4 bg-green-600 text-white text-xs font-semibold px-3 py-1 rounded-full flex items-center">
                            <i class="fas fa-vr-cardboard mr-2"></i>
                            VIRTUAL TOUR
                        </div>
                        <div class="absolute bottom-4 left-4 right-4 bg-white bg-opacity-75 p-2 flex justify-between items-center">
                            <div>
                                <h2 class="text-sm font-semibold">{{ $item->block }}</h2>
                                <div class="flex items-center mt-2 text-gray-600">
                                    <i class="fas fa-ruler-combined mr-2"></i>
                                    <span class="text-sm">{{ $item->land_area }} m2</span>
                                    <i class="fas fa-ruler-combined ml-4 mr-2"></i>
                                    <span>{{ $item->building_area }} m2</span>
                                </div>
                            </div>
                            <a class="bg-green-600 text-white p-2 rounded-full" href="{{ route('product.show', $item->id) }}">
                                <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
            
            </div>
        </div>
        </div>
    </div>
    </section>