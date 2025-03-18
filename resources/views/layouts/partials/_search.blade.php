@if(Request::segment(1) == "report-followup")
    <div class="SEARCH">
        <div class="row w-100">
            <div class="col-md-3">
                <div class="d-flex gap-3 itemscenter">
                    <a href="{{ url()->previous() }} ">
                        <img src="{{ asset('assets/back.png') }}"></a>
                    </a>
                    <div class="input-group">
                        <input type="date" class="form-control form-control-sm" placeholder="Search data..." id="search-datatable">
                    </div>
                </div>
            </div>
            <div class="col-md-2">
                <select name="search_kind_of_report" id="search_kind_of_report" class="form-control">
                    <option value="">Kind of Report</option>
                    <option value="Investigation">Investigation</option>
                    <option value="Meeting">Meeting</option>
                    <option value="MWT">MWT</option>
                    <option value="PEKA">PEKA</option>
                    <option value="Audit">Audit</option>
                    <option value="EmergencyDrils">Emergency Drils</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="search_status" id="search_status" class="form-control">
                    <option value="">Status</option>
                    <option value="Open">Open</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
            </div>

            <div class="col-md-3">
                <form>
                    <select class="form-control form-control-sm" id="form-select">
                        @foreach($data['forms'] as $option)
                            <option value="{{ url($option['route_link']) }}/?project_id={{$data['project']['id']}}" {{ Request::path() == $option['route_link'] ? 'selected' : '' }}>{{ $option['name_alias'] }}</option>
                        @endforeach
                    </select>
                </form>
            </div>

        </div>
    </div>
@else
    <div class="SEARCH">
        <div class="row w-100">
            <div class="col-md-8">
                <div class="d-flex gap-3 itemscenter">
                    <a href="{{ url()->previous() }} ">
                        <img src="{{ asset('assets/back.png') }}"></a>
                    </a>
                    <div class="input-group">
                        <span class="input-group-text"> <img src="{{ asset('assets/search.png') }}"> </span>
                        @php
                            $type = '';
                            if (request()->segment(1) == "report-mwt" || request()->segment(1) == "report-dcu") {
                                $type = "date";
                            } else {
                                $type = "text";
                            }
                        @endphp
                        <input type="{{ $type }}" class="form-control form-control-sm" placeholder="Search data..." id="search-datatable">
                    </div>
                    <button type="button" class="btn btn-main btn-md" onclick="searchTerm()">Search</button>
                </div>
            </div>
            <div class="col-md-4">
                <form>
                    <select class="form-control form-control-sm" id="form-select">
                        @foreach($data['forms'] as $option)
                            <option value="{{ url($option['route_link']) }}/?project_id={{$data['project']['id']}}" {{ Request::path() == $option['route_link'] ? 'selected' : '' }}>{{ $option['name_alias'] }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>
    </div>
@endif
