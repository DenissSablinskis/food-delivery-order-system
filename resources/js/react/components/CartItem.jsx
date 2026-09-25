function CartItem({ product, updateQuantity, removeFromCart }) {
    return (
        <div>
            <h3>{product.name}</h3>
            <p>€{product.price}</p>

            <button
                onClick={() => {
                    if (product.quantity > 1) {
                        updateQuantity(product.id, product.quantity - 1);
                    }
                }}
            >
                -
            </button>

            <span>{product.quantity}</span>

            <button
                onClick={() => updateQuantity(product.id, product.quantity + 1)}
            >
                +
            </button>

            <button
                onClick={() => removeFromCart(product.id)}
            >
                x
            </button>
        </div>
    );
}

export default CartItem;