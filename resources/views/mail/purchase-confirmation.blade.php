@component('mail::message')
    # Order Confirmation

    Hello {{ $order->user->name }},

    Thank you for your order!

    **Order ID:** {{ $order->id }}
    **Total:** ₹{{ number_format($order->total, 2) }}

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent
