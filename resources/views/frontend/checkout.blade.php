@extends('frontend.layouts.app')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('costumer.index') }}">Shop</a></li>
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('costumer.chart') }}">Chart</a></li>
                    <li class="breadcrumb-item">Checkout</li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-9 pt-4">
        <div class="container">
            <form action="{{ route('costumer.prosesCheckout') }}" method="post" name="orderForm" id="orderForm">
                @csrf
                <div class="row">
                    <div class="col-md-8">
                        <div class="sub-title">
                            <h2>Data Order</h2>
                        </div>
                        <div class="card shadow-lg border-0">
                            <div class="card-body checkout-form">
                                <div class="row">

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="nama">Nama</label>
                                            <input type="text" name="name" id="name" class="form-control"
                                                placeholder="Name" value="{{ old('name') }}">
                                            <p></p>
                                        </div>
                                    </div>


                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="emmail">Email</label>
                                            <input type="text" name="email" id="email" class="form-control"
                                                placeholder="Email" value="{{ old('email') }}">
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="alamat">Alamat</label>
                                            <textarea name="address" id="address" cols="30" rows="3" placeholder="Address" class="form-control"></textarea>
                                            <p></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="phone">No. Telp</label>
                                            <input type="number" name="telepon" id="telepon" class="form-control"
                                                placeholder="Phone Number" value="{{ old('telepon') }}">
                                            <p></p>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="sub-title">
                            <h2>Order Detail</h3>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body">
                                @foreach (Cart::content() as $cart)
                                    <div class="d-flex justify-content-between pb-2">
                                        <div class="h6">{{ $cart->name }} X {{ $cart->qty }}</div>
                                        <div class="h6">{{ formatCurrency($cart->price * $cart->qty) }}</div>
                                    </div>
                                @endforeach

                                <div class="d-flex justify-content-between summery-end">
                                    <div class="h6"><strong>Subtotal</strong></div>
                                    <div class="h6"><strong>Rp.{{ Cart::subtotal() }}</strong></div>
                                </div>
                                <div class="d-flex justify-content-between mt-2 summery-end">
                                    <div class="h5"><strong>Total</strong></div>
                                    <div class="h5"><strong>Rp.{{ Cart::subtotal() }}</strong></div>
                                </div>
                            </div>
                            <div class="pt-4">
                                <button type="submit" id="pay-button" class="btn-dark btn btn-block w-100">Pay
                                    Now</button>
                            </div>
                        </div>
                        <div class="card cart-summery">
                            <div class="card-body ">
                                <div class="w-100" id="snap-container">
                                </div>
                            </div>
                        </div>
                    </div>
            </form>
        </div>
    </section>
@endsection


@section('costumeJS')
    <script type="text/javascript">
        $('#orderForm').submit(function(event) {
            event.preventDefault();
            $.ajax({
                url: "{{ route('costumer.prosesCheckout') }}",
                type: "post",
                data: $(this).serialize(),
                dataType: "json",
                success: function(response) {
                    if (response.status == false) {

                        var errors = response.errors;
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
                        if (errors.address) {
                            $("#address").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.address);
                        } else {
                            $("#stock").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }
                        if (errors.telepon) {
                            $("#telepon").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.telepon);
                        } else {
                            $("#telepon").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        }
                    } else {
                        var snapToken = response.snapToken;


                        window.snap.embed(snapToken, {
                            embedId: 'snap-container',
                            onSuccess: function(result) {
                                alert("Payment success!");
                                var userConfirmed = confirm(
                                    "Payment success! Do you want to go to the home page?"
                                );

                                if (userConfirmed) {
                                    window.location.href =
                                        "{{ route('costumer.index') }}"; // Ganti "/home" dengan URL halaman beranda yang sesuai
                                }
                                console.log(result);
                            },
                            onPending: function(result) {
                                /* Your implementation for onPending here */
                                alert("waiting for your payment!");
                                console.log(result);
                            },
                            onError: function(result) {
                                /* Your implementation for onError here */
                                alert("payment failed!");
                                console.log(result);
                            },
                            onClose: function() {
                                /* Your implementation for onClose here */
                                alert('you closed the popup without finishing the payment');
                            }
                        });



                        // Trigger snap popup dengan snapToken yang diterima
                        // window.snap.pay(snapToken, {
                        //     onSuccess: function(result) {
                        //         /* You may add your own implementation here */
                        //         alert("payment success!");
                        //         console.log(result);
                        //     },
                        //     onPending: function(result) {
                        //         /* You may add your own implementation here */
                        //         alert("wating your payment!");
                        //         console.log(result);
                        //     },
                        //     onError: function(result) {
                        //         /* You may add your own implementation here */
                        //         alert("payment failed!");
                        //         console.log(result);
                        //     },
                        //     onClose: function() {
                        //         /* You may add your own implementation here */
                        //         alert('you closed the popup without finishing the payment');
                        //     }
                        // })
                    }
                },
                error: function(xhr, exception) {
                    console.log("Something went wrong");
                }
            })
        })
    </script>
@endsection
