<section class="pt-20  bg-white" style="border-top-left-radius: 80px;border-top-right-radius: 80px;margin-top: 0;z-index: 999;position: relative;">
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