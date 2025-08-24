<button type="button" class="btn btn-dark mt-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
    <i class="me-2 fs-6 bi bi-plus-lg"></i> Add Promo
</button>

<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Add Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <form action="{{ url('/admin/add_promo') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Promo</label>
                        <input type="text" name="promo" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="discount" class="form-control" min="1" max="100" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Active</label>
                        <select name="active" class="form-select" required>
                            <option value="1">Active</option>
                            <option value="0">Not Active</option>
                        </select>
                    </div>

                    <input type="hidden" name="global_one_time" value="0">

                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="global_one_time" id="global_one_time"
                            value="1">
                        <label class="form-check-label" for="global_one_time">
                            Global one-time (first user consumes for all)
                        </label>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Starts at (optional)</label>
                            <input type="datetime-local" name="starts_at" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ends at (optional)</label>
                            <input type="datetime-local" name="ends_at" class="form-control">
                        </div>
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
