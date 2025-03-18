<div class="card">
    <div class="card-header">
        <h2>10 Most Viewed Properties</h2>
        <div style="display: flex; justify-content: flex-start; align-items: center; gap: 20px;">
            <div class="dropdown">
                <div class="dropdown-toggle" id="dropdownBtn10">-</div>
                <div class="dropdown-menu" id="monthDropdown10">

                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <div class="selected-year" id="selectedYear10">-</div>
                        <div class="year-buttons">
                            <button class="arrow-btn" id="prevYear10">&lt;</button>
                            <button class="arrow-btn" id="nextYear10">&gt;</button>
                        </div>
                    </div>

                    <div class="months-container">
                        <button class="month-btn-ui month-btn10" data-value="jan-2024">Jan</button>
                        <button class="month-btn-ui month-btn10" data-value="feb-2024">Feb</button>
                        <button class="month-btn-ui month-btn10" data-value="mar-2024">Mar</button>
                        <button class="month-btn-ui month-btn10" data-value="apr-2024">Apr</button>
                        <button class="month-btn-ui month-btn10" data-value="may-2024">May</button>
                        <button class="month-btn-ui month-btn10" data-value="jun-2024">Jun</button>
                        <button class="month-btn-ui month-btn10" data-value="jul-2024">Jul</button>
                        <button class="month-btn-ui month-btn10" data-value="aug-2024">Aug</button>
                        <button class="month-btn-ui month-btn10" data-value="sep-2024">Sep</button>
                        <button class="month-btn-ui month-btn10" data-value="oct-2024">Oct</button>
                        <button class="month-btn-ui month-btn10" data-value="nov-2024">Nov</button>
                        <button class="month-btn-ui month-btn10" data-value="dec-2024">Dec</button>
                    </div>

                    <div class="clear-btn" id="clearSelection10">Clear Selection</div>
                </div>
            </div>
            <div class="dropdown">
                <div class="dropdown-toggle" id="select-category" onclick="toggleDropdown()">-</div>
                <div class="dropdown-menu" id="propertyDropdown">
                </div>
            </div>
        </div>
    </div>
    <canvas id="visitorsChart10"></canvas>
</div>

<script>
    const selectedYear10 = document.getElementById('selectedYear10');
    const selectCategory10 = document.getElementById('select-category');
    let selectedCategory = null;

    async function fetchPropertyCategories() {
        try {
            const formData = new FormData();
            formData.append('token', token);

            const response = await fetch('/api/superadmin/dashboard/master-property-categories', {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                throw new Error('Failed to fetch property categories');
            }

            const result = await response.json();
            if (result.status) {
                renderDropdown(result.data);
            } else {
                console.error(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function selectCategoryFunction(category) {
        selectCategory10.innerText = category.name;
        selectedCategory = category.id;
        console.log(`Selected Category ID: ${selectedCategory}`);
    }

    function renderDropdown(categories) {
        const dropdownMenu = document.getElementById('propertyDropdown');
        dropdownMenu.innerHTML = '';

        categories.forEach((category, idx) => {
            const item = document.createElement('div');
            item.classList.add('dropdown-item');
            item.innerText = category.name;
            item.dataset.id = category.id;
            item.addEventListener('click', () => selectCategoryFunction(category));
            dropdownMenu.appendChild(item);

            if (idx === 0) {
                item.classList.add('selected');
                selectCategory10.innerText = category.name;
                selectedCategory = category.id;
                console.log(`First Selected Category ID: ${category.id}`);
            }
        });

        fetchTenMostViewedProperties(currentMonth + 1, currentYear);
    }

    fetchPropertyCategories();

    async function fetchTenMostViewedProperties(month, year) {
        try {
            if (!selectedCategory || !year) {
                console.error('Missing parameters:', { month, year, category: selectedCategory });
                return;
            }

            const formData = new FormData();
            formData.append('token', token);
            formData.append('monthly_visitor_month_filter', month + 1);
            formData.append('monthly_visitor_year_filter', year);
            formData.append('category_id', selectedCategory);

            const response = await fetch('/api/superadmin/dashboard/ten-most-viewed-properties', {
                method: 'POST',
                body: formData,
            });

            if (!response.ok) {
                throw new Error('Failed to fetch ten most viewed properties');
            }

            const result = await response.json();
            if (result.status) {
                renderChart(result.data);
            } else {
                console.error(result.message);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    let visitorsChartInstance = null;

    function renderChart(data) {
        const ctx10 = document.getElementById('visitorsChart10').getContext('2d');

        const labels = data.map(item => item.property_address);
        const visitorCounts = data.map(item => item.total_visitors);

        const gradient10 = ctx10.createLinearGradient(0, 0, 0, 400);
        gradient10.addColorStop(0, 'rgba(0, 128, 0, 1)');
        gradient10.addColorStop(1, 'rgba(0, 128, 0, 0.6)');

        if (visitorsChartInstance) {
            visitorsChartInstance.destroy();
        }

        visitorsChartInstance = new Chart(ctx10, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Visitors',
                    data: visitorCounts,
                    backgroundColor: gradient10,
                    borderRadius: 10,
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                },
                scales: {
                    x: {
                        grid: { display: false }
                    },
                    y: {
                        grid: {
                            drawBorder: false,
                            drawOnChartArea: true
                        }
                    }
                }
            }
        });
    }

    function toggleDropdown() {
        const dropdownMenu = document.getElementById("propertyDropdown");
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    }

    window.onclick = function (event) {
        if (!event.target.matches(".dropdown-toggle")) {
            const dropdowns = document.getElementsByClassName("dropdown-menu");
            for (let i = 0; i < dropdowns.length; i++) {
                const openDropdown = dropdowns[i];
                if (openDropdown.style.display === "block") {
                    openDropdown.style.display = "none";
                }
            }
        }
    };

    initDropdown('monthDropdown10', 'dropdownBtn10', '.month-btn10', 'clearSelection10', 'selectedYear10', (month, year) => fetchTenMostViewedProperties(month, year), 'prevYear10', 'nextYear10');

    const interval = setInterval(() => {
        if (selectedCategory !== null) {
            console.log("Variable is no longer null:", selectedCategory);
            fetchTenMostViewedProperties(currentMonth, currentYear);
            clearInterval(interval);
        }
        console.log("interval run");
    }, 100);
</script>