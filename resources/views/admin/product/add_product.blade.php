<button type="button" class="btn btn-dark mt-4" data-bs-toggle="modal" data-bs-target="#addProductModal">
    <i class="me-2 fs-6 bi bi-plus-lg"></i>
    Add Product
</button>

<div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Product</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('/admin/add_product') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <!-- Product Image -->
                    <div class="mb-3">
                        <label class="form-label">Product Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                    </div>

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="form-label">Product Name</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="4"></textarea>
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select" id="categorySelect" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategory -->
                    <div class="mb-3">
                        <label class="form-label">Subcategory</label>
                        <select name="subcategory_id" class="form-select" id="subcategorySelect" required>
                            <option value="" disabled selected>Select Subcategory</option>
                            @foreach($subcategories as $sub)
                            <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}">
                                {{ $sub->name }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Voucher Code -->
                    <div class="mb-3" id="voucherCodeWrapper" style="display: none;">
                        <label class="form-label">Voucher Code</label>
                        <input type="text" name="code" class="form-control">
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" required>
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">
                        Add <i class="bi bi-plus-lg"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const subcategorySelect = document.getElementById('subcategorySelect');
    const voucherWrapper = document.getElementById('voucherCodeWrapper');

    function toggleVoucherCode() {
        const selectedOption = subcategorySelect.options[subcategorySelect.selectedIndex]?.text.toLowerCase();
        voucherWrapper.style.display = selectedOption === 'voucher' ? 'block' : 'none';
        if (selectedOption !== 'voucher') {
            voucherWrapper.querySelector('input').value = '';
        }
    }

    subcategorySelect.addEventListener('change', toggleVoucherCode);
    toggleVoucherCode();
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const categorySelect = document.getElementById('categorySelect');
    const subcategorySelect = document.getElementById('subcategorySelect');
    const allOptions = Array.from(subcategorySelect.querySelectorAll('option[data-category]'));

    function filterSubcategories() {
        const selectedCategory = categorySelect.value;

        // Reset subcategory dropdown
        subcategorySelect.innerHTML = '<option value="" disabled selected>Select Subcategory</option>';

        // Show only subcategories that belong to the selected category
        allOptions.forEach(option => {
            if (option.getAttribute('data-category') === selectedCategory) {
                subcategorySelect.appendChild(option);
            }
        });
    }

    categorySelect.addEventListener('change', filterSubcategories);
});
</script>
