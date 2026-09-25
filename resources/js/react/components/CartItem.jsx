import styles from '../cart.module.css';

function CartItem({ product, updateQuantity, removeFromCart }) {
    return (
        <article className={styles.item}>
            <div className={styles.itemInfo}>
                <h3>{product.name}</h3>
                <p className={styles.price}>€{product.price}</p>
            </div>

            <div className={styles.controls}>
                <button
                    type="button"
                    className={styles.button}
                    onClick={() => {
                        if (product.quantity > 1) {
                            updateQuantity(product.id, product.quantity - 1);
                        }
                    }}
                >
                    −
                </button>

                <span className={styles.quantity}>{product.quantity}</span>

                <button
                    type="button"
                    className={styles.button}
                    onClick={() => updateQuantity(product.id, product.quantity + 1)}
                >
                    +
                </button>

                <button
                    type="button"
                    className={`${styles.button} ${styles.removeButton}`}
                    onClick={() => removeFromCart(product.id)}
                >
                    ×
                </button>
            </div>
        </article>
    );
}

export default CartItem;
