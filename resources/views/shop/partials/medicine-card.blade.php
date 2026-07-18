<div class="medicine-card">

    <div class="card-image">

        @if(isset($badge))
            <span class="badge badge-sale">{{ $badge }}</span>
        @endif

        @if($med->isLowStock())
            <span class="badge badge-stock">
                موجودی محدود
            </span>
        @endif

        @if($med->image)
            <img src="{{ asset('storage/'.$med->image) }}"
                 alt="{{ $med->name }}">
        @else
            <div class="card-img-placeholder">
                <i class="fas fa-pills"></i>
            </div>
        @endif

    </div>

    <div class="card-content">

        <span class="category-badge">
            {{ $med->category->name }}
        </span>

        <h5 class="med-name">
            {{ $med->name }}
        </h5>

        <div class="generic-name">
            {{ $med->generic_name }}
        </div>

        @if($med->stock>0)
            <div class="stock stock-success">
                <i class="fas fa-check-circle"></i>
                {{ $med->stock }} {{ $med->unit }} موجود
            </div>
        @else
            <div class="stock stock-danger">
                <i class="fas fa-times-circle"></i>
                ناموجود
            </div>
        @endif

        <div class="bottom-section">

            <div class="price">
                {{ number_format($med->sale_price) }}
                <small>تومان</small>
            </div>

            @if($med->stock > 0)

                <form action="{{ route('shop.cart.add') }}" method="POST">
                    @csrf
                    <input type="hidden" name="medicine_id" value="{{ $med->id }}">
                    <input type="hidden" name="quantity" value="1">

                    <button type="submit" class="btn-add" title="افزودن به سبد خرید">
                        <i class="fas fa-cart-plus"></i>
                    </button>
                </form>

            @else

                <button type="button" class="btn-add disabled" disabled>
                    <i class="fas fa-ban"></i>
                </button>

            @endif

        </div>

    </div>

</div>
