@extends('frontend.layouts.app')

@section('title', 'Cart')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="{{ route('costumer.index') }}">Shop</a></li>
                    <li class="breadcrumb-item">Cart</li>
                </ol>
            </div>
        </div>
    </section>

    <section class=" section-9 pt-4">
        <div class="container">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show align-items-center" role="alert">
                    <p>{{ Session::get('success') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show align-items-center" role="alert">
                    <p>{{ Session::get('error') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="row">
                <div class="col-md-8">
                    <div class="table-responsive">
                        <table class="table" id="cart">
                            <thead>
                                <tr>
                                    <th>Item</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Total</th>
                                    <th>Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if (!empty($cartContent))
                                    @foreach ($cartContent as $cart)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center ">
                                                    @if ($cart->options->images)
                                                        <img src="{{ asset('uploads/product/thumb/' . $cart->options->images) }}"
                                                            width="" height="">
                                                    @else
                                                        <img src="{{ asset('admin-assets/img/default-150x150.png') }}"
                                                            width="" height="">
                                                    @endif
                                                    <h2>{{ $cart->name }}</h2>
                                                </div>
                                            </td>
                                            <td>Rp.{{ formatCurrency($cart->price) }}</td>
                                            <td>
                                                <div class="input-group quantity mx-auto" style="width: 100px;">
                                                    <div class="input-group-btn">
                                                        <button class="sub btn btn-sm btn-dark btn-minus p-2 pt-1 pb-1"
                                                            data-id="{{ $cart->rowId }}">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                    </div>
                                                    <input type="text"
                                                        class="form-control form-control-sm  border-0 text-center"
                                                        name="quantity" id="quantity" data-id="{{ $cart->rowId }}"
                                                        value="{{ $cart->qty }}">
                                                    <div class="input-group-btn">
                                                        <button class="add btn btn-sm btn-dark btn-plus p-2 pt-1 pb-1"
                                                            data-id="{{ $cart->rowId }}">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                Rp.{{ formatCurrency($cart->price * $cart->qty) }}
                                            </td>
                                            <td>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="deleteItem('{{ $cart->rowId }}')"><i
                                                        class="fa fa-times"></i></button>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card cart-summery">
                        <div class="sub-title">
                            <h2 class="bg-white">Cart Summery</h3>
                        </div>
                        <div class="card-body">
                            <div class="d-flex justify-content-between pb-2">
                                <div>Subtotal</div>
                                <div>Rp.{{ Cart::subtotal() }}</div>
                            </div>
                            <div class="d-flex justify-content-between summery-end">
                                <div>Total</div>
                                <div>Rp.{{ Cart::subtotal() }}</div>
                            </div>
                            @if (Cart::count() == 0)
                                <div class="pt-5">
                                    <button disabled type="button" class="btn-dark btn btn-block w-100">Item Not
                                        Found</button>
                                </div>
                            @elseif (Cart::count() != 0 && $cartContent->count() < 1)
                                <div class="pt-5">
                                    <button disabled type="button" class="btn-dark btn btn-block w-100">Item Sold
                                        Out</button>
                                </div>
                            @else
                                <div class="pt-5">
                                    <a href="{{ route('costumer.checkout') }}" class="btn-dark btn btn-block w-100">Proceed
                                        to Checkout</a>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('costumeJS')
    <script>
        $(document).ready(function() {
            $('.add').click(function() {
                var qtyElement = $(this).parent().prev();
                var qtyValue = parseInt(qtyElement.val());
                if (qtyValue) {
                    qtyElement.val(qtyValue + 1);
                    var rowId = $(this).data('id');
                    var newQty = qtyElement.val();
                    updateCart(rowId, newQty);
                }
            });


            $('.sub').click(function() {
                var qtyElement = $(this).parent().next();
                var qtyValue = parseInt(qtyElement.val());
                if (qtyValue > 1) {
                    qtyElement.val(qtyValue - 1);
                    var rowId = $(this).data('id');
                    var newQty = qtyElement.val();
                    updateCart(rowId, newQty);
                }
            });

        });

        function updateCart(rowId, qty) {
            $.ajax({
                url: "{{ route('costumer.updateCart') }}",
                type: "post",
                data: {
                    rowId: rowId,
                    qty: qty,
                },
                success: function(response) {
                    if (response.status == true) {
                        window.location.reload();
                    }
                }
            })
        }


        function deleteItem(rowId) {
            // Tampilkan pesan konfirmasi
            if (confirm('Are you sure you want to delete this item?')) {
                $.ajax({
                    url: "{{ route('costumer.deleteItem') }}", // Pastikan route ini sesuai dengan yang Anda gunakan
                    type: "post",
                    data: {
                        rowId: rowId
                    },
                    success: function(response) {
                        if (response.status == true) {
                            window.location.reload();
                        }
                    },
                    error: function() {
                        // Tangani kesalahan jika terjadi
                        alert('Terjadi error.');
                    }
                });
            }
        }
    </script>
@endsection
