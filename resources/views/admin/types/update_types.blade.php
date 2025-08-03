<a type="button" class="text-primary font-weight-bold text-xs" data-bs-toggle="modal" data-bs-target="#editSubcategoryModal{{ $subcategory->id }}">
    Edit <i class="bi bi-pencil"></i>
</a>

<div class="modal fade" id="editSubcategoryModal{{ $subcategory->id }}" tabindex="-1" aria-labelledby="editSubcategoryLabel{{ $subcategory->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Subcategory</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('/admin/update_subcategory/' . $subcategory->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('POST')
                <div class="modal-body">

                    <!-- Subcategory Name -->
                    <div class="mb-3">
                        <label class="form-label">Subcategory Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $subcategory->name }}" required>
                    </div>

                    <!-- Parent Category -->
                    <div class="mb-3">
                        <label class="form-label">Parent Category</label>
                        <select name="category_id" class="form-select" required>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $cat->id == $subcategory->category_id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Subcategory Image -->
                    <div class="mb-3">
                        <label class="form-label">Subcategory Image</label>
                        <input type="file" name="image" class="form-control" accept="image/*">

                        @if ($subcategory->image)
                            <div class="mt-2">
                                <small>Current Image:</small><br>
                                <img src="{{ asset('storage/' . $subcategory->image) }}" alt="Current Image" style="width: 60px; height: 60px; object-fit: cover; border-radius: 5px;">
                            </div>
                        @endif
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">Update <i class="bi bi-pencil"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
