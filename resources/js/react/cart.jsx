import { createRoot } from 'react-dom/client';
import CartItem from './components/CartItem';

function App() {
    return (
        <div>
            <h1>Cart</h1>
            <CartItem />
        </div>
    );
}

const root = createRoot(document.getElementById('app'));

root.render(<App />);