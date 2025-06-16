@extends('layout.admin')

@section('content')
    <div class="page-wrapper">
        <div class="page-breadcrumb">
            <div class="row">
                <div class="col-7 align-self-center">
                    {{-- <h3 class="page-title text-truncate text-dark font-weight-medium mb-1">Users Create Here</h3> --}}
                </div>
            </div>
        </div>
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <div class="card">
                        <div class="card-header">
                            <h2> Create User</h2>
                        </div>
                        <div class="card-body">
                            <div id="success-message" class="alert alert-success d-none"></div>
                            <div id="error-message" class="alert alert-danger d-none"></div>

                            <form id="user-form" action="{{ route('user.store') }}" method="POST">
                                @csrf
                                <div class="form-group">
                                    <label for="name">Name</label>
                                    <input type="text" class="form-control" id="name" name="name" value="">
                                    <span id="name-error" class="text-danger"></span>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="email" class="form-control" id="email" name="email" value="">
                                            <span id="email-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group position-relative">
                                            <label for="password">Password</label>
                                            <input type="password" class="form-control" id="password" name="password">
                                            <span toggle="#password" class="fa fa-fw fa-eye field-icon toggle-password"
                                                  style="position: absolute; top: 43px; right: 15px; cursor: pointer;"></span>
                                            <span id="password-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dob">dob</label>
                                            <input type="date" class="form-control" id="dob" name="dob" value="">
                                            <span id="dob-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" id="city" name="city" value="">
                                            <span id="city-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="country">Country</label>
                                            <input type="text" class="form-control" id="country" name="country" value="">
                                            <span id="country-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="postal_code">Post Code</label>
                                            <input type="number" class="form-control" id="postal_code" name="postal_code" value="">
                                            <span id="postal_code-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label for="phone">Phone</label>
                                        <input type="number" class="form-control" id="phone" name="phone" value="">
                                        <span id="phone-error" class="text-danger"></span>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="1">enable</option>
                                                <option value="0">disable</option>
                                            </select>
                                            <span id="status-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="role">Role</label>
                                        <select class="form-control" name="role" id="role">
                                            <option value="group_admin">Group Admin</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                        <span id="role-error" class="text-danger"></span>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="dob">Euro Per Point</label>
                                            <input type="number" min="0" value="1" class="form-control" id="euro_per_points" name="euro_per_points" value="">
                                            <span id="euro_per_points-error" class="text-danger"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <textarea name="address" id="address" rows="2" class="form-control"></textarea>
                                    <span id="address-error" class="text-danger"></span>
                                </div>

                                <button type="submit" class="btn btn-primary">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Toggle password visibility
            const togglePassword = document.querySelector(".toggle-password");
            const passwordField = document.querySelector("#password");

            if (togglePassword) {
                togglePassword.addEventListener("click", function () {
                    const type = passwordField.getAttribute("type") === "password" ? "text" : "password";
                    passwordField.setAttribute("type", type);

                    this.classList.toggle("fa-eye");
                    this.classList.toggle("fa-eye-slash");
                });
            }

            // AJAX form submission
            $('#user-form').submit(function(e) {
                e.preventDefault();

                // Clear previous errors
                $('.text-danger').text('');
                $('#success-message, #error-message').addClass('d-none');

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if(response.success) {
                            $('#success-message').removeClass('d-none').text(response.success);
                            // Optionally reset the form
                            $('#user-form')[0].reset();
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            // Validation errors
                            const errors = xhr.responseJSON.errors;
                            for (const field in errors) {
                                $(`#${field}-error`).text(errors[field][0]);
                            }
                        } else {
                            // Other errors
                            $('#error-message').removeClass('d-none').text('An error occurred. Please try again.');
                        }
                    }
                });
            });
        });
    </script>
@endsection
