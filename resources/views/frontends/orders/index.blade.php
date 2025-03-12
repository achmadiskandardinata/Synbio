@extends('frontends.layouts.app', ['title' => 'Orders Detail'])

@section('content')
    <section id="order">
        <div class="container">
            <div class="row" id="table-hover-row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Order</h4>
                        </div>
                        <div class="card-content">
                            @if ($orders->isEmpty())
                                <div class="card-body">
                                    <p class="text-center">Tidak ada pesanan
                                    </p>
                                </div>
                            @else
                                <!-- table hover -->
                                <div class="table-responsive">
                                    <table class="table table-hover mb-0">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Invoice</th>
                                                <th>Total Harga</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($orders as $order)
                                                <tr>
                                                    <td>
                                                        {{ $loop->iteration }}
                                                    </td>
                                                    <td>
                                                        @if ($order->invoice_number)
                                                            {{ $order->invoice_number }}
                                                        @else
                                                            <p>Nomor Invoice Belum Tersedia</p>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->status == 'PENDING' || $order->status == 'SUCCESS')
                                                            {{ moneyFormat($order->total_price + $order->shipping_cost) }}
                                                        @else
                                                            {{ moneyFormat($order->total_price) }}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->status == 'PENDING')
                                                            <span class="badge bg-warning">{{ $order->status }}</span>
                                                        @elseif ($order->status == 'SUCCESS')
                                                            <span class="badge bg-success">{{ $order->status }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $order->status }}</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if ($order->status == 'PENDING')
                                                            <a href="{{ route('payments.index', $order->id) }}"
                                                                class="btn btn-warning btn-sm">
                                                                <i class="fas fa-credit-card"></i>
                                                            </a>
                                                        @elseif ($order->status == 'SUCCESS')
                                                            <a href="{{ route('invoice', ['orderId' => $order->id]) }}" class="btn btn-success btn-sm" target="_blank">
                                                                <i class="fas fa-file-invoice"></i>
                                                            </a>
                                                        @else
                                                            <a href="{{ route('orders.detail', [$order->id]) }}"
                                                                class="btn btn-secondary btn-sm">
                                                                <i class="fas fa-shopping-cart"></i>
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
