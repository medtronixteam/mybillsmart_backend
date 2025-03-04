@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Reset Users Password</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h2>Reset Password</h2>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            <form action="{{ route('change.password') }}" method="POST">
                                @csrf
                                <input type="hidden" name="resetId" value="{{ $passId }}">
                                <div class="form-group">
                                    <label for="new-password">New Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="new-password" name="new_password">
                                        <span class="input-group-text">
                                            <i class="fa fa-eye-slash" id="toggle-new-password"
                                                onclick="togglePassword('new-password', 'toggle-new-password')"></i>
                                        </span>
                                    </div>
                                    @error('new_password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                        <div class="form-group">
                                            <label for="confirm-password">Confirm Password</label>
                                            <div class="input-group">
                                                <input type="password" class="form-control" id="confirm-password"
                                                    name="confirm_password">
                                                <span class="input-group-text">
                                                    <i class="fa fa-eye-slash" id="toggle-confirm-password"
                                                        onclick="togglePassword('confirm-password', 'toggle-confirm-password')"></i>
                                                </span>
                                            </div>
                                            @error('confirm_password')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                        </form>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>
@endsection
<script>
    function togglePassword(id, iconId) {
        let input = document.getElementById(id);
        let icon = document.getElementById(iconId);
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
        } else {
            input.type = "password";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
        }
    }
</script>
