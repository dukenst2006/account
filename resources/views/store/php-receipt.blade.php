@foreach ($receiptItems as $item)
    {{ $item->description }} x {{ number_format($item->quantity) }}
@endforeach