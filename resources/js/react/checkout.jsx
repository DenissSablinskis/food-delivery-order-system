import { createRoot } from 'react-dom/client';
import { useState } from 'react';

function Checkout() {
    const [cart] = useState(
        JSON.parse(localStorage.getItem('cart')) || []
    );

    const total = cart.reduce(
        (sum, item) => sum + Number(item.price) * item.quantity,0
    );

    return (
        <div>
            <h1>Checkout</h1>

            {cart.map(product => (
                <div key={product.id}>
                    <h3>{product.name}</h3>
                    <p>Price: €{product.price}</p>
                    <p>Quantity: {product.quantity}</p>
                </div>
            ))}
            <h2>Total: €{total.toFixed(2)}</h2>
        </div>
    );
}

const root = createRoot(document.getElementById('checkout'));

root.render(<Checkout />);