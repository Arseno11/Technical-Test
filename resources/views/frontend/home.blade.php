@extends('frontend.layouts.app')

@section('title', 'Shop')

@section('content')
    <section class="section-5 pt-3 pb-3 mb-3 bg-white">
        <div class="container">
            <div class="light-font">
                <ol class="breadcrumb primary-color mb-0">
                    <li class="breadcrumb-item"><a class="white-text" href="#">Shop</a></li>
                </ol>
            </div>
        </div>
    </section>

    <section class="section-6 pt-5">
        <div class="container">
            @if (Session::has('success'))
                <div class="alert alert-success alert-dismissible fade show align-items-center" role="alert">
                    <p>{{ Session::get('success') }}</p>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (Session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show align-items-center">
                    <p>{{ Session::get('error') }}</p>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    </button>
                </div>
            @endif
            <div class="row">
                <div class="col-md-12">
                    <div class="row pb-3">
                        @if ($products->count() > 0)
                            @foreach ($products as $product)
                                @if ($product->status != 0)
                                    <div class="col-md-4">
                                        <div class="card product-card">
                                            <div class="product-image position-relative">
                                                <a href="" class="product-img">
                                                    <img class="card-img-top"
                                                        src="{{ asset('uploads/product/' . $product->image) }}"
                                                        alt="">
                                                </a>
                                                @if ($product->stock != 0)
                                                    <div class="product-action">
                                                        <a class="btn btn-dark" href="javascript:void(0)"
                                                            onClick="addToCart({{ $product->id }})">
                                                            <i class="fa fa-shopping-cart"></i> Tambah Ke Keranjang
                                                        </a>
                                                    </div>
                                                @else
                                                    <div class="product-action">
                                                        <button type="button" disabled class="btn btn-dark">
                                                            <i class="fa fa-shopping-cart"></i> Stock Habis
                                                        </button>
                                                    </div>
                                                @endif
                                            </div>
                                            <div class=" bg-dark card-body text-center mt-3">
                                                <h3 class="text-uppercase text-white">{{ $product->name }}</h3>
                                                <div class="price mt-2 mb-3">
                                                    <span class="h5 text-white">
                                                        <strong>Rp.{{ formatCurrency($product->price) }}</strong>
                                                    </span>
                                                </div>
                                                <div class=" d-flex align-items-center justify-content-end">
                                                    <p class="text-white fw-light">Tersedia <span
                                                            class="text-primary">{{ formatCurrency($product->stock) }}</span>
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                            <div class="col-md-12 pt-5">
                                <nav aria-label="Page navigation example">
                                    {{ $products->links() }}
                                </nav>
                            </div>
                        @else
                            <div class="col-md-4">
                                <h1>Tidak Ada Produk Tersedia</h1>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('costumeJS')
    <script type="text/javascript">
        function addToCart(id) {
            $.ajax({
                url: "{{ route('costumer.addToCart') }}",
                type: "post",
                data: {
                    id: id,
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == true) {
                        window.location.reload();
                        alert(response.message);
                    } else {
                        alert(response.message);
                    }
                }

            })
        }
    </script>
@endsection
