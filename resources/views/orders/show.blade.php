@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card shadow-lg p-4">
        <h2 class="text-primary mb-4">Order Details</h2>

        <div class="row mb-3">
            <div class="col-md-6">
                <p><strong>Order ID:</strong> {{ $order->id }}</p>
                <p><strong>User:</strong> {{ $order->user->name }}</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="fw-bold fs-4 text-success">Total: ${{ number_format($order->total, 2) }}</p>
            </div>
        </div>

        <h4 class="text-secondary">Products</h4>
        <div class="table-responsive">
            <table class="table table-striped table-bordered mt-3">
                <thead class="table-dark">
                    <tr>
                        <th>Name</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Amount</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td class="text-center">{{ $product->qty }}</td>
                        <td class="text-end">${{ number_format($product->amount, 2) }}</td>
                        <td class="text-end">${{ number_format($product->total, 2) }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="text-end mt-4">
            <a href="{{ route('orders.index') }}" class="btn btn-outline-secondary">Back to Orders</a>
        </div>
    </div>
</div>
@endsection
