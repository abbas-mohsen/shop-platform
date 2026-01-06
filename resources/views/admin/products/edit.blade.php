@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="bg-white rounded-3 shadow-sm p-4">
            <h2 class="mb-4">Edit Product</h2>

            <form action="{{ route('admin.products.update', $product) }}"
                  method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- NAME --}}
                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name"
                           value="{{ old('name', $product->name) }}"
                           class="form-control @error('name') is-invalid @enderror">
                    @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- CATEGORY --}}
                <div class="mb-3">
                    <label class="form-label">Category</label>
                    <select name="category_id"
                            class="form-select @error('category_id') is-invalid @enderror">
                        <option value="">-- Choose category --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- DESCRIPTION --}}
                <div class="mb-3">
                    <label class="form-label">Description</label>
                    <textarea name="description" rows="3"
                              class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
                    @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- PRICE + STOCK --}}
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Price ($)</label>
                        <input type="number" step="0.01" name="price"
                               value="{{ old('price', $product->price) }}"
                               class="form-control @error('price') is-invalid @enderror">
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Stock</label>
                        <input type="number" name="stock"
                               value="{{ old('stock', $product->stock) }}"
                               class="form-control @error('stock') is-invalid @enderror">
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- AVAILABLE SIZES --}}
                @php
                    $selectedSizes = old('sizes', $product->sizes ?? []);
                    if (!is_array($selectedSizes)) {
                        $selectedSizes = is_string($selectedSizes)
                            ? array_filter(array_map('trim', explode(',', $selectedSizes)))
                            : [];
                    }
                    $categoryName = strtolower(optional($product->category)->name ?? '');
                    $isShoesCategory = false;
                    if (
                        strpos($categoryName, 'shoe') !== false ||     
                        strpos($categoryName, 'sneaker') !== false ||  
                        strpos($categoryName, 'boot') !== false        
                    ) {
                        $isShoesCategory = true;
                    }
                    if (!$isShoesCategory && !empty($selectedSizes)) {
                        foreach ($selectedSizes as $sz) {
                            if (is_numeric($sz)) {
                                $num = (int)$sz;
                                if ($num >= 20 && $num <= 47) {
                                    $isShoesCategory = true;
                                    break;
                                }
                            }
                        }
                    }
                @endphp

                <div class="mb-3">
                    <label class="form-label">Available Sizes</label>

                    {{-- If shoes → show shoe sizes; otherwise clothing sizes --}}
                    @if($isShoesCategory)
                        <div class="d-flex flex-wrap">
                            @foreach($shoeSizes as $size)
                                <div class="form-check form-check-inline mb-1 me-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="sizes[]"
                                           id="size_{{ $size }}"
                                           value="{{ $size }}"
                                           {{ in_array((string)$size, $selectedSizes, true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="size_{{ $size }}">
                                        {{ $size }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-1">
                            Shoes sizes from 20 to 47.
                        </small>
                    @else
                        <div class="d-flex flex-wrap">
                            @foreach($clothingSizes as $size)
                                <div class="form-check form-check-inline mb-1 me-2">
                                    <input class="form-check-input"
                                           type="checkbox"
                                           name="sizes[]"
                                           id="size_{{ $size }}"
                                           value="{{ $size }}"
                                           {{ in_array($size, $selectedSizes, true) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="size_{{ $size }}">
                                        {{ $size }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        <small class="text-muted d-block mt-1">
                            Clothing sizes (tops / pants): S, M, L, XL, XXL.
                        </small>
                    @endif

                    @error('sizes')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                {{-- CURRENT IMAGE --}}
                <div class="mb-3">
                    <label class="form-label d-block">Current Image</label>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}" class="img-thumbnail mb-2"
                             style="max-height: 150px;">
                    @else
                        <span class="text-muted">No image uploaded</span>
                    @endif
                </div>

                {{-- CHANGE IMAGE --}}
                <div class="mb-4">
                    <label class="form-label">Change Image (optional)</label>
                    <input type="file" name="image"
                           class="form-control @error('image') is-invalid @enderror">
                    @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                {{-- ACTION BUTTONS --}}
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                        Back
                    </a>
                    <button type="submit" class="btn btn-primary">
                        Update
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
