<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.css')
    <style>
        .no-bottom-margin {
    margin-bottom: 0 !important;
}
    </style>
</head>

<body class="g-sidenav-show bg-gray-100">

    @include('admin.sidebar')

    <main class="main-content position-relative border-radius-lg">
        @include('admin.navbar')

        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0 d-flex justify-content-between align-items-center">
                            <h6>Products</h6>
                        </div>

                        <div class="row mb-3">
                            <div class="col-12">
                                <div class="d-flex justify-content-center">

                                    @include('admin.product.add_product')
                                </div>
                            </div>
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
                                                Title</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Subcategory</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Voucher Code</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Price</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Category</th>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Description</th>
                                            <th class="text-secondary opacity-7"></th>
                                            <th class="text-secondary opacity-7"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($products as $product)
                                        <tr>
                                            <!-- Product Image -->
                                            <td>
                                                @if($product->image)
                                                <img src="{{ asset('images/products/' . $product->image) }}"
                                                    alt="Product" width="60">
                                                @else
                                                <span>No Image</span>
                                                @endif
                                            </td>

                                            <!-- Product Title -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $product->title }}</p>
                                            </td>

                                            <!-- Subcategory -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $product->subcategory->name
                                                    ?? 'N/A' }}</p>
                                            </td>

                                            <!-- Voucher Code -->
                                            {{-- <td>
                                                @if(isset($product->subcategory->name) &&
                                                strtolower($product->subcategory->name) === 'voucher')
                                                <span class="badge bg-info">{{ $product->code ?? 'N/A' }}</span>
                                                @else
                                                -
                                                @endif
                                            </td> --}}

                                            <td>
                                                @php
                                                    $isVoucher = isset($product->subcategory->name)
                                                        && strtolower($product->subcategory->name) === 'voucher';

                                                    // Works with both the new relation and your legacy single `code` column
                                                    $codes = collect(optional($product->codes)->pluck('code'))->filter();
                                                    if ($codes->isEmpty() && !empty($product->code)) {
                                                        $codes = collect([$product->code]);
                                                    }
                                                @endphp

                                                @if($isVoucher)
                                                    @if($codes->count() > 1)
                                                        <span class="badge bg-info"
                                                              data-bs-toggle="tooltip"
                                                              title="{{ $codes->implode(', ') }}">
                                                            Multiple
                                                        </span>
                                                    @elseif($codes->count() === 1)
                                                        <span class="badge bg-info">{{ $codes->first() }}</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                @else
                                                    -
                                                @endif
                                            </td>



                                            <!-- Price -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">${{
                                                    number_format($product->price, 2) }}</p>
                                            </td>

                                            <!-- Parent Category -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $product->category->name ??
                                                    'N/A' }}</p>
                                            </td>

                                            <!-- Description -->
                                            <td>
                                                <p class="text-xs font-weight-bold mb-0">{{ $product->description }}</p>
                                            </td>

                                            <!-- Edit (Modal Placeholder) -->
                                            <td class="align-middle">
                                                @include('admin.product.update_product', ['product' => $product])
                                            </td>

                                            <!-- Delete -->
                                            <td class="align-middle">
                                                <form action="{{ url('/admin/products/delete/' . $product->id) }}"
                                                    method="POST" onsubmit="return confirm('Are you sure?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-link text-danger font-weight-bold text-xs mb-0"
                                                        type="submit">
                                                        Delete <i class="bi bi-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="9">
                                                <p class="text-xs text-center text-danger font-weight-bold mb-0">
                                                    No Products Found!
                                                </p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                {{ $products->render('admin.pagination') }}
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            @include('admin.footer')
        </div>
    </main>

    @include('admin.script')

    <script>
        // Handle voucher code field toggle in Add & Edit modals
        document.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('select[name="type"]').forEach(select => {
                const codeWrapper = select.closest('form').querySelector('.voucher-code');

                function toggleCode() {
                    if (select.value === 'voucher') {
                        codeWrapper.style.display = 'block';
                    } else {
                        codeWrapper.style.display = 'none';
                        codeWrapper.querySelector('input').value = '';
                    }
                }

                select.addEventListener('change', toggleCode);
                toggleCode();
            });
        });
    </script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
      const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
      tooltipTriggerList.forEach(el => new bootstrap.Tooltip(el));
    });
    </script>




</body>



</html>
