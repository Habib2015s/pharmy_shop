{{-- ============================================================
     فایل: resources/views/shop/partials/medicine-card.blade.php
     توضیح: کارت محصول — قابل استفاده مجدد در همه صفحات
     ============================================================ --}}
<div class="medicine-card">
    {{-- تصویر --}}
    @if($med->image)
        <img src="{{ asset('storage/'.$med->image) }}"
             class="card-img-top" alt="{{ $med->name }}">
    @else
        <div class="card-img-placeholder">
            <i class="fas fa-pills"></i>
        </div>
    @endif

    <div class="card-body">
        {{-- badge (اختیاری) --}}
        @if(isset($badge))
            <span class="badge bg-success mb-1">{{ $badge }}</span>
        @endif
        @if($med->isLowStock())
            <span class="badge bg-warning text-dark mb-1">موجودی محدود</span>
        @endif

        {{-- دسته‌بندی --}}
        <div class="category-badge">{{ $med->category->name }}</div>

        {{-- نام --}}
        <h6 class="med-name">{{ $med->name }}</h6>
        <div class="generic-name">{{ $med->generic_name }}</div>

        {{-- وضعیت موجودی --}}
        <div class="mt-1">
            @if($med->stock > 0)
                <span class="stock-ok">
                    <i class="fas fa-check-circle me-1"></i>موجود ({{ $med->stock }} {{ $med->unit }})
                </span>
            @else
                <span class="stock-low">
                    <i class="fas fa-times-circle me-1"></i>ناموجود
                </span>
            @endif
        </div>

        {{-- قیمت و دکمه --}}
        <div class="price-section">
            <div>
                <div class="price">
                    {{ number_format($med->sale_price) }}
                    <small>تومان</small>
                </div>
            </div>

            @if($med->stock > 0)
                <form action="{{ route('shop.cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                    <input type="hidden" name="quantity"    value="1">
                    <button type="submit" class="btn-add" title="افزودن به سبد">
                        <i class="fas fa-plus"></i>
                    </button>
                </form>
            @else
                <button class="btn-add" disabled style="opacity:.4;cursor:not-allowed">
                    <i class="fas fa-ban"></i>
                </button>
            @endif
        </div>
    </div>
</div>
