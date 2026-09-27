import { createRoot } from 'react-dom/client';
import { useState } from 'react';
import { getCartKey } from '../cart';
import styles from './checkout.module.css';

function Checkout() {
    const [cart] = useState(
        JSON.parse(localStorage.getItem(getCartKey())) || []
    );
    const [address, setAddress] = useState(user.address || '');

    async function handleSubmit() {
        const response = await fetch('/checkout', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: JSON.stringify({
                cart,
                address,
            }),
        });

        const data = await response.json();

        if (response.ok) {
            localStorage.removeItem(getCartKey());
            window.location.href = `/orders/${data.id}`;
        }
    }

    const total = cart.reduce(
        (sum, item) => sum + Number(item.price) * item.quantity, 0
    );

    return (
        <div className={styles.checkout}>
            <div className={styles.panel}>
                <div className={styles.header}>
                    <h1 className={styles.title}>Checkout</h1>
                    <span className={styles.headerTag}>Order</span>
                </div>

                <div className={styles.content}>
                    <div className={styles.formCard}>
                        <div className={styles.userGrid}>
                            <div className={styles.field}>
                                <span className={styles.label}>Username</span>
                                <div className={styles.valueBox}>{user.username}</div>
                            </div>

                            <div className={styles.field}>
                                <span className={styles.label}>Email</span>
                                <div className={styles.valueBox}>{user.email}</div>
                            </div>

                            <div className={`${styles.field} ${styles.fieldFull}`}>
                                <label htmlFor="address" className={styles.label}>Delivery address</label>
                                <input
                                    id="address"
                                    className={styles.input}
                                    type="text"
                                    value={address}
                                    onChange={event => setAddress(event.target.value)}
                                    placeholder="Enter your address"
                                />
                            </div>
                        </div>

                        <h2 className={styles.sectionTitle}>Products</h2>
                        <div className={styles.products}>
                            {cart.map(product => (
                                <div key={product.id} className={styles.productRow}>
                                    <div className={styles.productMeta}>
                                        <span className={styles.productName}>{product.name}</span>
                                        <span className={styles.productDetails}>Qty: {product.quantity}</span>
                                    </div>
                                    <span className={styles.productPrice}>€{(Number(product.price) * product.quantity).toFixed(2)}</span>
                                </div>
                            ))}
                        </div>
                    </div>

                    <aside className={styles.summaryCard}>
                        <h2 className={styles.sectionTitle}>Order summary</h2>

                        <div className={styles.summaryTotal}>
                            <span>Total</span>
                            <strong>€{total.toFixed(2)}</strong>
                        </div>

                        <button type="button" className={styles.confirmButton} onClick={handleSubmit}>
                            Confirm order
                        </button>
                    </aside>
                </div>
            </div>
        </div>
    );
}

const root = createRoot(document.getElementById('checkout'));

root.render(<Checkout />);