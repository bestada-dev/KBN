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