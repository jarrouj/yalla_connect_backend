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
    const ctx = document.getElementById('myChart');
    const revenueData = 0;

    let dates = Object.keys(revenueData);
    let firstDate = new Date(dates[0]);
    let lastDate = new Date(dates[dates.length - 1]);
    let durationInDays = (lastDate - firstDate) / (1000 * 3600 * 24);

    let labelsToShow;
    if (durationInDays > 30) {
        // Calculate monthly revenue and show only month names
        let monthlyRevenue = {};
        dates.forEach(dateString => {
            const date = new Date(dateString);
            const monthYear = `${date.toLocaleString('en-US', { month: 'long' })} ${date.getFullYear()}`;
            if (monthlyRevenue[monthYear]) {
                monthlyRevenue[monthYear] += revenueData[dateString];
            } else {
                monthlyRevenue[monthYear] = revenueData[dateString];
            }
        });
        labelsToShow = Object.keys(monthlyRevenue);
        dataToShow = Object.values(monthlyRevenue);
    } else {
        // Show dates as "Month Day"
        labelsToShow = dates.map(dateString => {
            const date = new Date(dateString);
            return `${date.toLocaleString('en-US', { month: 'long' })} ${date.getDate()}`;
        });
        dataToShow = Object.values(revenueData);
    }

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labelsToShow,
            datasets: [{
                label: 'Revenue',
                data: dataToShow,
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>




    @include('admin.script')


</body>

</html>
