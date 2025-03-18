
          <!-- Our Tenants Section -->
          <section class="py-12">
            <div class="container mx-auto py-4 w-11/12">
             <h2 class="text-3xl text-green-600 vollkorn-400">
             {!!  formattingTitle($home[5]['title1_'.app()->getLocale()], 'green-900') ?? '' !!}
             </h2>
            <div class="slider mt-7">
                <div class="slider-items">
                  @foreach($childBagian6 as $i)
                  <img src="{{ file_exists(public_path('storage/'.$i['image'])) ? Storage::url($i['image']) : asset($i['image']) }}" alt="">
                  @endforeach
                    <!-- 
                     OLD
                     <img src="{{ asset('assets/for-landing-page/p1.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p2.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p3.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p4.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p1.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p2.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p3.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p4.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p1.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p2.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p3.png') }}" alt="">
                    <img src="{{ asset('assets/for-landing-page/p4.png') }}" alt=""> -->
                </div>
            </div>
            </div>
         </section>
       <!-- Contact Section -->
       <section class="container mx-auto w-9/12 py-14 flex justify-between gap-20">
        <div class="w-2/5">
           <h2 class="text-3xl text-green-600 vollkorn-400">
              {!!  formattingTitle($home[6]['title1_'.app()->getLocale()], 'green-900') ?? '' !!}
           </h2>
           <p class="mt-4 text-gray-800">
           {{ $home[6]['description1_'.app()->getLocale()] ?? '' }}
           </p>
           <div class="mt-8">
              <p class="flex items-center text-gray-800">
                 <i class="fas fa-map-marker-alt text-green-600 mr-2">
                 </i>
                 {{ $home[6]['address'] ?? '' }}
              </p>
              <p class="flex items-center text-gray-800 mt-4">
                 <i class="fas fa-phone-alt text-green-600 mr-2">
                 </i>
                 {{ $home[6]['phone'] ?? '' }}
              </p>
              <p class="flex items-center text-gray-800 mt-4">
                 <i class="fas fa-envelope text-green-600 mr-2">
                 </i>
                 {{ $home[6]['email'] ?? '' }}
              </p>
           </div>
        </div>
        <div class="w-3/5 bg-white px-10 py-12 rounded-lg border shadow-md">
         <form action="{{ route('message') }}" method="POST">
            @csrf
            <div class="flex mb-4">
               <input
                  class="w-1/2 px-4 py-2 mr-2 border rounded-lg bg-gray-50 focus:bg-white focus:border-cyan-500 focus:outline-none text-sm duration-300 transition-all"
                  placeholder="First Name"
                  required="" 
                  type="text"
                  name="first_name" 
                  id="first_name" 
               >
               <input 
                  class="w-1/2 px-4 py-2 border rounded-lg bg-gray-50 focus:bg-white focus:border-cyan-500 focus:outline-none text-sm duration-300 transition-all"
                  placeholder="Last Name"
                  required="" 
                  type="text"
                  name="last_name" 
                  id="last_name" 
               >
            </div>
            <div class="mb-4">
               <input 
                  class="w-full px-4 py-2 border rounded-lg bg-gray-50 focus:bg-white focus:border-cyan-500 focus:outline-none text-sm duration-300 transition-all " 
                  placeholder="Email" 
                  required="" 
                  type="email"
                  name="email" 
                  id="email" 
               >
            </div>
            <div class="mb-4">
               <input 
                  class="w-full px-4 py-2 border rounded-lg bg-gray-50 focus:bg-white focus:border-cyan-500 focus:outline-none text-sm duration-300 transition-all" 
                  placeholder="Phone Number" 
                  required="" 
                  type="number"
                  name="phone_number" 
                  id="phone_number" 
               >
            </div>
            <div class="mb-4">
               <textarea 
                  class="w-full px-4 py-2 border rounded-lg bg-gray-50 focus:bg-white focus:border-cyan-500 focus:outline-none text-sm duration-300 transition-all" 
                  placeholder="Your message..." 
                  required="" 
                  rows="4"
                  name="message" 
                  id="message" 
               ></textarea>
            </div>
            <button 
               class="w-full px-4 py-2 text-white bg-green-600 rounded-full hover:bg-green-700 mt-2" 
               type="submit"
            >
               Send
            </button>
         </form>
        </div>
     </section>
       <!-- Footer Section -->
       <footer class="bg-custom-green-gradient text-white py-14" style="border-top-left-radius: 80px;border-top-right-radius: 80px;">
            <div class="container mx-auto px-10">
             <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
              <div class="col-span-1 flex flex-col items-center md:items-start">
                <img alt="Company Logo" src="{{ asset('assets/for-landing-page/logo') }}.png" width="140">
               <p class="text-sm font-bold" style="font-size:9px">
                KBN © 2024 All rights reserved
               </p>
               <div class="flex space-x-4 mt-4">
                <a class="text-white" href="#">
                 <i class="fab fa-linkedin">
                 </i>
                </a>
                <a class="text-white" href="#">
                 <i class="fab fa-twitter">
                 </i>
                </a>
                <a class="text-white" href="#">
                 <i class="fab fa-instagram">
                 </i>
                </a>
                <a class="text-white" href="#">
                 <i class="fab fa-facebook">
                 </i>
                </a>
               </div>
              </div>
              <div class="col-span-1 text-sm">
               <h3 class="font-bold mb-4 text-lg">
                Contact us
               </h3>
               <p class="mb-2">
                <i class="fas fa-map-marker-alt mr-2">
                </i>
                {{ $home[6]['address'] ?? '' }}
               </p>
               <p class="mb-2">
                <i class="fas fa-phone-alt mr-2">
                </i>
                {{ $home[6]['phone'] ?? '' }}
               </p>
               <p class="mb-2">
                <i class="fas fa-envelope mr-2">
                </i>
                {{ $home[6]['email'] ?? '' }}
               </p>
              </div>
              <div class="col-span-1 text-sm">
               <h3 class="font-bold mb-4 text-lg">
                Contents
               </h3>
               <ul>
                <li class="mb-1">
                 Warehouse
                </li>
                <li class="mb-1">
                 Factory
                </li>
                <li class="mb-1">
                 Container Yard
                </li>
                <li class="mb-1">
                 Industrial Land
                </li>
                <li>
                 Contact us
                </li>
               </ul>
              </div>
              <div class="col-span-1 text-sm">
               <h3 class="font-bold mb-4 text-lg">
                The best choice
               </h3>
               <p>
                Participate now for a different experience than ever before
               </p>
              </div>
             </div>
            </div>
           </footer>