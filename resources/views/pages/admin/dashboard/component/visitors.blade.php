<div class="visitors-container">
  <h2>Visitors</h2>
  <table class="visitors-table">
    <thead>
      <tr>
        <th>Country</th>
        <th>Visitor</th>
        <th>Number of Visitors</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($visitors['countries'] as $vc)
      <tr>
        <td>
          <div class="country">
            <img src="https://flagcdn.com/{{ $vc['country_code']}}.svg" alt="{{ $vc['country_name'] }}" class="country-flag" />
            {{ $vc['country_name'] }}
          </div>
        </td>
        <td>
          <div class="visitor-avatars">
            @foreach($vc['visitor_users'] as $vu)
              @if($vu != null)
                <img src="https://ui-avatars.com/api/?name={{ $vu['name'] }}" alt="{{ $vu['name'] }}" />
              @endif
            @endforeach
          </div>
        </td>
        <td>{{ $vc['total_visitors']}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>