import { createRoot } from 'react-dom/client';

function Checkout() {
    return (
        <h1>Checkout</h1>
    );
}

const root = createRoot(document.getElementById('checkout'));

root.render(<Checkout />);