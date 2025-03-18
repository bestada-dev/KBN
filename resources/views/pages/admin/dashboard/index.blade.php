@extends('layouts.app')

@section('title', 'Kinarya Alihdaya Mandiri')

@section('breadcumbTitle', 'Dashboard')

@section('breadcumbSubtitle', 'Dashboard')

@section('head')
<link rel="stylesheet" href="/assets/css/dashboard.css">
@endsection

@section('content')
     <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

     {{-- Global Script --}}
        <script>
            const getToken = () => localStorage.getItem('token');
            const currentDate = new Date();
            let currentMonth = currentDate.getMonth();
            let currentYear = currentDate.getFullYear();
            const monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

            function capitalizeFirstLetter(str) 
            {
                if (!str) return str;
                return str.charAt(0).toUpperCase() + str.slice(1).toLowerCase();
            }

            function getMonthSelected(classSelector)
            {
                const selectedButton = document.querySelector(classSelector);
                let month = null;
                if (selectedButton) {
                    month = selectedButton.getAttribute('data-value').split('-')[0];
                }
                
                return month != null ? capitalizeFirstLetter(month) : capitalizeFirstLetter(monthNames[currentMonth]);
            }

            function updateYear(currentYearSelected, selectedYearElement, toggleId, monthButtonsElements, updateChartCallback, menuId) 
            {
                const monthButtons = document.querySelectorAll(monthButtonsElements);
                const selectedYear = document.getElementById(selectedYearElement);
                const dropdownToggle = document.getElementById(toggleId);

                selectedYear.innerText = currentYearSelected;
                monthButtons.forEach(btn => {
                    const month = btn.getAttribute('data-value').split('-')[0];
                    btn.setAttribute('data-value', `${month}-${currentYearSelected}`);
                });

                let monthSelected = getMonthSelected(monthButtonsElements+'.selected');
                
                dropdownToggle.innerText = monthSelected + ", " + currentYearSelected;

                updateChartCallback(monthNames.indexOf(monthSelected), currentYearSelected);
                const dropdownMenu = document.getElementById(menuId);
                dropdownMenu.classList.toggle('active');
            }

            function initDropdown(menuId, toggleId, monthButtonsClass, clearSelectionId, selectedYearId, dropdownActionCallback, prevYearId, nextYearId)
            {
                const dropdownMenu = document.getElementById(menuId);
                const dropdownToggle = document.getElementById(toggleId);
                const monthButtons = document.querySelectorAll(monthButtonsClass);
                const clearButton = document.getElementById(clearSelectionId);
                const selectedYear = document.getElementById(selectedYearId);
                const prevYear = document.getElementById(prevYearId);
                const nextYear = document.getElementById(nextYearId);

                selectedYear.innerText = currentYear;
                
                dropdownToggle.addEventListener('click', () => dropdownMenu.classList.toggle('active'));

                monthButtons.forEach(button => {
                    button.addEventListener('click', () => {
                        monthButtons.forEach(btn => btn.classList.remove('selected'));
                        button.classList.add('selected');

                        const value = button.getAttribute('data-value').split('-');
                        dropdownToggle.innerText = `${capitalizeFirstLetter(value[0])}, ${value[1]}`;
                        let monthSelected = getMonthSelected(monthButtonsClass+'.selected');
                        console.log(monthSelected);
                        
                        dropdownMenu.classList.toggle('active')
                        dropdownActionCallback(monthNames.indexOf(monthSelected), currentYear);
                    });
                });

                clearButton.addEventListener('click', () => {
                    monthButtons.forEach(btn => btn.classList.remove('selected'));
                    dropdownToggle.innerText = monthNames[currentMonth] + ", " + currentYear;
                    dropdownMenu.classList.toggle('active')
                    dropdownActionCallback(currentMonth, currentYear);
                });

                prevYear.addEventListener(
                    'click', 
                    () => updateYear(
                        parseInt(selectedYear.innerText) - 1, 
                        selectedYearId, 
                        toggleId,  
                        monthButtonsClass, 
                        (month, year) => dropdownActionCallback(month, year), 
                        menuId,
                    ),
                );
                nextYear.addEventListener(
                    'click', 
                    () => updateYear(
                        parseInt(selectedYear.innerText) + 1, 
                        selectedYearId, 
                        toggleId, 
                        monthButtonsClass, 
                        (month, year) => dropdownActionCallback(month, year),
                        menuId,
                    ),
                );

                dropdownToggle.innerText = `${monthNames[currentMonth]}, ${currentYear}`;
                dropdownActionCallback(currentMonth, currentYear);
            }

            const token = getToken();
            if (!token) 
            {
                console.error('Token is missing!');
            }
        </script>
    {{-- Global Script --}}
    <div
        class="dashboard-app"
        style="
        margin: 7.5rem 1.25rem 0 auto; 
        width: 77%;
        height: calc(100vh - 7.5rem - 1.25rem);
        overflow-y: auto;
        -ms-overflow-style: none;
        scrollbar-width: none;
    ">
        @include('pages.admin.dashboard.component.this_month_visitors')

        @include('pages.admin.dashboard.component.monthly_visitors')

        <div style="display: flex; gap: 32px;">
            <div style="width: 50%;">
                @include('pages.admin.dashboard.component.monthly_visitors_by_country')
            </div>
            <div style="width: 50%;">
                @include('pages.admin.dashboard.component.available_property')
            </div>
        </div>
        @include('pages.admin.dashboard.component.ten_most_viewed_properties')

        @include('pages.admin.dashboard.component.visitors')
    </div>
@endsection