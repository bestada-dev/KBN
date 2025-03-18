 <div class="card">
     <div class="card-header">
         <h2>Monthly Visitors</h2>
         <div class="dropdown">
             <div class="dropdown-toggle" id="dropdownBtn">-</div>
             <div class="dropdown-menu" id="monthDropdown">

                 <div style="display: flex; justify-content: space-between; align-items: center;">
                     <div class="selected-year" id="selectedYear">2024</div>
                     <div class="year-buttons">
                         <button class="arrow-btn" id="prevYear">&lt;</button>
                         <button class="arrow-btn" id="nextYear">&gt;</button>
                     </div>
                 </div>

                 <div class="months-container">
                     <button class="month-btn-ui month-btn" data-value="jan-2024">Jan</button>
                     <button class="month-btn-ui month-btn" data-value="feb-2024">Feb</button>
                     <button class="month-btn-ui month-btn" data-value="mar-2024">Mar</button>
                     <button class="month-btn-ui month-btn" data-value="apr-2024">Apr</button>
                     <button class="month-btn-ui month-btn" data-value="may-2024">May</button>
                     <button class="month-btn-ui month-btn" data-value="jun-2024">Jun</button>
                     <button class="month-btn-ui month-btn" data-value="jul-2024">Jul</button>
                     <button class="month-btn-ui month-btn" data-value="aug-2024">Aug</button>
                     <button class="month-btn-ui month-btn" data-value="sep-2024">Sep</button>
                     <button class="month-btn-ui month-btn" data-value="oct-2024">Oct</button>
                     <button class="month-btn-ui month-btn" data-value="nov-2024">Nov</button>
                     <button class="month-btn-ui month-btn" data-value="dec-2024">Dec</button>
                 </div>

                 <div class="clear-btn" id="clearSelection">Clear Selection</div>
             </div>
         </div>
     </div>
     <div class="loading-indicator" id="loadingIndicator">
         <span>Loading...</span>
     </div>
     <canvas id="visitorsChart"></canvas>
 </div>

 <script>
     const loadingIndicator = document.getElementById('loadingIndicator');

     const ctx = document.getElementById('visitorsChart').getContext('2d');
     const gradient = ctx.createLinearGradient(0, 0, 0, 400);
     gradient.addColorStop(0, 'rgba(0, 128, 0, 1)');
     gradient.addColorStop(1, 'rgba(0, 128, 0, 0.6)');

     const visitorsChart = new Chart(ctx, {
         type: 'bar',
         data: {
             labels: Array.from({
                 length: 31
             }, (_, i) => i + 1),
             datasets: [{
                 data: [],
                 backgroundColor: gradient,
                 borderRadius: 90,
             }]
         },
         options: {
             responsive: true,
             plugins: {
                 legend: {
                     display: false
                 }
             },
             scales: {
                 x: {
                     grid: {
                         display: false
                     }
                 },
                 y: {
                     grid: {
                         drawBorder: false,
                         drawOnChartArea: true,
                         drawTicks: false
                     },
                     beginAtZero: true
                 }
             }
         }
     });

     function updateChart(month, year) {
         loadingIndicator.style.display = 'block';

         const formData = new FormData();
         formData.append('token', token);
         formData.append('monthly_visitor_month_filter', month + 1);
         formData.append('monthly_visitor_year_filter', year);

         fetch(`/api/superadmin/dashboard/monthly-visitors`, {
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

                 visitorsChart.data.labels = Array.from({
                     length: data.length
                 }, (_, i) => i + 1);
                 visitorsChart.data.datasets[0].data = data;
                 visitorsChart.update(); 
             })
             .catch(error => {
                 loadingIndicator.style.display = 'none';
                 console.error('Error fetching monthly visitors data:', error);
             });
     }

    initDropdown('monthDropdown', 'dropdownBtn', '.month-btn', 'clearSelection', 'selectedYear', (month, year) => updateChart(month, year), 'prevYear', 'nextYear');
 </script>