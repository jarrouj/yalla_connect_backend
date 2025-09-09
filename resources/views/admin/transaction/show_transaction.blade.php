<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.css')
</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('admin.sidebar')
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        @include('admin.navbar')
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Transactions</h6>
                        </div>

                        {{-- <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-center">

                                    @include('admin.order.add_order')

                                </div>
                            </div>
                        </div> --}}

                        {{-- Filters --}}
                        {{-- <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-block w-75 m-auto">
                                    <form action="{{ route('admin.orders') }}" method="GET"
                                        class="row g-2 align-items-end">
                                        <div class="col-md-6">
                                            <label class="form-label d-block text-center mb-1">Status</label>
                                            <select name="status" class="form-select" onchange="this.form.submit()">
                                                <option value="all" {{ ($selectedStatus ??
                                                    request('status','all'))==='all' ? 'selected' : '' }}>All</option>
                                                <option value="completed" {{ ($selectedStatus ??
                                                    request('status'))==='completed' ? 'selected' : '' }}>Completed
                                                </option>
                                                <option value="pending" {{ ($selectedStatus ??
                                                    request('status'))==='pending' ? 'selected' : '' }}>Pending</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label d-block text-center mb-1">Date</label>
                                            <select name="period" class="form-select" onchange="this.form.submit()">
                                                <option value="all" {{ ($selectedPeriod ??
                                                    request('period','all'))==='all' ? 'selected' : '' }}>All time
                                                </option>
                                                <option value="today" {{ ($selectedPeriod ??
                                                    request('period'))==='today' ? 'selected' : '' }}>Today</option>
                                                <option value="week" {{ ($selectedPeriod ?? request('period'))==='week'
                                                    ? 'selected' : '' }}>This week</option>
                                                <option value="month" {{ ($selectedPeriod ??
                                                    request('period'))==='month' ? 'selected' : '' }}>This month
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div> --}}



                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr class="text-center">

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                User Name
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Type of Deposit
                                            </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Amount
                                            </th>

                                            {{--
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Address
                                            </th> --}}

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Date
                                            </th>


                                            <th class="text-secondary opacity-7"></th>


                                        </tr>
                                    </thead>
                                    <tbody id="SearchResults">
                                        @forelse ($transactions as $data)
                                        <tr class="text-center">




                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $data->user->first_name }} {{ $data->user->last_name }}
                                                </p>
                                            </td>

                                            <td>
                                             <p class="text-xs font-weight-bold mb-0">
                                                    {{ $data->type_of_transaction }}
                                                </p>
                                            </td>


                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $data->amount }}
                                                </p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $data->created_at->format('d M, Y') }}
                                                </p>
                                            </td>



                                            <td class="align-middle">
                                                <a href="{{ url('admin/delete_transaction', $data->id) }}"
                                                    class="text-danger font-weight-bold text-xs" data-toggle="tooltip"
                                                    data-original-title="Edit transaction"
                                                    onclick="return confirm('Are you sure you want to delete this transaction?')">
                                                    Delete
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="16">
                                                <p class="text-xs text-center text-danger font-weight-bold mb-0">
                                                    No Data !
                                                </p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $transactions->render('admin.pagination') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.footer')
        </div>
    </main>




    @include('admin.script')

</body>

</html>
