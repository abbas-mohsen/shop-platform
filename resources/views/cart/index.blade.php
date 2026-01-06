@extends('layouts.app')

@section('title', 'Your Cart')

@section('content')
<div class="container">
    <h2 class="mb-4">Your Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @php
        $cart = $cart ?? session('cart', []);
    @endphp

    @if(empty($cart))
        <p>Your cart is empty.</p>
    @else
        <table class="table align-middle">
            <thead>
            <tr>
                <th style="width: 35%">Product</th>
                <th style="width: 10%">Size</th>
                <th style="width: 10%">Price</th>
                <th style="width: 20%">Quantity / Size</th>
                <th style="width: 15%">Subtotal</th>
                <th style="width: 10%"></th>
            </tr>
            </thead>

            <tbody>
            @foreach($cart as $productId => $item)
                @php
                    $availableSizes = $item['sizes'] ?? [];
                    if (!is_array($availableSizes)) {
                        $availableSizes = array_filter(array_map('trim', explode(',', $availableSizes)));
                    }
                    $currentSize = $item['size'] ?? null;
                @endphp

                <tr>
                    {{-- Product + image --}}
                    <td>
                        <div class="d-flex align-items-center">
                            @if(!empty($item['image']))
                                <img src="{{ asset('storage/' . $item['image']) }}"
                                     alt="{{ $item['name'] }}"
                                     class="me-3 rounded"
                                     style="width:60px;height:60px;object-fit:cover;">
                            @else
                                <div class="me-3 rounded bg-light d-flex align-items-center justify-content-center"
                                     style="width:60px;height:60px;font-size:12px;">
                                    No image
                                </div>
                            @endif

                            <div>
                                <a href="{{ route('products.show', $productId) }}"
                                   class="fw-semibold text-decoration-none">
                                    {{ $item['name'] }}
                                </a>
                            </div>
                        </div>
                    </td>

                    {{-- Current size --}}
                    <td>
                        @if($currentSize)
                            {{ $currentSize }}
                        @elseif(!empty($availableSizes))
                            -
                        @else
                            N/A
                        @endif
                    </td>

                    {{-- Price --}}
                    <td>${{ number_format($item['price'], 2) }}</td>

                    {{-- Quantity + size selector --}}
                    <td>
                        <form action="{{ route('cart.update', $productId) }}"
                              method="POST" class="d-flex align-items-center">
                            @csrf

                            <input type="number"
                                   name="quantity"
                                   value="{{ $item['quantity'] }}"
                                   min="1"
                                   class="form-control form-control-sm me-2"
                                   style="width:80px;">

                            @if(!empty($availableSizes))
                                <select name="size"
                                        class="form-select form-select-sm me-2"
                                        style="width:110px;">
                                    <option value="">Size</option>
                                    @foreach($availableSizes as $size)
                                        <option value="{{ $size }}"
                                            {{ $currentSize === $size ? 'selected' : '' }}>
                                            {{ $size }}
                                        </option>
                                    @endforeach
                                </select>
                            @else
                                <input type="hidden" name="size" value="">
                            @endif

                            <button class="btn btn-sm btn-outline-secondary" type="submit">
                                Update
                            </button>
                        </form>
                    </td>

                    {{-- Subtotal --}}
                    <td>${{ number_format($item['price'] * $item['quantity'], 2) }}</td>

                    {{-- Remove --}}
                    <td>
                        <form action="{{ route('cart.remove', $productId) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-outline-danger" type="submit">
                                Remove
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

                <div class="d-flex justify-content-between align-items-center mt-3">
            <h4>Total: ${{ number_format($total, 2) }}</h4>
        </div>

        {{-- Checkout details (address + payment) --}}
        <div class="row mt-3">
            <div class="col-md-8">
                <form id="checkout-form"
                      action="{{ route('checkout.store') }}"
                      method="POST"
                      class="bg-white rounded-3 shadow-sm p-3">
                    @csrf

                    <h5 class="mb-3">Checkout details</h5>

                    {{-- ADDRESS --}}
                    <div class="mb-3">
                        <label class="form-label">Shipping address</label>
                        <textarea name="address" rows="2"
                                  class="form-control @error('address') is-invalid @enderror"
                                  placeholder="Street, building, city, phone...">{{ old('address') }}</textarea>
                        @error('address')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- PAYMENT METHOD --}}
                    <div class="mb-3" style="max-width: 260px;">
                        <label class="form-label">Payment method</label>
                        <select name="payment_method"
                                class="form-select @error('payment_method') is-invalid @enderror">
                            <option value="">Choose...</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>
                                Cash
                            </option>
                            <option value="card" {{ old('payment_method') === 'card' ? 'selected' : '' }}>
                                Card
                            </option>
                        </select>
                        @error('payment_method')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">
                            Total: ${{ number_format($total, 2) }}
                        </span>

                        <button type="submit" class="btn btn-primary">
                            Place order
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
@endsection

