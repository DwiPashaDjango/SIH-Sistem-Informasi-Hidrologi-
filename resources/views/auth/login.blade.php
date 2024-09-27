@extends('layouts.pages')

@section('title')
    Login
@endsection

@push('css')
    <style>
        canvas{
            pointer-events:none;
        }
    </style>
@endpush    

@section('content')
<div class="container">
    <div class="justify-content-center mt-5 row">
        <div class="col-md-4">
            <div class="shadow-sm card">
                <div class="card-body">
                    <div class="py-3 text-center">
                        <img src="{{asset('logo.png')}}" width="200" alt="">
                    </div>
                    <hr class="divide">
                    <form id="loginForm" class="mt-4" onsubmit="validateCaptcha()">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input id="username" name="username" placeholder="Masukkan Username" type="text" class="form-control" value="admin">
                            <span class="text-danger error-username"></span>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input id="password" name="password" placeholder="Masukkan Password" type="password" class="form-control" value="janganganggu2024">
                            <span class="text-danger error-password"></span>
                        </div>
                        <div class="mt-4 mb-4">
                            <div class="mb-3">
                                <div id="captcha">
                                </div>
                                <input id="cpatchaTextBox" name="cpatchaTextBox" placeholder="Captcha" type="text" class="form-control">
                            </div>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: rgb(10, 71, 147); color: #ffff; font-weight: 500">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
    <script>
        var code;

        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });

        function createCaptcha() {
            document.getElementById('captcha').innerHTML = "";
            
            var num1 = Math.floor(Math.random() * 10);
            var num2 = Math.floor(Math.random() * 10);

            code = num1 + num2;

            var captchaText = num1 + " + " + num2 + " = ?";

            var canv = document.createElement("canvas");
            canv.id = "captcha";
            canv.width = 100;
            canv.height = 50;
            var ctx = canv.getContext("2d");
            ctx.font = "25px Georgia";
            ctx.strokeText(captchaText, 0, 30);
            
            document.getElementById("captcha").appendChild(canv); 
        }

        function validateCaptcha() {
            if (document.getElementById("cpatchaTextBox").value == code) {
                return true;
            } else {
                Toast.fire({
                    icon: "error",
                    title: "Invalid Captcha. Try Again"
                });
                createCaptcha();
                return false;
            }
        }

        $(document).ready(function() {
            createCaptcha();

            $("#loginForm").submit(function(event) {
                event.preventDefault();
                if (validateCaptcha()) {
                    var username = $("#username").val();
                    var password = $("#password").val();

                    $.ajax({
                        url: "{{route('login.post')}}",
                        type: 'POST',
                        data: {
                            username: username,
                            password: password
                        },
                        success: function(response) {
                            if (response.errors) {
                                $.each(response.errors, function(index, value) {
                                    $("#" + index).addClass('is-invalid');
                                    $(".error-" + index).html(value);
                                    
                                    setTimeout(() => {
                                        $("#" + index).removeClass('is-invalid');
                                        $(".error-" + index).html('');
                                    }, 3000);
                                })
                            } else {
                                if (response.code === 400) {
                                    Toast.fire({
                                        icon: "error",
                                        title: response.message
                                    });
                                } else {
                                    window.location.href = "{{route('dashboard')}}"
                                }
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }
            });
        });
    </script>

@endpush