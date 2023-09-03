@extends('frontend.layouts.app')

@section('content')
    <section class=" section-10 pt-3">
        <div class="container">
            <div class="login-form">
                <form action="{{ route('register.proses') }}" method="POST" name="formRegister" id="formRegister">
                    @csrf
                    <h4 class="modal-title">Register Now</h4>
                    <div class="form-group">
                        <input type="text" id="name" name="name" class="form-control" placeholder="Name">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="text" id="email" name="email" class="form-control" placeholder="Email">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Password">
                        <p></p>
                    </div>
                    <div class="form-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control"
                            placeholder="Confirm Password">
                        <p></p>
                    </div>
                    <button type="submit" class="btn btn-dark btn-block btn-lg" value="Register">Register</button>
                </form>
                <div class="text-center small">Already have an account? <a href="login.php">Login Now</a></div>
            </div>
        </div>
    </section>
@endsection


@section('costumeJS')
    <script>
        $(document).ready(function() {
            $("#formRegister").submit(function(e) {
                e.preventDefault();
                var element = $(this);

                $.ajax({
                    url: "{{ route('register.proses') }}",
                    type: "post",
                    data: element.serializeArray(),
                    dataType: "json",
                    success: function(response) {

                        if (response.status == false) {


                            var errors = response.errors

                            if (errors.name) {
                                $("#name").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback').html(errors.name);

                            } else {
                                $("#name").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");
                            }

                            if (errors.email) {
                                $("#email").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors.email);
                            } else {
                                $("#email").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html("");
                            }
                            if (errors.password) {
                                $("#password").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors.password);
                            } else {
                                $("#password").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html("");
                            }

                        } else {
                            $("#name").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");

                            $("#email").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                            $("#password").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");

                            window.location.href = "{{ route('login') }}";
                        }
                    },
                    error: function(response) {
                        console.log(response);
                    }
                });
            });
        });
    </script>
@endsection
