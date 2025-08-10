<!-- Trigger Edit Modal -->
<a type="button" class="text-primary font-weight-bold text-xs" data-bs-toggle="modal"
    data-bs-target="#editProductModal{{ $product->id }}">
    Edit <i class="bi bi-pencil"></i>
</a>

<!-- Edit Product Modal -->
<div class="modal fade" id="editProductModal{{ $product->id }}" tabindex="-1"  
    aria-labelledby="editProductModalLabel{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Product - {{ $product->title }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('/admin/update_product', $product->id) }}" method="POST"
                enctype="multipart/form-data" id="editForm{{ $product->id }}">
                @csrf

                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="mt-3 row">
                        <div class="col-md-4">
                            <label class="form-label">Name</label>
                            <input type="text" name="title" class="form-control" required
                                value="{{ $product->title }}">
                        </div>

                        <!-- Category -->
                        <div class="col-md-4">
                            <label class="form-label">Category</label>
                            <select class="form-select categorySelect" name="category_id" required>
                                <option value="" disabled>Select Category</option>
                                @foreach ($categories as $cat)
                                    <option value="{{ $cat->id }}"
                                        {{ $cat->id == $product->category_id ? 'selected' : '' }}>
                                        {{ $cat->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Subcategory -->
                        <div class="col-md-4">
                            <label class="form-label">Subcategory</label>
                            <select class="form-select subcategorySelect" name="subcategory_id" required>
                                <option value="" disabled>Select Subcategory</option>
                                @foreach ($subcategories as $sub)
                                    <option value="{{ $sub->id }}" data-category="{{ $sub->category_id }}"
                                        {{ $sub->id == $product->subcategory_id ? 'selected' : '' }}>
                                        {{ $sub->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Voucher Code (only for Voucher subcategory) -->
                    {{-- Voucher Codes (only for Voucher subcategory) --}}
                    @php
                        // Prefill from relation or legacy single column
                        $prefillCodes = collect(optional($product->codes)->pluck('code'))
                            ->filter()
                            ->values();
                        if ($prefillCodes->isEmpty() && !empty($product->code)) {
                            $prefillCodes = collect([$product->code]);
                        }
                    @endphp

                    <div class="mt-3 row voucher-codes" style="display:none;">
                        <div class="col-md-8">
                            <label class="form-label d-flex align-items-center gap-2">
                                Voucher Codes
                                <span class="badge bg-secondary">at least 1</span>
                            </label>

                            <div id="codesWrapper-{{ $product->id }}" class="d-grid gap-2">
                                {{-- First (mandatory) field --}}
                                <div class="input-group">
                                    <input type="text" name="codes[]" class="form-control"
                                        value="{{ $prefillCodes->get(0) ?? '' }}" placeholder="Enter voucher code">
                                    <button type="button" class="btn btn-outline-danger" disabled
                                        title="First code cannot be removed">×</button>
                                </div>

                                {{-- Extra (removable) prefilled rows --}}
                                @foreach ($prefillCodes->slice(1) as $codeVal)
                                    <div class="input-group">
                                        <input type="text" name="codes[]" class="form-control"
                                            value="{{ $codeVal }}" placeholder="Enter voucher code">
                                        <button type="button" class="btn btn-outline-danger"
                                            onclick="this.parentElement.remove()">×</button>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-outline-primary mt-2"
                                id="addCodeBtn-{{ $product->id }}">
                                + Add another code
                            </button>

                            <div class="form-text">
                                You can add as many codes as you want. Duplicates are ignored server-side.
                            </div>
                        </div>
                    </div>

                    <!-- Image -->
                    <div class="mt-3 row">
                        <div class="col-md-6">
                            <label class="form-label">Product Image</label>
                            <input type="file" name="image" class="form-control" accept="image/*"
                                onchange="previewImage(event, '{{ $product->id }}')">
                            @if ($product->image)
                                <img id="imagePreview{{ $product->id }}"
                                    src="{{ asset('images/products/' . $product->image) }}" alt="Product Image"
                                    class="img-thumbnail mt-2" width="120">
                            @else
                                <img id="imagePreview{{ $product->id }}" class="img-thumbnail mt-2" width="120"
                                    style="display:none;">
                            @endif
                        </div>

                        <!-- Price -->
                        <div class="col-md-2">
                            <label class="form-label">Price ($)</label>
                            <input type="number" name="price" step="0.01" class="form-control" required
                                value="{{ $product->price }}">
                        </div>




                    </div>

                    <!-- Description -->
                    <div class="mt-3 row">
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea name="description" rows="4" class="form-control">{{ $product->description }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">Update Product</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('editProductModal{{ $product->id }}');
        const categorySelect = modal.querySelector('.categorySelect');
        const subcategorySelect = modal.querySelector('.subcategorySelect');
        const voucherField = modal.querySelector('.voucher-code');
        const allOptions = Array.from(subcategorySelect.querySelectorAll('option[data-category]'));

        // Filter subcategories by category
        function filterSubcategories() {
            const selectedCategory = categorySelect.value;
            const selectedSub = subcategorySelect.value;

            subcategorySelect.innerHTML = '<option value="" disabled>Select Subcategory</option>';

            allOptions.forEach(option => {
                if (option.getAttribute('data-category') === selectedCategory) {
                    subcategorySelect.appendChild(option);
                }
            });

            if (!Array.from(subcategorySelect.options).some(opt => opt.value === selectedSub)) {
                if (subcategorySelect.options.length > 1) subcategorySelect.options[1].selected = true;
            }

            toggleVoucherCode();
        }

        // Show voucher code field if subcategory name is "Voucher"
        function toggleVoucherCode() {
            const selectedText = subcategorySelect.options[subcategorySelect.selectedIndex]?.text.toLowerCase();
            voucherField.style.display = (selectedText === 'voucher') ? 'flex' : 'none';

            // Auto-generate voucher code if empty
            if (selectedText === 'voucher') {
                const input = voucherField.querySelector('input');
                if (!input.value.trim()) {
                    input.value = 'VCHR-' + Math.random().toString(36).substring(2, 8).toUpperCase();
                }
            }
        }

        categorySelect.addEventListener('change', filterSubcategories);
        subcategorySelect.addEventListener('change', toggleVoucherCode);

        filterSubcategories(); // Run once when modal opens
    });

    // Preview image before upload
    function previewImage(event, id) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('imagePreview' + id);
            output.src = reader.result;
            output.style.display = 'block';
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('editProductModal{{ $product->id }}');
        const categorySelect = modal.querySelector('.categorySelect');
        const subcategorySelect = modal.querySelector('.subcategorySelect');
        const voucherSection = modal.querySelector('.voucher-codes');
        const allOptions = Array.from(subcategorySelect.querySelectorAll('option[data-category]'));

        const codesWrapper = modal.querySelector('#codesWrapper-{{ $product->id }}');
        const addCodeBtn = modal.querySelector('#addCodeBtn-{{ $product->id }}');

        function filterSubcategories() {
            const selectedCategory = categorySelect.value;
            const selectedSub = subcategorySelect.value;

            subcategorySelect.innerHTML = '<option value="" disabled>Select Subcategory</option>';

            allOptions.forEach(option => {
                if (option.getAttribute('data-category') === selectedCategory) {
                    subcategorySelect.appendChild(option);
                }
            });

            if (!Array.from(subcategorySelect.options).some(opt => opt.value === selectedSub)) {
                if (subcategorySelect.options.length > 1) subcategorySelect.options[1].selected = true;
            }

            toggleVoucherSection();
        }

        function isVoucherSelected() {
            const opt = subcategorySelect.options[subcategorySelect.selectedIndex];
            return opt && opt.text?.toLowerCase() === 'voucher';
        }

        function toggleVoucherSection() {
            if (isVoucherSelected()) {
                voucherSection.style.display = '';
                // make first input required
                const first = codesWrapper.querySelector('input[name="codes[]"]');
                if (first) first.required = true;
            } else {
                voucherSection.style.display = 'none';
                // reset to a single, empty first input (not required)
                codesWrapper.innerHTML = `
                    <div class="input-group">
                        <input type="text" name="codes[]" class="form-control" placeholder="Enter voucher code">
                        <button type="button" class="btn btn-outline-danger" disabled title="First code cannot be removed">×</button>
                    </div>
                `;
                const first = codesWrapper.querySelector('input[name="codes[]"]');
                if (first) first.required = false;
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

        addCodeBtn?.addEventListener('click', () => addCodeRow());

        categorySelect.addEventListener('change', filterSubcategories);
        subcategorySelect.addEventListener('change', toggleVoucherSection);

        // Initialize on modal load
        filterSubcategories();
        // Ensure correct show/hide if current subcategory already voucher
        toggleVoucherSection();
    });
    </script>
