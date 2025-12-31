@extends('layouts.app')

@section('title', 'Manage Orders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0">Manage Orders</h1>

    @if($orders->count())
        <span class="text-muted small">
            Showing {{ $orders->firstItem() }}–{{ $orders->lastItem() }} of {{ $orders->total() }} orders
        </span>
    @endif
</div>

@if($orders->isEmpty())
    <div class="alert alert-info">
        No orders found yet.
    </div>
@else
    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Date</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Payment</th>
                            <th class="text-end">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        @php
                            // simple mapping of status -> bootstrap color
                            $statusClass = 'secondary';
                            switch ($order->status) {
                                case 'pending':  $statusClass = 'warning'; break;
                                case 'paid':     $statusClass = 'success'; break;
                                case 'shipped':  $statusClass = 'primary'; break;
                                case 'cancelled':$statusClass = 'danger';  break;
                            }
                        @endphp

                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>
                                @if($order->user)
                                    <div class="fw-semibold">{{ $order->user->name }}</div>
                                    <div class="small text-muted">{{ $order->user->email }}</div>
                                @else
                                    <span class="text-muted">Guest</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $statusClass }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                            <td>{{ ucfirst($order->payment_method) }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.orders.show', $order) }}"
                                   class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        @if(method_exists($orders, 'links'))
            <div class="card-footer">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
@endif
@endsection
