@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Well, Change your personal detail here</h3>
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h2> Update Profile</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reset.name') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                                    @error('name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
                                    @error('email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-12 col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h2> Reset Password</h2>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('reset.password') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
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
                                <div class="form-group mb-3">
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
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                            </form>
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
