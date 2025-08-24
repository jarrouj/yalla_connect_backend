<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.css')
</head>

<body class="g-sidenav-show bg-gray-100">
    @include('admin.sidebar')

    <main class="main-content position-relative border-radius-lg">
        @include('admin.navbar')

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">

                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">Promos</h6>
                        </div>

                        {{-- Create / Add Promo --}}
                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-center">
                                    @include('admin.promo.add_promo')
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr class="text-center">
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Code</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Discount</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Active</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Global</th>
                                            <th class="text-secondary opacity-7"></th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @forelse ($promo as $data)
                                        <tr class="text-center">
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ strtoupper($data->code) }}
                                                </p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ (int)$data->percent }}%
                                                </p>
                                            </td>

                                            <td>
                                                @if($data->is_active)
                                                <span class="badge badge-sm bg-gradient-success w-50">Active</span>
                                                @else
                                                <span class="badge badge-sm bg-gradient-danger w-50">Not Active</span>
                                                @endif
                                            </td>

                                            <td>
                                                @if($data->global_one_time)
                                                <span class="badge badge-sm bg-gradient-info w-75">Global</span>
                                                @else
                                                <span class="badge badge-sm bg-gradient-secondary w-75">Per User</span>
                                                @endif
                                            </td>

                                            {{-- Edit --}}
                                            <td class="align-middle">
                                                @include('admin.promo.update_promo', ['promo' => $data])
                                            </td>

                                            {{-- Delete --}}
                                            <td class="align-middle">
                                                <form action="{{ route('admin.promos.destroy', $data->id) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this promo?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-link text-danger font-weight-bold text-xs p-0 m-0">
                                                        Delete <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="6">
                                                <p class="text-xs text-center text-danger font-weight-bold mb-0">
                                                    No Data !
                                                </p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{-- Pagination --}}
                                <div class="px-3 py-2">
                                    {{ $promo->links('admin.pagination') }}
                                </div>
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
