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
            // Atjaunina produkta daudzumu grozā, ja tas atrodas, un saglabā izmaiņas localStorage
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
            // Noņem produktu no groza un saglabā izmaiņas localStorage
            const updatedCart = prevCart.filter(item => item.id !== id);

            localStorage.setItem('cart', JSON.stringify(updatedCart));

            return updatedCart;
        });
    }

    // Aprēķina kopējo cenu grozā
    const total = cart.reduce(
        (sum, item) => sum + Number(item.price) * item.quantity, 0
    );

    return (
        <div className={styles.cart}>
            <h1 className={styles.title}>{translations.cartTitle}</h1>
            <div className={styles.items}>
                {cart.length === 0 ? (
                    <p>{translations.cartEmpty}</p> // Ja grozs ir tukšs, tiek parādīts paziņojums
                ) : (
                    cart.map(product => (
                        <CartItem
                            key={product.id}
                            product={product}
                            updateQuantity={updateQuantity}
                            removeFromCart={removeFromCart}
                        />
                    ))
                )}
            </div>
            <div className={styles.total}>
                <span>{translations.cartTotal}:</span>
                <span>€{total.toFixed(2)}</span>
            </div>
        </div>
    );
}

const root = createRoot(document.getElementById('app'));

root.render(<App />);
