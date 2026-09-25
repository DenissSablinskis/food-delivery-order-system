import { createRoot } from 'react-dom/client';
import CartItem from './components/CartItem';
import { useState } from 'react';

function App() {
    const [cart, setCart] = useState(
        JSON.parse(localStorage.getItem('cart')) || []
    );

    function updateQuantity(id, quantity) {
    setCart(prevCart => {
        const updatedCart = prevCart.map(item =>
            item.id === id
                ? { ...item, quantity: quantity }
                : item
        );

        localStorage.setItem('cart', JSON.stringify(updatedCart));

        return updatedCart;
    });
}

    return (
        <div>
            <h1>Cart</h1>
            {cart.map(product => (
                <CartItem key={product.id} product={product} updateQuantity={updateQuantity}/>
            ))}
        </div>
    );
}

const root = createRoot(document.getElementById('app'));

root.render(<App />);