import './bootstrap';
import { updateCartCount } from './cart';

updateCartCount(); // Atjaunina groza pogas skaitītāju, kad lapa tiek ielādēta

// ----------------BURGERA IZVĒLNE----------------------

const burgerBtn = document.getElementById('burgerBtn');
const navLinks = document.getElementById('navLinks');

// Pārslēgt navigācijas saites un burgera ikonu

burgerBtn.addEventListener('click', () => {
    burgerBtn.classList.toggle('open');
    navLinks.classList.toggle('open');
});


// -------------------TĒMAS IZVĒLE--------------------

const themeToggleBtn = document.getElementById('themeToggle');
const currentTheme = localStorage.getItem('theme');

// Iestatīt sākotnējo tēmu, ja tā ir saglabāta

if (currentTheme) {
    document.documentElement.setAttribute('data-theme', currentTheme);
}

// Pārslēgt tēmu un saglabāt izvēli localStorage

themeToggleBtn.addEventListener('click', () => {
    let theme = document.documentElement.getAttribute('data-theme');
    
    if (theme === 'dark') {
        document.documentElement.removeAttribute('data-theme'); 
        localStorage.setItem('theme', 'light');
    } else {
        document.documentElement.setAttribute('data-theme', 'dark'); 
        localStorage.setItem('theme', 'dark');
    }
});

// ----------------PRĒCES DAUDZUMA MAIŅA------------------------------

// Izvelēties prēču daudzumu, kuru pievienot grozam

document.addEventListener('DOMContentLoaded', () => {
    const quantityControls = document.querySelectorAll('.quantity-control');

    quantityControls.forEach(control => {
        const minusButton = control.querySelector('.quantity-minus');
        const plusButton = control.querySelector('.quantity-plus');
        const quantityInput = control.querySelector('.quantity-input');

        const min = Number(quantityInput.min);
        const max = Number(quantityInput.max);

        // Minusa pogas funkcionalitāte

        minusButton.addEventListener('click', () => {
            let quantity = Number(quantityInput.value);

            if (quantity > min) {
                quantityInput.value = quantity - 1;
            }
        });

        // Plusa pogas funkcionalitāte

        plusButton.addEventListener('click', () => {
            let quantity = Number(quantityInput.value);

            if (quantity < max) {
                quantityInput.value = quantity + 1;
            }
        });

        // Daudzuma lauka uzvedība ievadot vertību <min, >max vai atstajot tukšumu

        quantityInput.addEventListener('change', () => {
            let quantity = Number(quantityInput.value);

            if (quantity < min) {
                quantityInput.value = min;
            }

            if (quantity > max) {
                quantityInput.value = max;
            }

            if (isNaN(quantity)) {
                quantityInput.value = min;
            }
        });
    });
});

// ----------------GROZA POGAS------------------------------

// Pievienot grozam ar izvēlēto daudzumu

const addToCartButtons = document.querySelectorAll('.add-to-cart');

addToCartButtons.forEach(button => {
    button.addEventListener('click', () => {

        const form = button.closest('form'); // Atrod tuvāko formu, kurā atrodas poga
        const quantityInput = form.querySelector('.quantity-input'); // Iegūst daudzuma ievades lauku šajā formā
        

        const product = {
            id: button.dataset.id,
            name: button.dataset.name,
            price: button.dataset.price,
            quantity: Number(quantityInput.value)
        };

        // Iegūst esošo grozu no localStorage vai izveido jaunu, ja tas vēl neeksistē
        const cart = JSON.parse(localStorage.getItem('cart')) || [];

        // Pārbauda, vai produkts jau ir grozā
        const existingProduct = cart.find(item => item.id === product.id);

        if (existingProduct) {
            existingProduct.quantity += product.quantity;
        } else {
            cart.push(product);
        }
        

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
    });
});