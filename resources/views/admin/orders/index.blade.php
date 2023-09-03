@extends('admin.layouts.app')


@section('content')
    <section class="content">
        <!-- Default box -->
        <div class="container-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <div class="input-group input-group" style="width: 250px;">
                            <input type="text" name="table_search" class="form-control float-right" placeholder="Search">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-default">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body table-responsive p-0">
                    <table class="table table-hover text-nowrap">
                        <thead>
                            <tr>
                                <th>Orders #</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Date Purchased</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($orders->count() > 0)
                                @foreach ($orders as $order)
                                    <tr>
                                        <td>{{ $order->code_order }}</td>
                                        <td>{{ $order->name }}</td>
                                        <td>{{ $order->email }}</td>
                                        <td>{{ $order->telepon }}</td>
                                        <td>
                                            @if ($order->status == 'Unpaid')
                                                <span class="badge bg-warning">{{ $order->status }}</span>
                                            @else
                                                <span class="badge bg-success">{{ $order->status }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $order->quantity }}</td>
                                        <td>Rp.{{ formatCurrency($order->total) }}</td>
                                        <td>{{ formatWaktu($order->created_at) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td class="text-center" colspan="12">Data Tidak Ditemukan</td>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
                <div class=" card-footer clearfix">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
@endsection
