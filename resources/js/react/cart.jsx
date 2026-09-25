import { createRoot } from 'react-dom/client';
import CartItem from './components/CartItem';
import { useState } from 'react';
import styles from './cart.module.css';

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

    function removeFromCart(id) {
        setCart(prevCart => {
            const updatedCart = prevCart.filter(item => item.id !== id);

            localStorage.setItem('cart', JSON.stringify(updatedCart));

            return updatedCart;
        });
    }

    return (
        <div className={styles.cart}>
            <h1 className={styles.title}>Cart</h1>
            <div className={styles.items}>
                {cart.map(product => (
                    <CartItem key={product.id} product={product} updateQuantity={updateQuantity} removeFromCart={removeFromCart}/>
                ))}
            </div>
        </div>
    );
}

const root = createRoot(document.getElementById('app'));

root.render(<App />);
