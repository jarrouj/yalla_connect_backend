{{-- Edit trigger --}}
<a type="button" class="text-primary font-weight-bold text-xs" data-bs-toggle="modal"
    data-bs-target="#editPromo{{ $promo->id }}">
    Edit <i class="bi bi-pencil"></i>
</a>

{{-- Edit Modal --}}
<div class="modal fade" id="editPromo{{ $promo->id }}" tabindex="-1" aria-labelledby="editPromoLabel{{ $promo->id }}"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title" id="editPromoLabel{{ $promo->id }}">Edit Promo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- If your route is POST /admin/update_promo/{id}, keep POST.
            If you use a PUT route, add: @method('PUT') --}}
            <form action="{{ route('admin.promos.update', $promo->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-body">
                    {{-- Code --}}
                    <div class="mb-3">
                        <label class="form-label">Code</label>
                        <input type="text" name="code" class="form-control" value="{{ old('code', $promo->code) }}"
                            required>
                        @error('code') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Percent --}}
                    <div class="mb-3">
                        <label class="form-label">Discount (%)</label>
                        <input type="number" name="percent" class="form-control" min="1" max="100"
                            value="{{ old('percent', (int) $promo->percent) }}" required>
                        @error('percent') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Active --}}
                    <div class="mb-3">
                        <label class="form-label">Active</label>
                        <select name="is_active" class="form-select" required>
                            <option value="1" {{ old('is_active', (int)$promo->is_active) === 1 ? 'selected' : ''
                                }}>Active</option>
                            <option value="0" {{ old('is_active', (int)$promo->is_active) === 0 ? 'selected' : '' }}>Not
                                Active</option>
                        </select>
                        @error('is_active') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    {{-- Global one-time (checkbox posts 0/1 reliably) --}}
                    <input type="hidden" name="global_one_time" value="0">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="global_one_time"
                            id="global_one_time_{{ $promo->id }}" value="1" {{ old('global_one_time',
                            (int)$promo->global_one_time) === 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="global_one_time_{{ $promo->id }}">
                            Global one-time (first user consumes for all)
                        </label>
                    </div>

                    {{-- Optional: Validity window --}}
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Starts at (optional)</label>
                            <input type="datetime-local" name="starts_at" class="form-control"
                                value="{{ old('starts_at', optional($promo->starts_at)->format('Y-m-d\TH:i')) }}">
                            @error('starts_at') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ends at (optional)</label>
                            <input type="datetime-local" name="ends_at" class="form-control"
                                value="{{ old('ends_at', optional($promo->ends_at)->format('Y-m-d\TH:i')) }}">
                            @error('ends_at') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
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
