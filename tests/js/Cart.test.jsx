import { render, screen, fireEvent } from '@testing-library/react';
import { App } from '../../resources/js/react/cart';

jest.mock('../../resources/js/cart', () => ({
    getCartKey: () => 'test_cart',
    updateCartCount: jest.fn(),
}));

beforeEach(() => {
    localStorage.clear();

    global.translations = {
        cartTitle: 'Shopping cart',
        cartEmpty: 'Your cart is empty',
        cartTotal: 'Total',
        cartCheckout: 'Checkout',
    };
});

test('shows empty cart message when cart has no products', () => {
    render(<App />);

    expect(screen.getByText('Your cart is empty')).toBeInTheDocument();
    expect(screen.queryByText('Checkout')).not.toBeInTheDocument();
});

test('calculates total price correctly', () => {
    localStorage.setItem(
        'test_cart',
        JSON.stringify([
            { id: 1, name: 'Apple', price: 2.50, quantity: 2 },
            { id: 2, name: 'Pear', price: 3.00, quantity: 1 },
        ])
    );

    render(<App />);

    expect(screen.getByText('€8.00')).toBeInTheDocument();
});

test('saves updated quantity to localStorage', () => {
    localStorage.setItem(
        'test_cart',
        JSON.stringify([
            { id: 1, name: 'Apple', price: 2.50, quantity: 1 },
        ])
    );

    render(<App />);

    fireEvent.click(screen.getByText('+'));

    expect(
        JSON.parse(localStorage.getItem('test_cart'))
    ).toEqual([
        { id: 1, name: 'Apple', price: 2.50, quantity: 2 },
    ]);
});

test('shows checkout link when cart contains products', () => {
    localStorage.setItem(
        'test_cart',
        JSON.stringify([
            { id: 1, name: 'Apple', price: 2.50, quantity: 1 },
        ])
    );

    render(<App />);

    expect(screen.getByText('Checkout')).toHaveAttribute('href', '/checkout');
});