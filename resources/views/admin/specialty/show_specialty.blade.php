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
                            <h6>Specialties</h6>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-center">

                                    @include('admin.specialty.add_specialty')

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

                                                Image
                                            </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Specialty Name
                                            </th>

                                            <th
                                            class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                            Duration
                                        </th>

                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Specialty Description
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Price
                                            </th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                                Status
                                            </th>

                                            <th class="text-secondary opacity-7"></th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($specialties as $data)
                                        <tr class="text-center">

                                            <td>
                                                <img src="{{ asset('storage/' . $data->image) }}" alt="Specialty Image"
                                                    width="60px"
                                                    style="object-fit: cover; height: 60px; border-radius: 6px;">

                                            </td>


                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $data->name }}
                                                </p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $data->time }}
                                                </p>
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">
                                                    {{ $data->description }}
                                                </p>
                                            </td>

                                            <td class="align-middle">
                                                <p class="text-xs font-weight-bold mb-0">
                                                    ${{ number_format($data->price, 2) }}
                                                </p>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="d-inline-block text-xs font-weight-bold rounded px-2 py-1 text-white
                                                     {{ $data->is_active ? 'bg-success' : 'bg-danger' }}" style="min-width: 80px;">
                                                    {{ $data->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>


                                            <td class="align-middle">
                                                @include('admin.specialty.update_specialty')
                                            </td>

                                            <td class="align-middle">
                                                <a href="{{ url('/admin/delete_specialty/' . $data->id) }}"
                                                    onclick="deletespecialty({{ $data->id }})"
                                                    class="text-danger font-weight-bold text-xs" data-toggle="tooltip"
                                                    data-original-title="Delete specialty">
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
                                {{ $specialties->render('admin.pagination') }}
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
