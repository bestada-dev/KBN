<div class="chart-container" style="margin: 24px 0;">
  <div class="chart-container-header" style="justify-content: space-between;">
    <h2>Monthly Visitors by Country</h2>
    <div class="dropdown">
      <div class="dropdown-toggle" id="dropdownBtn2">-</div>
      <div class="dropdown-menu" id="monthDropdown2">

          <div style="display: flex; justify-content: space-between; align-items: center;">
              <div class="selected-year" id="selectedYear2">-</div>
              <div class="year-buttons">
                  <button class="arrow-btn" id="prevYear2">&lt;</button>
                  <button class="arrow-btn" id="nextYear2">&gt;</button>
              </div>
          </div>

          <div class="months-container">
              <button class="month-btn-ui month-btn2" data-value="jan-2024">Jan</button>
              <button class="month-btn-ui month-btn2" data-value="feb-2024">Feb</button>
              <button class="month-btn-ui month-btn2" data-value="mar-2024">Mar</button>
              <button class="month-btn-ui month-btn2" data-value="apr-2024">Apr</button>
              <button class="month-btn-ui month-btn2" data-value="may-2024">May</button>
              <button class="month-btn-ui month-btn2" data-value="jun-2024">Jun</button>
              <button class="month-btn-ui month-btn2" data-value="jul-2024">Jul</button>
              <button class="month-btn-ui month-btn2" data-value="aug-2024">Aug</button>
              <button class="month-btn-ui month-btn2" data-value="sep-2024">Sep</button>
              <button class="month-btn-ui month-btn2" data-value="oct-2024">Oct</button>
              <button class="month-btn-ui month-btn2" data-value="nov-2024">Nov</button>
              <button class="month-btn-ui month-btn2" data-value="dec-2024">Dec</button>
          </div>

          <div class="clear-btn" id="clearSelection2">Clear Selection</div>
      </div>
  </div>
  </div>
  <div class="chart" id="chart"></div>
  <div class="flags" id="flags"></div>
</div>

<script>
  const chartContainer = document.getElementById("chart");
  const flagsContainer = document.getElementById("flags");

function updateChart2(month, year) 
{
        loadingIndicator.style.display = 'block';

        const formData = new FormData();
        formData.append('token', token);
        formData.append('monthly_visitor_month_filter', month + 1);
        formData.append('monthly_visitor_year_filter', year);

        fetch(`/api/superadmin/dashboard/monthly-visitors-by-country`, {
            method: 'POST',
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Unauthorized');
            }
            return response.json();
        })
        .then(data => {
            loadingIndicator.style.display = 'none';

            chartContainer.innerHTML = '';
            flagsContainer.innerHTML = '';

            data.data.forEach((country) => {
              console.log(country);
              
                const bar = document.createElement("div");
                bar.classList.add("bar");
                bar.style.height = `${country.total_visitors * 2}px`;
                const valueLabel = document.createElement("span");
                valueLabel.innerText = `${country.total_visitors}`;
                bar.appendChild(valueLabel);
                chartContainer.appendChild(bar);

                const flag = document.createElement("img");
                flag.src = `https://flagcdn.com/w40/${country.country_code.toLowerCase()}.png`;
                flag.alt = country.country_code;
                flagsContainer.appendChild(flag);
            });
        })
        .catch(error => {
            loadingIndicator.style.display = 'none';
            console.error('Error fetching monthly visitors data:', error);
        });
}

initDropdown('monthDropdown2', 'dropdownBtn2', '.month-btn2', 'clearSelection2', 'selectedYear2', (month, year) => updateChart2(month, year), 'prevYear2', 'nextYear2');
</script>