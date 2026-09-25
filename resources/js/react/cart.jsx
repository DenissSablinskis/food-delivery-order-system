import { createRoot } from 'react-dom/client';
import CartItem from './components/CartItem';
import { useState } from 'react';

function App() {
    const [cart, setCart] = useState([]);

    function addToCart(product) {
        setCart(prev=>[...prev, product])
    }
    
    return (
        <div>
            <h1>Cart</h1>
            {cart.map(product => (
                <CartItem product={product}/>
            ))}
        </div>
    );
}

const root = createRoot(document.getElementById('app'));

root.render(<App />);