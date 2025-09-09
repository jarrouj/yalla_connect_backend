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
                                <option
                                    value="{{ $sub->id }}"
                                    data-category="{{ $sub->category_id }}"
                                    data-is-voucher="{{ strtolower($sub->name) === 'voucher' ? '1' : '0' }}"
                                >
                                    {{ $sub->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Voucher Codes (shown only when subcategory is 'voucher') -->
                    <div class="mb-3" id="voucherCodesSection" style="display:none;">
                        <label class="form-label d-flex align-items-center gap-2">
                            Voucher Codes
                            <span class="badge bg-secondary">at least 1</span>
                        </label>

                        <div id="codesWrapper" class="d-grid gap-2">
                            <!-- First (mandatory) code input; cannot remove -->
                            <div class="input-group">
                                <input type="text" name="codes[]" class="form-control" placeholder="Enter voucher code">
                                <button type="button" class="btn btn-outline-danger no-bottom-margin" disabled title="First code cannot be removed">×</button>
                            </div>
                        </div>

                        <button type="button" class="btn btn-outline-primary mt-2" id="addCodeBtn">
                            + Add another code
                        </button>

                        <div class="form-text">
                            You can add as many codes as you want. Duplicates will be ignored server-side.
                        </div>
                    </div>

                      <!--Purchase Price -->
                    <div class="mb-3">
                        <label class="form-label">Purchase Price</label>
                        <input type="number" name="purchase_price" step="0.01" class="form-control" required>
                    </div>


                    <!--Selling Price -->
                    <div class="mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" name="price" step="0.01" class="form-control" required>
                    </div>

                      <!-- Duration -->
                    <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <input type="text" name="duration" class="form-control" required>
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
    const categorySelect = document.getElementById('categorySelect');
    const subcategorySelect = document.getElementById('subcategorySelect');
    const allSubOptions = Array.from(subcategorySelect.querySelectorAll('option[data-category]'));

    const voucherSection = document.getElementById('voucherCodesSection');
    const codesWrapper = document.getElementById('codesWrapper');
    const addCodeBtn = document.getElementById('addCodeBtn');

    function filterSubcategories() {
        const selectedCategory = categorySelect.value;

        // Reset subcategory dropdown
        subcategorySelect.innerHTML = '<option value="" disabled selected>Select Subcategory</option>';

        // Append only subcategories for the selected category
        allSubOptions.forEach(option => {
            if (option.getAttribute('data-category') === selectedCategory) {
                subcategorySelect.appendChild(option);
            }
        });

        // Hide voucher section when category changes (no subcategory chosen yet)
        hideVoucherSection();
    }

    function isVoucherSelected() {
        const opt = subcategorySelect.options[subcategorySelect.selectedIndex];
        return opt && opt.getAttribute('data-is-voucher') === '1';
    }

    function showVoucherSection() {
        voucherSection.style.display = '';
        // ensure the first input is required
        const first = codesWrapper.querySelector('input[name="codes[]"]');
        if (first) first.required = true;
    }

    function hideVoucherSection() {
        voucherSection.style.display = 'none';
        // reset codes to a single (empty) first input and not required
        codesWrapper.innerHTML = `
            <div class="input-group">
                <input type="text" name="codes[]" class="form-control" placeholder="Enter voucher code">
                <button type="button" class="btn btn-outline-danger" disabled title="First code cannot be removed">×</button>
            </div>
        `;
        const first = codesWrapper.querySelector('input[name="codes[]"]');
        if (first) first.required = false;
    }

    function onSubcategoryChange() {
        if (isVoucherSelected()) {
            showVoucherSection();
        } else {
            hideVoucherSection();
        }
    }

    function addCodeRow(value = '') {
        const group = document.createElement('div');
        group.className = 'input-group';

        const input = document.createElement('input');
        input.type = 'text';
        input.name = 'codes[]';
        input.className = 'form-control';
        input.placeholder = 'Enter voucher code';
        input.value = value;

        const btn = document.createElement('button');
        btn.type = 'button';
        btn.className = 'btn btn-outline-danger';
        btn.textContent = '×';
        btn.title = 'Remove';
        btn.addEventListener('click', () => group.remove());

        group.appendChild(input);
        group.appendChild(btn);
        codesWrapper.appendChild(group);
    }

    // Events
    categorySelect?.addEventListener('change', filterSubcategories);
    subcategorySelect?.addEventListener('change', onSubcategoryChange);
    addCodeBtn?.addEventListener('click', () => addCodeRow());

    // Initial state
    hideVoucherSection();
});
</script>
