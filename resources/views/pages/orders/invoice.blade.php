<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <style>{!! $invoiceCss !!}</style>

        <title>{{ __('messages.invoiceTitle') }} #{{ $order->id }}</title>
    </head>

    <body>
        <div>
            <div>
                <p>
                    <strong>{{ __('messages.invoiceCustomer') }}:</strong>
                    {{ $order->user->name }} {{ $order->user->surname }}
                </p>
                <p>
                    <strong>{{ __('messages.invoiceOrderNumber') }}:</strong>
                    #{{ $order->id }}
                </p>

                <p>
                    <strong>{{ __('messages.invoiceOrderDate') }}:</strong>
                    {{ $order->created_at->format('d.m.Y H:i') }}
                </p>

                <p>
                    <strong>{{ __('messages.invoiceDeliveryAddress') }}:</strong>
                    {{ $order->delivery_address }}
                </p>
            </div>

            <h2>{{ __('messages.invoiceProduct') }}</h2>

            <div>

                @foreach ($order->orderedProducts as $orderedProduct)

                    <div>

                        <span>
                            {{ $orderedProduct->product->name }}
                        </span>

                        <span>
                            {{ $orderedProduct->product_count }}
                            ×
                            €{{ number_format($orderedProduct->unit_price_at_purchase, 2) }}
                        </span>

                    </div>

                @endforeach

            </div>

            <div>

                <strong>{{ __('messages.invoiceTotal') }}:</strong>

                <strong>
                    €{{ number_format($total, 2) }}
                </strong>
            </div>
        </div>
    </body>
</html>


