import { render, screen, fireEvent } from '@testing-library/react';
import CartItem from '../../resources/js/react/components/CartItem';

test('calls updateQuantity when the plus button is clicked', () => {
    const product = {
        id: 1,
        name: 'Apple',
        price: 2.50,
        quantity: 1,
    };

    const updateQuantity = jest.fn();
    const removeFromCart = jest.fn();

    render(
        <CartItem
            product={product}
            updateQuantity={updateQuantity}
            removeFromCart={removeFromCart}
        />
    );

    fireEvent.click(screen.getByText('+'));

    expect(updateQuantity).toHaveBeenCalledWith(1, 2);
});

test('calls removeFromCart when the remove button is clicked', () => {
    const product = {
        id: 1,
        name: 'Apple',
        price: 2.50,
        quantity: 1,
    };

    const updateQuantity = jest.fn();
    const removeFromCart = jest.fn();

    render(
        <CartItem
            product={product}
            updateQuantity={updateQuantity}
            removeFromCart={removeFromCart}
        />
    );

    fireEvent.click(screen.getByText('×'));

    expect(removeFromCart).toHaveBeenCalledWith(1);
});

test('does not decrease quantity below 1', () => {
    const product = {
        id: 1,
        name: 'Apple',
        price: 2.50,
        quantity: 1,
    };

    const updateQuantity = jest.fn();
    const removeFromCart = jest.fn();

    render(
        <CartItem
            product={product}
            updateQuantity={updateQuantity}
            removeFromCart={removeFromCart}
        />
    );

    fireEvent.click(screen.getByText('−'));

    expect(updateQuantity).not.toHaveBeenCalled();
});

test('displays product name, price and quantity', () => {
    const product = {
        id: 1,
        name: 'Apple',
        price: 2.50,
        quantity: 3,
    };

    render(
        <CartItem
            product={product}
            updateQuantity={jest.fn()}
            removeFromCart={jest.fn()}
        />
    );

    expect(screen.getByText('Apple')).toBeInTheDocument();
    expect(screen.getByText('€2.5')).toBeInTheDocument();
    expect(screen.getByText('3')).toBeInTheDocument();
});