<button type="button" class="btn btn-dark mt-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <i class="me-2 fs-6 bi bi-plus-lg"></i>
    Add Specialty
</button>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Specialty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('/admin/add_specialty') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <!-- Specialty Name -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>

                     <!-- Specialty Duration -->
                     <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <input type="text" name="time" class="form-control" required>
                    </div>

                    <!-- Specialty Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3" ></textarea>
                    </div>

                     <!-- Specialty Purchase Price -->
                    <div class="mb-3">
                        <label class="form-label">Purchase Price</label>
                        <input type="number" name="purchase_price" class="form-control" step="0.01" min="0"  required>
                    </div>
                    <!-- Specialty Selling Price -->
                    <div class="mb-3">
                        <label class="form-label">Selling Price</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0"  required>
                    </div>

                    <!-- Specialty Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" selected>Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <!-- Image Upload -->
                    <div class="mb-3">
                        <label class="form-label">Specialty Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*" >
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
