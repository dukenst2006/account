<address>
    {{ $address->address_one }}<br/>
    @if(!empty($address->address_two))
        {{ $address->address_two }}<br/>
    @endif
    {{ $address->city }}, {{ $address->state }} {{ $address->zip_code }}
</address>