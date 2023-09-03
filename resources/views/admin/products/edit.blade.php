@extends('admin.layouts.app')

@section('title', 'Create product')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid my-2">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>Edit product</h1>
                </div>
                <div class="col-sm-6 text-right">
                    <a href="{{ route('product.index') }}" class="btn btn-primary">Back</a>
                </div>
            </div>
        </div>
        <!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <form action="{{ route('product.update', $product->id) }}" method="post" name="productForm"
                    id="productForm">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" value="{{ $product->name }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="price">Price</label>
                                    <input type="number" name="price" id="price" class="form-control"
                                        placeholder="Price" value="{{ $product->price }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="stock">Stock</label>
                                    <input type="number" name="stock" id="stock" class="form-control"
                                        placeholder="Slug" value="{{ $product->stock }}">
                                    <p></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-5">
                                    <label for="image">Image</label>
                                    <input type="text" name="image_id" id="image_id" value="" hidden>
                                    <div id="image" class="dropzone dz-clickable">
                                        <div class="dz-message needsclick">
                                            <br>Drop files here or click to upload.<br><br>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if (!empty($product->image))
                                <div class="col-md-6">
                                    <div class="mb-5">
                                        <img src="{{ asset('uploads/product/thumb/' . $product->image) }}" class="img-fluid"
                                            style="width: 200px">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="status">Status</label>
                                    <select name="status" id="status" class="form-control">
                                        <option {{ $product->status == 1 ? 'selected' : '' }} value="1">Active
                                        </option>
                                        <option {{ $product->status == 0 ? 'selected' : '' }} value="0">Block</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>

            <div class="pb-5 pt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="#" class="btn btn-outline-dark ml-3">Cancel</a>
            </div>
            </form>

        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->

@endsection


@section('costumeJS')
    <script>
        $(document).ready(function() {
            $("#productForm").submit(function(e) {
                e.preventDefault();
                var element = $(this);
                $("button[type='submit']").attr('disabled', true);
                $.ajax({
                    url: "{{ route('product.update', $product->id) }}",
                    type: "put",
                    data: element.serializeArray(),
                    dataType: "json",
                    success: function(response) {
                        $("button[type='submit']").attr('disabled', false);

                        if (response['status'] == true) {

                            window.location.href = "{{ route('product.index') }}";

                            $("#name").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback').html("");

                            $("#price").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                            $("#stock").removeClass('is-invalid')
                                .siblings('p')
                                .removeClass('invalid-feedback')
                                .html("");
                        } else {
                            var errors = response['errors'];
                            if (errors['name']) {
                                $("#name").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback').html(errors['name']);

                            } else {
                                $("#name").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback').html("");
                            }

                            if (errors['price']) {
                                $("#price").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors['price']);
                            } else {
                                $("#price").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html("");
                            }
                            if (errors['stock']) {
                                $("#stock").addClass('is-invalid')
                                    .siblings('p')
                                    .addClass('invalid-feedback')
                                    .html(errors['stock']);
                            } else {
                                $("#stock").removeClass('is-invalid')
                                    .siblings('p')
                                    .removeClass('invalid-feedback')
                                    .html("");
                            }
                        }
                    },
                    error: function(jqXHR, exception) {
                        console.log("Something went wrong");
                    }
                });
            });
        });



        Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            init: function() {
                this.on('addedfile', function(file) {
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });
            },
            url: "{{ route('temp-images.create') }}",
            maxFiles: 1,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: "image/jpeg,image/png,image/gif",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(file, response) {
                $("#image_id").val(response.image_id);
                //console.log(response)
            }
        });
    </script>
@endsection
