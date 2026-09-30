<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <style>
            * {
                box-sizing: border-box;
            }

            body {
                margin: 0;
                padding: 32px 16px;
                background: #f3f4f6;
                color: #1f2937;
                font-family: DejaVu Sans, sans-serif;
                line-height: 1.5;
            }

            body > div {
                width: auto;
                max-width: 680px;
                margin: 0 auto;
                padding: 32px;
                background: #ffffff;
                border: 1px solid #d1d5db;
                border-radius: 8px;
            }

            body > div > div:first-child {
                padding-bottom: 16px;
                border-bottom: 1px solid #d1d5db;
            }

            p {
                margin: 0 0 8px;
            }

            p:last-child {
                margin-bottom: 0;
            }

            h2 {
                margin: 24px 0 8px;
                padding-bottom: 8px;
                border-bottom: 2px solid #2563eb;
                color: #111827;
                font-size: 1.25rem;
            }

            h2 + div > div {
                display: table;
                width: 100%;
                padding: 10px 0;
                border-bottom: 1px solid #e5e7eb;
            }

            h2 + div > div span {
                display: table-cell;
                vertical-align: middle;
            }

            h2 + div > div span:last-child {
                width: 35%;
                text-align: right;
            }

            h2 + div + div {
                display: table;
                width: 100%;
                margin-top: 16px;
                padding-top: 16px;
                border-top: 2px solid #111827;
                font-size: 1.1rem;
            }

            h2 + div + div strong {
                display: table-cell;
            }

            h2 + div + div strong:last-child {
                width: 35%;
                text-align: right;
            }

            @media print {
                body {
                    padding: 0;
                    background: #ffffff;
                }

                body > div {
                    max-width: none;
                    padding: 0;
                    border: 0;
                }
            }
        </style>

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



