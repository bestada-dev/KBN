<!-- Contact Section -->
<section class="flex lg:flex-row flex-col mx-auto w-9/12 py-14 flex justify-between gap-20">
    <div class="w-full md:w-2/5">
         <h2 class="text-3xl text-green-600 vollkorn-400">
            {!!  formattingTitle($home[6]['title1_'.app()->getLocale()], 'green-900') ?? '' !!}
         </h2>
         <p class="mt-4 text-gray-800">
         {{ $home[6]['description1_'.app()->getLocale()] ?? '' }}
         </p>
       <div class="flex flex-col gap-y-5 mt-8">
          <!-- Icon dan Deskripsi Lokasi -->
          <div class="flex flex-col items-center gap-y-3 lg:flex-row gap-x-3">
             <i class="fas fa-map-marker-alt text-green-600 mr-2 text-xl lg:text-2xl"></i>
             <p class="flex items-center text-gray-800 lg:w-1/2 lg:text-left text-center mb-4 lg:mb-0">
               {{ $home[6]['address'] ?? '' }}
             </p>
          </div>
          <!-- Icon dan Deskripsi Telepon -->
          <div class="flex flex-col items-center gap-y-3 lg:flex-row gap-x-3">
             <i class="fas fa-phone-alt text-green-600 mr-2 text-xl lg:text-2xl"></i>
             <p class="flex items-center text-gray-800 lg:w-1/2 lg:text-left text-center mb-4 lg:mb-0">
               {{ $home[6]['phone'] ?? '' }}
             </p>
          </div>
          <!-- Icon dan Deskripsi Email -->
          <div class="flex flex-col items-center gap-y-3 lg:flex-row gap-x-3">
             <i class="fas fa-envelope text-green-600 mr-2 text-xl lg:text-2xl"></i>
             <p class="flex items-center text-gray-800 lg:w-1/2 lg:text-left text-center mb-4 lg:mb-0">
               {{ $home[6]['email'] ?? '' }}
             </p>
          </div>
      </div>
         
    </div>
    <div class="w-full md:w-3/5 bg-white px-6 sm:px-10 py-12 rounded-lg border shadow-md">
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
                type="text"
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
             >
             </textarea>
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