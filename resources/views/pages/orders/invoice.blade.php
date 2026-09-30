<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <style>
            body {
                font-family: DejaVu Sans;
            }
        </style>

        <title>Invoice #{{ $order->id }}</title>
    </head>

    <body>
        <div>
            <div>
                <p>
                    <strong>Order number:</strong>
                    #{{ $order->id }}
                </p>

                <p>
                    <strong>Status:</strong>
                    {{ $order->status }}
                </p>

                <p>
                    <strong>Delivery address:</strong>
                    {{ $order->delivery_address }}
                </p>
            </div>

            <h2>Products</h2>

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

                <strong>Total</strong>

                <strong>
                    €{{ number_format($total, 2) }}
                </strong>
            </div>
        </div>
    </body>
</html>



