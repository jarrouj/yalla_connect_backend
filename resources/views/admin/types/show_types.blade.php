<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.css')
</head>

<body class="g-sidenav-show bg-gray-100">

    @include('admin.sidebar')
    <main class="main-content position-relative border-radius-lg">
        <!-- Navbar -->
        @include('admin.navbar')
        <!-- End Navbar -->

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Subcategories</h6>
                            @include('admin.types.add_types')
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0 text-center">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Image</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Subcategory Name</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Parent Category</th>
                                            <th class="text-secondary opacity-7">Edit</th>
                                            <th class="text-secondary opacity-7">Delete</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subcategories as $sub)
                                        <tr>
                                            <td>
                                                @if ($sub->image)
                                                <img src="{{ asset('storage/' . $sub->image) }}" alt="Subcategory Image"
                                                    style="height: 40px; width: 40px; object-fit: cover; border-radius: 5px;">
                                                @else
                                                <span class="text-xs text-muted">No image</span>
                                                @endif
                                            </td>

                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $sub->name }}</p>
                                            </td>
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $sub->category->name ??
                                                    'N/A' }}</p>
                                            </td>
                                            <td class="align-middle">
                                                @include('admin.types.update_types', ['subcategory' => $sub])
                                            </td>
                                            <td class="align-middle">
                                                <form action="{{ url('/admin/delete_subcategory/' . $sub->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-link text-danger font-weight-bold text-xs">
                                                        Delete <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4">
                                                <p class="text-xs text-center text-danger font-weight-bold mb-0">
                                                    No Subcategories Found!
                                                </p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{ $subcategories->render('admin.pagination') }}
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
