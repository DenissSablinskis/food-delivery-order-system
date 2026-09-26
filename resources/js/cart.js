export function getCartKey() {
    // Iegūst lietotāja ID no globālā mainīgā, kas tiek iestatīts Blade šablonā
    return `cart_${userId}`; 
}

export function updateCartCount() {
    // Iegūst groza datus no localStorage un parada kopējo daudzumu groza pogā
    const cart = JSON.parse(localStorage.getItem(getCartKey())) || [];
    const cartCounts = document.querySelectorAll('.cart-count');

    const totalQuantity = cart.reduce(
        (sum, item) => sum + item.quantity,
        0
    );

    cartCounts.forEach(count => {
        count.textContent = totalQuantity; // Atjaunina groza pogas skaitītāju ar kopējo daudzumu
    });
}