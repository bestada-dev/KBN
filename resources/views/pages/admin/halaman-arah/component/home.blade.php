<style>
    div#Section_5_Poin_poin .items-center,
    div#Section_5_Poin_poin .items-center,
    div#Section_6_Logo .items-center{
        display: flex !important;
        align-items: start !important;
        margin-top: 1.6rem !important;
    }
</style>

<form id="home-form" action="{{url('api/landing-page/home')}}" method="POST" enctype="multipart/form-data"
      class="p-4 pt-3">
    @csrf
  <div id="Section_1" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 1</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_1_id" value="{{$section1['id'] ?? '' }}">
        
          <div class="col-md-12 tests">
            <!-- Judul INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_1_title1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_1_title1_id" id="Section_1_title1_id" required placeholder="Input Judul" aria-describedby="Section_1_title1_id" value="{{ $section1['title1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Judul ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_1_title1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_1_title1_en" id="Section_1_title1_en" required placeholder="Input Judul" aria-describedby="Section_1_title1_en" value="{{ $section1['title1_en'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_1_description1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Deskripsi <b>(*)</b>
              </label>
              <textarea class="form-control" name="Section_1_description1_id" rows="5" id="Section_1_description1_id" required placeholder="Input Deskripsi" aria-describedby="Section_1_description1_id">{{$section1['description1_id'] ?? '' }}</textarea>
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_1_description1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Deskripsi <b>(*)</b>
              </label>
              <textarea class="form-control" name="Section_1_description1_en" rows="5" id="Section_1_description1_en" required placeholder="Input Deskripsi" aria-describedby="Section_1_description1_en">{{$section1['description1_en'] ?? '' }}</textarea>
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Foto  input -->
            <div class="mb-2">
              <label for="Section_1_photo" class="form-label"> Foto <b>(*)</b>
              </label>
              <div class="form-group">
                <div class="file-upload-container">
                  <input type="file" class="file-input" name="Section_1_photo" id="Section_1_photo" data-max-file-size="10" accept=".png, .jpeg, .jpg" aria-describedby="Section_1_photo" value="{{ file_exists(public_path('storage/'.$section1['photo'])) ? Storage::url($section1['photo']) : asset($section1['photo']) }}" style="display:none">
                  <!-- File input is hidden -->
                  <div class="file-upload-box">
                    <div class="file-details">
                      <i class="bi bi-file-earmark"></i>
                      <div>
                        <span class="file-name-size"> {{ file_exists(public_path('storage/'.$section1['photo'])) ? Storage::url($section1['photo']) : asset($section1['photo']) }}
                          <!-- Get the file name -->
                        </span>
                        <span class="file-size"></span>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn-icon ms-2 view-button" data-bs-toggle="modal" data-bs-target="#filePreviewModal">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn-icon ms-2 reset-button" type="button">
                    <i class="bi bi-save2"></i>
                  </button>
                </div>
                <p class="upload-info">Ukuran maksimum: 10 MB. Format file: .png, .jpeg, .jpg.</p>
                <span class="text-danger error-message" style="display:none">This field is required.</span>
                <input type="hidden" class="form-control" name="Section_1_photo_old" value="{{ file_exists(public_path('storage/'.$section1['photo'])) ? Storage::url($section1['photo']) : asset($section1['photo']) }}">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_1-add-here"></div>
  </div>
  <div id="Section_2" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 2</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_2_id" value="{{$section2['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Judul INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_2_title1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_2_title1_id" id="Section_2_title1_id" required placeholder="Input Judul" aria-describedby="Section_2_title1_id" value="{{ $section2['title1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Judul ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_2_title1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_2_title1_en" id="Section_2_title1_en" required placeholder="Input Judul" aria-describedby="Section_2_title1_en" value="{{ $section2['title1_en'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_2-add-here"></div>
  </div>
  <div id="Section_3" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 3</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_3_id" value="{{$section3['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Judul INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_3_title1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_3_title1_id" id="Section_3_title1_id" required placeholder="Input Judul" aria-describedby="Section_3_title1_id" value="{{ $section3['title1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Judul ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_3_title1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_3_title1_en" id="Section_3_title1_en" required placeholder="Input Judul" aria-describedby="Section_3_title1_en" value="{{ $section3['title1_en'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_3_description1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Deskripsi <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_3_description1_id" id="Section_3_description1_id" required placeholder="Input Deskripsi" aria-describedby="Section_3_description1_id" value="{{ $section3['description1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_3_description1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Deskripsi <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_3_description1_en" id="Section_3_description1_en" required placeholder="Input Deskripsi" aria-describedby="Section_3_description1_en" value="{{ $section3['description1_en'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_3-add-here"></div>
  </div>

  <div id="Section_4" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 4</h1>
    </div>
    
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_4_id" value="{{$section4['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Judul INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_4_title1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_4_title1_id" id="Section_4_title1_id" required placeholder="Input Judul" aria-describedby="Section_4_title1_id" value="{{ $section4['title1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Judul ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_4_title1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_4_title1_en" id="Section_4_title1_en" required placeholder="Input Judul" aria-describedby="Section_4_title1_en" value="{{ $section4['title1_en'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_4-add-here"></div>
  </div>
  <div id="Section_5" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 5</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_5_id" value="{{$section5['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Judul INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_5_title1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_5_title1_id" id="Section_5_title1_id" required placeholder="Input Judul" aria-describedby="Section_5_title1_id" value="{{ $section5['title1_id'] ?? ''}}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Judul ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_5_title1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_5_title1_en" id="Section_5_title1_en" required placeholder="Input Judul" aria-describedby="Section_5_title1_en" value="{{ $section5['title1_en'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_5_description1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Deskripsi <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_5_description1_id" id="Section_5_description1_id" required placeholder="Input Deskripsi" aria-describedby="Section_5_description1_id" value="{{ $section5['description1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_5_description1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Deskripsi <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_5_description1_en" id="Section_5_description1_en" required placeholder="Input Deskripsi" aria-describedby="Section_5_description1_en" value="{{$section5['description1_en'] ?? ''}}">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_5-add-here"></div>
  </div>
  <div id="Section_5_Poin_poin" class="mb-3" style="background:#e9f4ff;padding:1.4rem;border-radius:15px">
   <div class="between">
     <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 5 Poin poin</h1>
     <button data-section="Section_5_Poin_poin" type="button" id="addMoreBtn" class="btn btn-default btn-sm add-field" style="color: rgb(0, 107, 183); font-weight: 500;">
       <img src="http://localhost:8001/assets/add-outline.png"> Add </button>
   </div>
   @if(count($childBagian5))
    @foreach($childBagian5 as $item)
   <div class="row">
     <div class="col-md-11">
       <div class="row dynamic-row">
       <input type="hidden" name="Section_5_Poin_poin_id[{{$item['id']}}]" value="{{$item['id']}}">
         <div class="col-md-12 tests">
           <!-- Judul  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_title1_id[{{$item['id']}}]" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2">  Judul <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_title1_id[{{$item['id']}}]" id="Section_5_Poin_poin_title1_id[{{$item['id']}}]_{{$item['id']}}" required placeholder="Input Judul" aria-describedby="Section_5_Poin_poin_title1_id[{{$item['id']}}]_{{$item['id']}}" value="{{$item['title1_id']}}">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Judul  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_title1_en[{{$item['id']}}]" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_title1_en[{{$item['id']}}]" id="Section_5_Poin_poin_title1_en[{{$item['id']}}]_{{$item['id']}}" required placeholder="Input Judul" aria-describedby="Section_5_Poin_poin_title1_en[{{$item['id']}}]_{{$item['id']}}" value="{{ $item['title1_en'] }}">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Deskripsi  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_description1_id[{{$item['id']}}]" class="form-label"><img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2">  Deskripsi <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_description1_id[{{$item['id']}}]" id="Section_5_Poin_poin_description1_id[{{$item['id']}}]_{{$item['id']}}" required placeholder="Input Deskripsi" aria-describedby="Section_5_Poin_poin_description1_id[{{$item['id']}}]_{{$item['id']}}" value="{{$item['description1_id']}}">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Deskripsi  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_description1_en[{{$item['id']}}]" class="form-label"><img src="https://flagsapi.com/US/shiny/24.png" class="mr-2">  Deskripsi <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_description1_en[{{$item['id']}}]" id="Section_5_Poin_poin_description1_en[{{$item['id']}}]_{{$item['id']}}" required placeholder="Input Deskripsi" aria-describedby="Section_5_Poin_poin_description1_en[{{$item['id']}}]_{{$item['id']}}" value="{{$item['description1_en']}}">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Foto  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_photo[{{$item['id']}}]" class="form-label"> Foto <b>(*)</b>
             </label>
             <div class="form-group">
               <div class="file-upload-container">
                 <input type="file" class="file-input" name="Section_5_Poin_poin_photo[{{$item['id']}}]" id="Section_5_Poin_poin_photo[{{$item['id']}}]_{{$item['id']}}"  data-max-file-size="10" value="{{ file_exists(public_path('storage/'.$item['image'])) ? Storage::url($item['image']) : asset($item['image']) }}" accept=".png, .jpeg, .jpg" aria-describedby="Section_5_Poin_poin_photo[{{$item['id']}}]_{{$item['id']}}" style="display:none">
                 <!-- File input is hidden -->
                 <div class="file-upload-box">
                   <div class="file-details">
                     <i class="bi bi-file-earmark"></i>
                     <div>
                       <span class="file-name-size"> {{ file_exists(public_path('storage/'.$item['image'])) ? Storage::url($item['image']) : asset($item['image']) }}
                         <!-- Get the file name -->
                       </span>
                       <span class="file-size"></span>
                     </div>
                   </div>
                 </div>
                 <button type="button" class="btn-icon ms-2 view-button" data-bs-toggle="modal" data-bs-target="#filePreviewModal">
                   <i class="bi bi-eye"></i>
                 </button>
                 <button class="btn-icon ms-2 reset-button" type="button">
                   <i class="bi bi-save2"></i>
                 </button>
               </div>
               <p class="upload-info">Ukuran maksimum: 10 MB. Format file: .png, .jpeg, .jpg.</p>
               <span class="text-danger error-message" style="display:none">This field is required.</span>
               <input type="hidden" class="form-control" name="Section_5_Poin_poin_photo[{{$item['id']}}]_old" value="{{ file_exists(public_path('storage/'.$item['image'])) ? Storage::url($item['image']) : asset($item['image']) }}">
             </div>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-1 items-center">
       <button type="button" class="btn btn-sm-custom btn-danger remove-field bold" data-section="Section_5_Poin_poin">X </button>
     </div>
   </div>
   @endforeach
   @else
   <div class="row">
     <div class="col-md-11">
       <div class="row dynamic-row">
       <input type="hidden" name="Section_5_Poin_poin_id[0]" value="">
         <div class="col-md-12 tests">
           <!-- Judul  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_title1_id[0]" class="form-label"><img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2">  Judul <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_title1_id[0]" id="Section_5_Poin_poin_title1_id[0]_1" required placeholder="Input Judul" aria-describedby="Section_5_Poin_poin_title1_id[0]_1" value="">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Judul  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_title1_en[0]" class="form-label"><img src="https://flagsapi.com/US/shiny/24.png" class="mr-2">  Judul <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_title1_en[0]" id="Section_5_Poin_poin_title1_en[0]_1" required placeholder="Input Judul" aria-describedby="Section_5_Poin_poin_title1_en[0]_1" value="">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Deskripsi  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_description1_id[0]" class="form-label"><img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2">  Deskripsi <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_description1_id[0]" id="Section_5_Poin_poin_description1_id[0]_1" required placeholder="Input Deskripsi" aria-describedby="Section_5_Poin_poin_description1_id[0]_1" value="">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Deskripsi  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_description1_en[0]" class="form-label"><img src="https://flagsapi.com/US/shiny/24.png" class="mr-2">  Deskripsi <b>(*)</b>
             </label>
             <input type="text" class="form-control" name="Section_5_Poin_poin_description1_en[0]" id="Section_5_Poin_poin_description1_en[0]_1" required placeholder="Input Deskripsi" aria-describedby="Section_5_Poin_poin_description1_en[0]_1" value="">
           </div>
         </div>
         <div class="col-md-12 tests">
           <!-- Foto  input -->
           <div class="mb-2">
             <label for="Section_5_Poin_poin_photo[0]" class="form-label"> Foto <b>(*)</b>
             </label>
             <div class="form-group">
               <div class="file-upload-container">
                 <input type="file" class="file-input" name="Section_5_Poin_poin_photo[0]" id="Section_5_Poin_poin_photo[0]_1" required data-max-file-size="10" value="" accept=".png, .jpeg, .jpg" aria-describedby="Section_5_Poin_poin_photo[0]_1" style="display:none">
                 <!-- File input is hidden -->
                 <div class="file-upload-box">
                   <div class="file-details">
                     <i class="bi bi-file-earmark"></i>
                     <div>
                       <span class="file-name-size"> Pilih File
                         <!-- Get the file name -->
                       </span>
                       <span class="file-size"></span>
                     </div>
                   </div>
                 </div>
                 <button type="button" class="btn-icon ms-2 view-button" data-bs-toggle="modal" data-bs-target="#filePreviewModal">
                   <i class="bi bi-eye"></i>
                 </button>
                 <button class="btn-icon ms-2 reset-button" type="button">
                   <i class="bi bi-save2"></i>
                 </button>
               </div>
               <p class="upload-info">Ukuran maksimum: 10 MB. Format file: .png, .jpeg, .jpg.</p>
               <span class="text-danger error-message" style="display:none">This field is required.</span>
               <input type="hidden" class="form-control" name="Section_5_Poin_poin_photo[0]_old[]" value="">
             </div>
           </div>
         </div>
       </div>
     </div>
     <div class="col-md-1 items-center">
       <button type="button" class="btn btn-sm-custom btn-danger remove-field bold" data-section="Section_5_Poin_poin">X </button>
     </div>
   </div>
   @endif
   <div id="Section_5_Poin_poin-add-here"></div>
 </div>
  <div id="Section_6" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 6</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_6_id" value="{{$section6['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Judul INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_6_title1_id" class="form-label"> <img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_6_title1_id" id="Section_6_title1_id" required placeholder="Input Judul" aria-describedby="Section_6_title1_id" value="{{$section6['title1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Judul ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_6_title1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_6_title1_en" id="Section_6_title1_en" required placeholder="Input Judul" aria-describedby="Section_6_title1_en" value="{{$section6['title1_en'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_6-add-here"></div>
  </div>

  <div id="Section_6_Logo" class="mb-3" style="background:#e9f4ff;padding:1.4rem;border-radius:15px">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 6 Logo</h1>
      <button data-section="Section_6_Logo" type="button" id="addMoreBtn" class="btn btn-default btn-sm add-field" style="color: rgb(0, 107, 183); font-weight: 500;">
        <img src="http://localhost:8001/assets/add-outline.png"> Add </button>
    </div>

    @if(count($childBagian6))
        @foreach($childBagian6 as $item)
    <div class="row">
      <div class="col-md-11">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_6_Logo_id[{{$item['id']}}]" value="{{$item['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Logo  input -->
            <div class="mb-2">
              <label for="Section_6_Logo_logo[{{$item['id']}}]" class="form-label"> Logo <b>(*)</b>
              </label>
              <div class="form-group">
                <div class="file-upload-container">
                  <input type="file" class="file-input" name="Section_6_Logo_logo[{{$item['id']}}]" id="Section_6_Logo_logo[{{$item['id']}}]_{{$item['id']}}" data-max-file-size="10" value="{{ file_exists(public_path('storage/'.$item['image'])) ? Storage::url($item['image']) : asset($item['image']) }}" accept=".png, .jpeg, .jpg" aria-describedby="Section_6_Logo_logo[{{$item['id']}}]_{{$item['id']}}" style="display:none">
                  <!-- File input is hidden -->
                  <div class="file-upload-box">
                    <div class="file-details">
                      <i class="bi bi-file-earmark"></i>
                      <div>
                        <span class="file-name-size"> {{ file_exists(public_path('storage/'.$item['image'])) ? Storage::url($item['image']) : asset($item['image']) }}
                          <!-- Get the file name -->
                        </span>
                        <span class="file-size"></span>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn-icon ms-2 view-button" data-bs-toggle="modal" data-bs-target="#filePreviewModal">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn-icon ms-2 reset-button" type="button">
                    <i class="bi bi-save2"></i>
                  </button>
                </div>
                <p class="upload-info">Ukuran maksimum: 10 MB. Format file: .png, .jpeg, .jpg.</p>
                <span class="text-danger error-message" style="display:none">This field is required.</span>
                <input type="hidden" class="form-control" name="Section_6_Logo_logo[{{$item['id']}}]_old" value="{{ file_exists(public_path('storage/'.$item['image'])) ? Storage::url($item['image']) : asset($item['image']) }}">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-1 items-center">
        <button type="button" class="btn btn-sm-custom btn-danger remove-field bold" data-section="Section_6_Logo">X </button>
      </div>
    </div>
    @endforeach
    @else
    <div class="row">
      <div class="col-md-11">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_6_Logo_id" value="">
          <div class="col-md-12 tests">
            <!-- Logo  input -->
            <div class="mb-2">
              <label for="Section_6_Logo_logo[0]" class="form-label"> Logo <b>(*)</b>
              </label>
              <div class="form-group">
                <div class="file-upload-container">
                  <input type="file" class="file-input" name="Section_6_Logo_logo[0]" id="Section_6_Logo_logo[0]_1" required data-max-file-size="10" value="" accept=".png, .jpeg, .jpg" aria-describedby="Section_6_Logo_logo[0]_1" style="display:none">
                  <!-- File input is hidden -->
                  <div class="file-upload-box">
                    <div class="file-details">
                      <i class="bi bi-file-earmark"></i>
                      <div>
                        <span class="file-name-size"> Pilih File
                          <!-- Get the file name -->
                        </span>
                        <span class="file-size"></span>
                      </div>
                    </div>
                  </div>
                  <button type="button" class="btn-icon ms-2 view-button" data-bs-toggle="modal" data-bs-target="#filePreviewModal">
                    <i class="bi bi-eye"></i>
                  </button>
                  <button class="btn-icon ms-2 reset-button" type="button">
                    <i class="bi bi-save2"></i>
                  </button>
                </div>
                <p class="upload-info">Ukuran maksimum: 10 MB. Format file: .png, .jpeg, .jpg.</p>
                <span class="text-danger error-message" style="display:none">This field is required.</span>
                <input type="hidden" class="form-control" name="Section_6_Logo_logo[0]_old[]" value="">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-md-1 items-center">
        <button type="button" class="btn btn-sm-custom btn-danger remove-field bold" data-section="Section_6_Logo">X </button>
      </div>
    </div>
    @endif
    <div id="Section_6_Logo-add-here"></div>
  </div>

  <div id="Section_7" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 7</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_7_id" value="{{$section7['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Judul   input -->
            <div class="mb-2">
              <label for="Section_7_title1_id" class="form-label"><img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Judul  <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_7_title1_id" id="Section_7_title1_id" required placeholder="Input Judul " aria-describedby="Section_7_title1_id" value="{{$section7['title1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Judul ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_7_title1_en" class="form-label"> <img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Judul <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_7_title1_en" id="Section_7_title1_en" required placeholder="Input Judul" aria-describedby="Section_7_title1_en" value="{{$section7['title1_en'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi INDONESIA  input -->
            <div class="mb-2">
              <label for="Section_7_description1_id" class="form-label"><img src="https://flagsapi.com/ID/shiny/24.png" class="mr-2"> Deskripsi  </label>
              <input type="text" class="form-control" name="Section_7_description1_id" id="Section_7_description1_id" placeholder="Input Deskripsi" aria-describedby="Section_7_description1_id" value="{{$section7['description1_id'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Deskripsi ENGLISH  input -->
            <div class="mb-2">
              <label for="Section_7_description1_en" class="form-label"><img src="https://flagsapi.com/US/shiny/24.png" class="mr-2"> Deskripsi  </label>
              <input type="text" class="form-control" name="Section_7_description1_en" id="Section_7_description1_en" placeholder="Input Deskripsi" aria-describedby="Section_7_description1_en" value="{{$section7['description1_en'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Address  input -->
            <div class="mb-2">
              <label for="Section_7_address" class="form-label"> Address <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_7_address" id="Section_7_address" required placeholder="Input Address" aria-describedby="Section_7_address" value="{{$section7['address'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- No. Phone  input -->
            <div class="mb-2">
              <label for="Section_7_phone" class="form-label"> No. Phone <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_7_phone" id="Section_7_phone" required placeholder="Input No. Phone" aria-describedby="Section_7_phone" value="{{$section7['phone'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Email  input -->
            <div class="mb-2">
              <label for="Section_7_email" class="form-label"> Email <b>(*)</b>
              </label>
              <input type="email" class="form-control" name="Section_7_email" id="Section_7_email" required placeholder="Input Email" aria-describedby="Section_7_email" value="{{$section7['email'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_7-add-here"></div>
  </div>
  <div id="Section_8" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Section 8</h1>
    </div>
    <div class="row">
      <div class="col-md-12">
        <div class="row dynamic-row">
        <input type="hidden" name="Section_8_id" value="{{$section8['id'] ?? '' }}">
          <div class="col-md-12 tests">
            <!-- Whatsapp  input -->
            <div class="mb-2 hidden">
              <label for="Section_8_whatsapp" class="form-label"> Whatsapp <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_8_whatsapp" id="Section_8_whatsapp" required placeholder="Input Whatsapp" aria-describedby="Section_8_whatsapp" value="{{ $section8['whatsapp'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Tiktok  input -->
            <div class="mb-2">
              <label for="Section_8_tiktok" class="form-label"> Linkedin <b>(*)</b>
            </label>
            <input type="text" class="form-control" name="Section_8_tiktok" id="Section_8_tiktok" required placeholder="Input Tiktok" aria-describedby="Section_8_tiktok" value="{{ $section8['tiktok'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Instagram  input -->
            <div class="mb-2">
              <label for="Section_8_instagram" class="form-label"> Instagram <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_8_instagram" id="Section_8_instagram" required placeholder="Input Instagram" aria-describedby="Section_8_instagram" value="{{ $section8['instagram'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Facebook  input -->
            <div class="mb-2">
              <label for="Section_8_facebook" class="form-label"> Facebook <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_8_facebook" id="Section_8_facebook" required placeholder="Input Facebook" aria-describedby="Section_8_facebook" value="{{ $section8['facebook'] ?? '' }}">
            </div>
          </div>
          <div class="col-md-12 tests">
            <!-- Twitter  input -->
            <div class="mb-2">
              <label for="Section_8_twitter" class="form-label"> Twitter <b>(*)</b>
              </label>
              <input type="text" class="form-control" name="Section_8_twitter" id="Section_8_twitter" required placeholder="Input Twitter" aria-describedby="Section_8_twitter" value="{{ $section8['twitter'] ?? '' }}">
            </div>
          </div>
        </div>
      </div>
    </div>
    <div id="Section_8-add-here"></div>
  </div>
  
  <div class="btn-footer">
    <button type="button" class="btn btn-default btn-sm btn-block mt-4">Cancel</button>
    <button type="submit" class="btn btn-main btn-sm btn-block mt-4" id="btn-save">Save </button>
  </div>
</form>