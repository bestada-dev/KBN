<style>
    div#Section_5_Poin_poin .items-center,
    div#Section_5_Poin_poin .items-center,
    div#Section_6_Logo .items-center{
        display: flex !important;
        align-items: start !important;
        margin-top: 1.6rem !important;
    }
    .dynamic-row {
      background-color: #f8f9fa;
      border-radius: 8px;
      padding: 20px;
    }

    .dynamic-row input,
    .dynamic-row textarea {
      border-radius: 5px;
    }

    .dynamic-row .btn-sm {
      padding: 0.5rem 1rem;
    }

    .dynamic-row .remove-content-row {
      color: #fff;
      background-color: #dc3545;
      border: none;
      padding: 0.5rem 1rem;
      border-radius: 5px;
    }

    .dynamic-row .remove-content-row:hover {
      background-color: #c82333;
    }

</style>

<form id="home-form" action="{{url('superadmin/landing-page/benefit/update')}}" method="POST" enctype="multipart/form-data"
      class="p-4 pt-3">
    @csrf
    @method('PUT')

  <div id="benefit" class="mb-3">
    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Benefit</h1>
    </div>
  
    <!-- Input global: image, title, sub_title -->
    <div class="row mb-4">
      <div class="col-md-4">
        <label for="image" class="form-label">Image <b>(*)</b></label>
        <input type="file" class="form-control" name="image" id="image">
        @if (!empty($benefit_title->image))
          <img src="{{ asset('storage/' . $benefit_title->image) }}" alt="Preview" class="mt-2" style="max-height: 100px;">
        @endif
      </div>
      <div class="col-md-4">
        <label for="title" class="form-label">Title <b>(*)</b></label>
        <input type="text" class="form-control" name="title" id="title" value="{{ $benefit_title->title ?? '' }}" placeholder="Input Title" required>
      </div>
      <div class="col-md-4">
        <label for="sub_title" class="form-label">Sub Title</label>
        <textarea class="form-control" name="sub_title" id="sub_title" rows="3" placeholder="Input Sub Title">{{ $benefit_title->sub_title ?? '' }}</textarea>
      </div>
      
    </div>

    <div class="between">
      <h1 style="font-size: 17px; margin-bottom: 1rem; color: rgb(58, 113, 176);">Benefit List</h1>
    </div>
  
    <div class="row" id="benefit-container">
      @foreach($benefit_list ?? [] as $index => $benefit)
        <div class="col-md-12 dynamic-row mb-3 p-3 rounded border shadow-sm d-flex justify-content-between align-items-center benefit-row" data-index="{{ $index }}">
          <!-- Benefit ID Hidden Field -->
          <input type="hidden" name="benefit[{{$index}}][id]" value="{{ $benefit['id'] ?? '' }}">
        
          <!-- Content (input for the body of the benefit) -->
          <div class="col-md-11 mb-2">
            <label for="content_text_{{$index}}" class="form-label">Content Text <b>(*)</b></label>
            <input type="text" class="form-control" name="benefit[{{$index}}][content]" id="content_text_{{$index}}" placeholder="Write the benefit content" value="{{ $benefit['content'] ?? '' }}">
          </div>
        
          <!-- Remove button -->
          <div class="col-md-1 d-flex justify-content-center align-items-center">
            <button type="button" class="btn btn-danger btn-sm remove-content-row">X</button>
          </div>
        </div>
      @endforeach
    </div>
    
    <!-- Button to add new benefit row -->
    <div class="d-flex justify-content-end mt-3">
      <button type="button" class="btn btn-primary btn-sm" id="add-benefit-row">Add Content</button>
    </div>

    
  </div>
  
  
  <div class="btn-footer">
    <button type="button" class="btn btn-default btn-sm btn-block mt-4">Cancel</button>
    <button type="submit" class="btn btn-main btn-sm btn-block mt-4" id="btn-save">Save </button>
  </div>
</form>