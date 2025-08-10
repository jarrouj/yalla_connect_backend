<a type="button" class="text-primary font-weight-bold text-xs" data-bs-toggle="modal"
    data-bs-target="#exampleModal{{ $data->id }}">
    Edit <i class="bi bi-pencil"></i>
</a>

<div class="modal fade" id="exampleModal{{ $data->id }}" tabindex="-1"
    aria-labelledby="exampleModal{{ $data->id }}Label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Specilaty</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ url('/admin/update_specialty/' . $data->id) }}" method="POST"
                enctype="multipart/form-data">
                @csrf
                @method('POST') {{-- If your route is POST-based, keep it. If it's PUT, change this to @method('PUT') --}}

                <div class="modal-body">

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" value="{{ $data->name }}" required>
                    </div>

                    <!-- Duration -->
                    <div class="mb-3">
                        <label class="form-label">Duration</label>
                        <input type="text" name="time" class="form-control" value="{{ $data->time }}" required>
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="3">{{ $data->description }}</textarea>
                    </div>

                    <!-- Current Image Preview -->
                    @if ($data->image)
                        <div class="mb-3">
                            <label class="form-label d-block">Current Image</label>
                            <img src="{{ asset('storage/' . $data->image) }}" alt="Category Image" width="80"
                                class="mb-2 rounded">
                        </div>
                    @endif

                    <!-- Price -->
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control" step="0.01" min="0"
                            value="{{ $data->price }}" required>
                    </div>

                    <!-- Status -->
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="is_active" class="form-select">
                            <option value="1" {{ $data->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$data->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- New Image Upload -->
                    <div class="mb-3">
                        <label class="form-label">Change Image (optional)</label>
                        <input type="file" name="image" class="form-control" accept="image/*">
                    </div>



                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-dark">
                        Update <i class="bi bi-pencil"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
