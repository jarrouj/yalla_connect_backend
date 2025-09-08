<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.css')
</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('admin.sidebar')

    <main class="main-content position-relative border-radius-lg ">
        @include('admin.navbar')

        <div class="container-fluid py-4">

            <div class="row">

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Number of Users</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $NumberOfUsers }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">

                                    <div
                                    class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                    <i class="bi bi-people-fill text-lg opacity-10" aria-hidden="true"></i>
                                </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Number of Transactions Today</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $TransactionCount }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                     <div
                                        class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i class="bi bi-credit-card text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Revenue Made Today
                                        </p>
                                        <h5 class="font-weight-bolder">
                                            {{ $revenue_tdy }}$
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                        <i class="bi bi-cash text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">None Finished Orders
                                        </p>
                                        <h5 class="font-weight-bolder">
                                            {{ $non_finished_orders }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                        <i class="bi bi-box-fill text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Revenue Made This Month</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $revenue_this_month }}$
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                    class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                    <i class="bi bi-cash-coin text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Active Promo Codes</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $active_promo_codes }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-success shadow-success text-center rounded-circle">
                                        <i class="bi bi-percent text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-3 col-sm-6 mb-xl-0 mb-4">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Active Specialties</p>
                                        <h5 class="font-weight-bolder">
                                            {{ $active_specialties }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-primary shadow-primary text-center rounded-circle">
                                        <i class="bi bi-boxes text-lg opacity-10" aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-3 col-sm-6">
                    <div class="card h-100">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">Completed Orders Today
                                        </p>
                                        <h5 class="font-weight-bolder">
                                            {{ $completed_orders_tdy }}
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-warning shadow-warning text-center rounded-circle">
                                        <i class="bi bi-box-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-5 w-70 m-auto">
                <canvas id="myChart"></canvas>
              </div>


            {{-- <div class="row">
                <div class="col-12 my-4">
                    <div class="card">
                        <div class="card-body p-3">
                            <div class="row">
                                <div class="col-8">
                                    <div class="numbers">
                                        <p class="text-sm mb-0 text-uppercase font-weight-bold">
                                            The Equation used in the system is
                                        </p>
                                        <h5 class="font-weight-bolder">
                                            [Product Price + (Product Price X Percentage / 100)] X Number of Days
                                        </h5>
                                    </div>
                                </div>
                                <div class="col-4 text-end">
                                    <div
                                        class="icon icon-shape bg-gradient-danger shadow-danger text-center rounded-circle">
                                        <i class="bi bi-bar-chart text-lg opacity-10" style="font-size: 20px"
                                            aria-hidden="true"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> --}}

            <div class="row mt-4">
                <div class="col-lg-12 mb-lg-0 mb-4">
                    {{-- @include('admin.home-users') --}}
                </div>
            </div>

            @include('admin.footer')
        </div>
    </main>

{{-- {{ Charts}} --}}
     {{-- const revenueData = {!! json_encode($revenue) !!}; --}}

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Data coming from backend
    const revenueDaily   = @json($revenueDaily ?? []);
    const revenueMonthly = @json($revenueMonthly ?? []);
    const chartStart     = @json($chartStart ?? null);
    const chartEnd       = @json($chartEnd ?? null);

    const ctx = document.getElementById('myChart');

    // Helper: safe keys/values
    const dailyDates = Object.keys(revenueDaily || {});
    const hasDaily = dailyDates.length > 0;

    let labelsToShow = [];
    let dataToShow   = [];

    if (!hasDaily) {
        // No data — show an empty chart
        labelsToShow = [];
        dataToShow = [];
    } else {
        // Determine duration in days from first to last daily key
        const firstDate = new Date(dailyDates[0]);
        const lastDate  = new Date(dailyDates[dailyDates.length - 1]);
        const durationInDays = (lastDate - firstDate) / (1000 * 3600 * 24);

        if (durationInDays > 30 && Object.keys(revenueMonthly || {}).length > 0) {
            // Use monthly series computed on backend
            labelsToShow = Object.keys(revenueMonthly).map(ym => {
                const [y, m] = ym.split('-');
                const d = new Date(Number(y), Number(m) - 1, 1);
                return d.toLocaleString('en-US', { month: 'long', year: 'numeric' });
            });
            dataToShow = Object.values(revenueMonthly);
        } else {
            // Use daily series
            labelsToShow = dailyDates.map(ds => {
                const d = new Date(ds);
                return `${d.toLocaleString('en-US', { month: 'long' })} ${d.getDate()}`;
            });
            dataToShow = dailyDates.map(ds => revenueDaily[ds]);
        }
    }

    // Init Chart.js (make sure Chart.js is included on the page)
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsToShow,
            datasets: [{
                label: 'Revenue',
                data: dataToShow,
                borderWidth: 2,
                tension: 0.25,
                pointRadius: 2
            }]
        },
        options: {
            plugins: {
                tooltip: { mode: 'index', intersect: false },
                legend: { display: true }
            },
            interaction: { mode: 'index', intersect: false },
            scales: {
                x: { ticks: { autoSkip: true, maxRotation: 0 } },
                y: { beginAtZero: true }
            }
        }
    });
</script>



    @include('admin.script')


</body>

</html>
