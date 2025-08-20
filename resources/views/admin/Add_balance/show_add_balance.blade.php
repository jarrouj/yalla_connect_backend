<!DOCTYPE html>
<html lang="en">

<head>
    @include('admin.css')
</head>

<body class="g-sidenav-show   bg-gray-100">

    @include('admin.sidebar')
    <main class="main-content position-relative border-radius-lg ">
        <!-- Navbar -->
        @include('admin.navbar')
        <!-- End Navbar -->
        <div class="container-fluid py-4">
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>All Users</h6>
                        </div>

                        {{-- Search --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="d-block w-50 m-auto">
                                    <form action="{{ url('/admin/search_user') }}" method="POST">
                                        @csrf
                                        <p for="" class="text-center form-label">Search Names, Emails or Phone
                                            Number
                                        </p>

                                        <div class="d-flex justify-content-center">

                                            <div class="input-group mb-3 w-75">

                                                <input type="text" name="query" class="form-control"
                                                    placeholder="example@gmail.com" style="height: 41px "
                                                    id="searchInput">


                                            </div>

                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="card-body px-0 pt-0 pb-2">
                            <div class="table-responsive p-0">
                                <table class="table align-items-center mb-0">
                                    <thead>
                                        <tr>
                                            <th
                                                class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Full Name/Email
                                            </th>

                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                Phone Number
                                            </th>

                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">
                                                User Balance
                                            </th>
                                            <th
                                                class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">

                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody id="tbody">
                                        @forelse ($user as $data)
                                        <tr>
                                            <td>
                                                <div class="d-flex px-2 py-1">
                                                    <div class="d-flex flex-column justify-content-center">
                                                        <h6 class="mb-0 text-sm">{{ $data->first_name }} {{
                                                            $data->last_name }}</h6>
                                                        <p class="text-xs text-secondary mb-0">
                                                            {{ $data->email }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </td>

                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $data->phone
                                                    }}</span>
                                            </td>
                                            <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{ $data->balance
                                                    }}</span>
                                            </td>


                                            {{-- <td class="align-middle text-center">
                                                <span class="text-secondary text-xs font-weight-bold">{{
                                                    $data->last_seen }}</span>
                                            </td> --}}
                                            <td class="align-middle">
                                                @include('admin.Add_balance.add_balance', ['user' => $data])
                                            </td>

                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="16">
                                                <p class="text-xs text-center text-danger font-weight-bold mb-0">
                                                    No Data !
                                                </p>
                                            </td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                {{ $user->render('admin.pagination') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @include('admin.footer')
        </div>
    </main>

    @include('admin.script')


    <script>
        $(document).ready(function () {
    $('#searchInput').on('keyup', function () {
      var searchInput = $('#searchInput').val();

      $.ajax({
        url: '{{ url('admin/search_user') }}',
        type: 'get',
        data: { query: searchInput },
        success: function (response) {
          var usersHtml = '';

          response.forEach(function (user) {
            const fullName   = `${user.first_name ?? ''} ${user.last_name ?? ''}`.trim();
            const email      = user.email ?? '';
            const phone      = user.phone ?? '';
            const balance    = (Number(user.balance) || 0).toFixed(2);
            const modalId    = `addBalanceModal${user.id}`;
            const actionUrl  = "{{ url('/admin/users') }}/" + user.id + "/balance/add";
            const csrfToken  = "{{ csrf_token() }}";

            usersHtml += `
              <tr>
                <!-- Full Name / Email -->
                <td>
                  <div class="d-flex px-2 py-1">
                    <div class="d-flex flex-column justify-content-center">
                      <h6 class="mb-0 text-sm">${fullName}</h6>
                      <p class="text-xs text-secondary mb-0">${email}</p>
                    </div>
                  </div>
                </td>

                <!-- Phone -->
                <td class="align-middle text-center">
                  <span class="text-secondary text-xs font-weight-bold">${phone}</span>
                </td>

                <!-- Balance -->
                <td class="align-middle text-center">
                  <span class="text-secondary text-xs font-weight-bold">${balance}</span>
                </td>

                <!-- Add Balance trigger + modal -->
                <td class="align-middle">
                 <a href="#" class="text-primary font-weight-bold text-xs js-open-add-balance"
   data-id="${user.id}"
   data-name="${(user.first_name ?? '') + ' ' + (user.last_name ?? '')}"
   data-balance="${(Number(user.balance)||0).toFixed(2)}">
  Add Balance <i class="bi bi-cash"></i>
</a>


                </td>
              </tr>
            `;
          });

          $('#tbody').html(usersHtml);
        },
        error: function (error) {
          console.log(error);
        }
      });
    });
  });
    </script>

    <script>
        // Open existing modal or create it if missing, then show it
  $(document).on('click', '.js-open-add-balance', function (e) {
    e.preventDefault();

    const id      = $(this).data('id');
    const name    = $(this).data('name') || '';
    const balance = $(this).data('balance') || '0.00';
    const modalId = 'addBalanceModal' + id;

    // If the Blade modal already exists (same markup you shared), just show it
    let modalEl = document.getElementById(modalId);
    if (!modalEl) {
      // Otherwise, inject an identical modal dynamically
      const actionUrl = "{{ url('/admin/users') }}/" + id + "/balance/add";
      const csrf      = "{{ csrf_token() }}";

      const html = `
<div class="modal fade" id="${modalId}" tabindex="-1" aria-labelledby="addBalanceLabel${id}" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="addBalanceLabel${id}">Add Balance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <form method="POST" action="${actionUrl}">
        <input type="hidden" name="_token" value="${csrf}">
        <div class="modal-body">
          <label class="form-label">
            Add balance for ${name}
            <span class="badge bg-secondary ms-2">Current: ${balance}</span>
          </label>

          <div class="mb-3">
            <input type="number" name="balance" class="form-control"
                   step="0.01" min="0.01" placeholder="e.g. 25.00" required>
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
</div>`;
      document.body.insertAdjacentHTML('beforeend', html);
      modalEl = document.getElementById(modalId);
    }

    const modal = bootstrap.Modal.getOrCreateInstance(modalEl);
    modal.show();
  });
    </script>




</body>

</html>
