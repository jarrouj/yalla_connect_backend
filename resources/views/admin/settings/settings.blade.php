<!DOCTYPE html>
<html lang="en">
<head>
    @include('admin.css')
</head>
<body class="g-sidenav-show bg-gray-100">

@include('admin.sidebar')

<main class="main-content position-relative border-radius-lg">
    @include('admin.navbar')

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-md-8 col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">Dollar Price</h6>
                    </div>

                    <div class="card-body">

                        {{-- Success message --}}
                        @if(session('message'))
                            <div class="alert alert-success mb-3">
                                {{ session('message') }}
                            </div>
                        @endif

                        {{-- Validation errors --}}
                        @if($errors->any())
                            <div class="alert alert-danger mb-3">
                                <ul class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.settings.currency.update') }}">
                            @csrf

                            <label for="dollar_price" class="form-label">Current Dollar Price</label>

                            <div class="input-group mb-3">
                                <span class="input-group-text">$</span>
                                <input
                                    type="number"
                                    step="0.0001"
                                    min="0"
                                    class="form-control"
                                    id="dollar_price"
                                    name="dollar_price"
                                    value="{{ old('dollar_price', $converter->dollar_price) }}"
                                    placeholder="Enter dollar price"
                                    required
                                >
                            </div>

                            {{-- Buttons row: primary "Submit" and secondary "Update" (both submit) --}}
                            <div class="d-flex gap-2">
                                <button type="submit" name="action" value="submit" class="btn btn-primary">
                                    Submit
                                </button>

                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>

        @include('admin.footer')
    </div>
</main>

@include('admin.script')
</body>
</html>
