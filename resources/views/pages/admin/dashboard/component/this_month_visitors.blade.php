<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

<style>
    .hide-in-pdf {
        display: none !important;
    }
</style>

<div class="dashboard">
    <div class="header">
        <div>
            <h1>Dashboard</h1>
            <span id="dynamicDate"></span>
        </div>
        <button class="pdf-button">
            <img src="{{ asset('assets/dashboard/logout.png') }}" alt="PDF" />
            Export PDF
        </button>
    </div>

    <div class="visitors">
        <div class="card">
            <h3>This Month</h3>
            <p>{{ $visitors['dashboard']['month']['this'] }}</p>
        </div>
        <div class="card">
            <h3>Last Month</h3>
            <p>{{ $visitors['dashboard']['month']['last'] }}</p>
        </div>
        <div class="card">
            <h3>This Year</h3>
            <p>{{ $visitors['dashboard']['year']['this'] }}</p>
        </div>
        <div class="card">
            <h3>Last Year</h3>
            <p>{{ $visitors['dashboard']['year']['last'] }}</p>
        </div>
    </div>
</div>

<script>
    function setDynamicDate() {
        const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const currentDate = new Date().toLocaleDateString('en-US', options);
        document.getElementById('dynamicDate').innerText = currentDate;
    }

    setDynamicDate();

    async function exportPDF() {
        const dashboard = document.querySelector('.dashboard-app');

        const elementsToHide = document.querySelectorAll('.pdf-button');
        elementsToHide.forEach(el => el.classList.add('hide-in-pdf'));

        const originalStyle = dashboard.style.cssText;

        dashboard.style.overflow = 'visible';
        dashboard.style.height = 'auto';

        const canvas = await html2canvas(dashboard, {
            scale: 2,
            useCORS: true,
        });

        dashboard.style.cssText = originalStyle;
        elementsToHide.forEach(el => el.classList.remove('hide-in-pdf'));

        const imgData = canvas.toDataURL('image/jpeg', 0.7);

        const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
        const pdfWidth = pdf.internal.pageSize.getWidth();
        const pdfHeight = pdf.internal.pageSize.getHeight();

        const canvasWidth = canvas.width;
        const canvasHeight = canvas.height;

        const imgWidth = pdfWidth;
        const imgHeight = (canvasHeight * imgWidth) / canvasWidth;

        let heightLeft = imgHeight;
        let position = 0;

        while (heightLeft > 0) {
            pdf.addImage(imgData, 'JPEG', 0, position, imgWidth, imgHeight);
            heightLeft -= pdfHeight;
            position -= pdfHeight;

            if (heightLeft > 0) {
                pdf.addPage();
            }
        }

        pdf.save('dashboard.pdf');
    }

    document.querySelector('.pdf-button').addEventListener('click', exportPDF);
</script>