import { createRoot } from 'react-dom/client';
import { useState } from 'react';
import { getCartKey } from '../cart';

function Checkout() {
    const [cart] = useState(
        JSON.parse(localStorage.getItem(getCartKey())) || []
    );
    const [address, setAddress] = useState(user.address || '');

    const total = cart.reduce(
        (sum, item) => sum + Number(item.price) * item.quantity,0
    );

    return (
        <div>
            <h1>Checkout</h1>
            <p>Username: {user.username}</p>
            <p>Email: {user.email}</p>
            <label>
                Address:
                <input
                    type="text"
                    value={address}
                    onChange={event => setAddress(event.target.value)}
                />
            </label>
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