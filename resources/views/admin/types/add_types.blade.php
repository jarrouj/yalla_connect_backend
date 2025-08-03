<button type="button" class="btn btn-dark mt-3" data-bs-toggle="modal" data-bs-target="#addSubcategoryModal">
    <i class="me-2 fs-6 bi bi-plus-lg"></i>
    Add Subcategory
</button>

<div class="modal fade" id="addSubcategoryModal" tabindex="-1" aria-labelledby="addSubcategoryLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('/admin/add_subcategory') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <!-- Subcategory Name -->
                    <div class="mb-3">
                        <label class="form-label">Subcategory Name</label>
                        <input type="text" name="name" class="form-control" placeholder="Enter subcategory name" required>
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="category_id" class="form-select" required>
                            <option value="" disabled selected>Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategory Image -->
                    <div class="mb-3">
                        <label class="form-label">Subcategory Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">Add <i class="bi bi-plus-lg"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
