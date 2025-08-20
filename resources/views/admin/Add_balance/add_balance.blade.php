<a type="button" class="text-primary font-weight-bold text-xs" data-bs-toggle="modal"
   data-bs-target="#addBalanceModal{{ $user->id }}">
   Add Balance <i class="bi bi-cash"></i>
</a>

<div class="modal fade" id="addBalanceModal{{ $user->id }}" tabindex="-1" aria-labelledby="addBalanceLabel{{ $user->id }}"
     aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBalanceLabel{{ $user->id }}">Add Balance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      {{-- IMPORTANT: point to the add_balance route --}}
      <form method="POST" action="{{ route('admin.users.balance.add', $user->id) }}">
        @csrf
        <div class="modal-body">
          <label class="form-label">
            Add balance for {{ $user->first_name }} {{ $user->last_name }}
            <span class="badge bg-secondary ms-2">Current: {{ number_format($user->balance, 2) }}</span>
          </label>

          {{-- Amount to ADD (leave empty; do NOT prefill with current balance) --}}
          <div class="mb-3">
            <input type="number"
                   name="balance"
                   class="form-control @error('balance') is-invalid @enderror"
                   step="0.01" min="0.01"
                   placeholder="e.g. 25.00" required>
            @error('balance')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <small class="text-muted">This amount will be added to the current balance.</small>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-dark">Add <i class="bi bi-plus-circle"></i></button>
        </div>
      </form>
    </div>
  </div>
</div>
