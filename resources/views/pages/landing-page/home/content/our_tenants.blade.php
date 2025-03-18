<!-- Our Tenants Section -->
<section class="py-12">
    <div class="container mx-auto py-4 w-11/12">
    <h2 class="text-3xl text-green-600 vollkorn-400">
       Our 
       <span class="text-green-900 vollkorn-800 underline underline-offset-8">
          Tenants
       </span>
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