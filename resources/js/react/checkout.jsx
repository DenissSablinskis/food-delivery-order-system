import { createRoot } from 'react-dom/client';
import { useState } from 'react';
import { getCartKey } from '../cart';
import styles from './checkout.module.css';

function Checkout() {
    const [cart] = useState(
        JSON.parse(localStorage.getItem(getCartKey())) || []
    );
    const [address, setAddress] = useState(user.address || '');
    const [serverError, setServerError] = useState('');
    const [nameError, setNameError] = useState('');
    const [surnameError, setSurnameError] = useState('');
    const [addressError, setAddressError] = useState('');
    const [name, setName] = useState(user.name || '');
    const [surname, setSurname] = useState(user.surname || '');

    /*
    * Šī funkcija tiek izpildīta pēc pogas "Apstiprināt pasūtījumu" nospiešanas.
    * Groza saturs, adrese, vārds un uzvārds tiek nosūtīti serverim.
    * Laravel validē saņemtos datus un, ja tie ir derīgi, izveido pasūtījumu datubāzē.
    */

    async function handleSubmit() {

        setServerError('');
        setAddressError('');
        setNameError('');
        setSurnameError('');

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
                name,
                surname
            }),
        });

        const data = await response.json();

        if (response.ok) {

            // Pēc veiksmīgas pasūtījuma izveides grozs tiek iztīrīts,
            // un lietotājs tiek novirzīts uz pasūtījuma lapu.

            localStorage.removeItem(getCartKey());
            window.location.href = `/orders/${data.id}`;

        } else {

            // Ja servera validācija neizdodas, kļūdas ziņojums tiek saglabāts attiecīgajā state mainīgajā atkarībā no kļūdas veida.

            if (data.errors) {

                data.errors.name ? setNameError(data.errors.name[0]) : false;
                data.errors.surname ? setSurnameError(data.errors.surname[0]) : false;
                data.errors.address ? setAddressError(data.errors.address[0]) : false;

            } else {

                data.message ? setServerError(data.message) : false;

            }

        }
    }

    const total = cart.reduce(
        (sum, item) => sum + Number(item.price) * item.quantity, 0
    );

    return (
        <div className={styles.checkout}>
            <div className={styles.panel}>
                <div className={styles.header}>
                    <h1 className={styles.title}>{translations.checkoutTitle}</h1>
                    <span className={styles.headerTag}>{translations.checkoutOrder}</span>
                </div>

                <div className={styles.content}>
                    <div className={styles.formCard}>
                        <div className={styles.userGrid}>
                            <div className={styles.field}>
                                <span className={styles.label}>{translations.checkoutUsername}</span>
                                <div className={styles.valueBox}>{user.username}</div>
                            </div>

                            <div className={styles.field}>
                                <span className={styles.label}>{translations.checkoutEmail}</span>
                                <div className={styles.valueBox}>{user.email}</div>
                            </div>

                            {/* Ja lietotāja profilā ir vārds, tas tiek ievietots name state mainīgajā un attēlots ievades laukā. Pretējā gadījumā lietotājam vārds jāievada pašam. */}

                            <div className={`${styles.field} ${styles.fieldFull}`}>
                                <label htmlFor="name" className={styles.label}>{translations.checkoutName}</label>
                                <input
                                    id="name"
                                    className={styles.input}
                                    type="text"
                                    value={name}
                                    onChange={event => setName(event.target.value)}
                                    placeholder="Enter your name"
                                />

                                {/* Ja vārda validācija neizdodas, tiek parādīts kļūdas ziņojums. */}
                                {nameError && <span className={styles.error}>{nameError}</span>}
                            </div>

                            {/* Ja lietotāja profilā ir uzvārds, tas tiek ievietots surname state mainīgajā un attēlots ievades laukā. Pretējā gadījumā lietotājam uzvārds jāievada pašam. */}

                            <div className={`${styles.field} ${styles.fieldFull}`}>
                                <label htmlFor="surname" className={styles.label}>{translations.checkoutSurname}</label>
                                <input
                                    id="surname"
                                    className={styles.input}
                                    type="text"
                                    value={surname}
                                    onChange={event => setSurname(event.target.value)}
                                    placeholder="Enter your surname"
                                />

                                {/* Ja uzvārda validācija neizdodas, tiek parādīts kļūdas ziņojums. */}
                                {surnameError && <span className={styles.error}>{surnameError}</span>}
                            </div>

                            {/* Ja lietotāja profilā ir adrese, tā tiek ievietota address state mainīgajā un attēlota ievades laukā. Pretējā gadījumā lietotājam adrese jāievada pašam. */}

                            <div className={`${styles.field} ${styles.fieldFull}`}>
                                <label htmlFor="address" className={styles.label}>{translations.checkoutAddress}</label>
                                <input
                                    id="address"
                                    className={styles.input}
                                    type="text"
                                    value={address}
                                    onChange={event => setAddress(event.target.value)}
                                    placeholder="Enter your address"
                                />

                                {/* Ja adreses validācija neizdodas, tiek parādīts kļūdas ziņojums. */}
                                {addressError && <span className={styles.error}>{addressError}</span>}
                            </div>
                        </div>

                        <h2 className={styles.sectionTitle}>{translations.checkoutProducts}</h2>
                        <div className={styles.products}>
                            {cart.map(product => (
                                <div key={product.id} className={styles.productRow}>
                                    <div className={styles.productMeta}>
                                        <span className={styles.productName}>{product.name}</span>
                                        <span className={styles.productDetails}>{translations.checkoutQty}: {product.quantity}</span>
                                    </div>
                                    <span className={styles.productPrice}>€{(Number(product.price) * product.quantity).toFixed(2)}</span>
                                </div>
                            ))}
                        </div>
                    </div>

                    <aside className={styles.summaryCard}>
                        <h2 className={styles.sectionTitle}>{translations.checkoutOrderSummary}</h2>

                        <div className={styles.summaryTotal}>
                            <span>{translations.checkoutTotal}</span>
                            <strong>€{total.toFixed(2)}</strong>
                        </div>

                        {/* Ja rodas servera kļūda, tiek parādīts kļūdas ziņojums. */}
                        {serverError && <span className={styles.error}>{serverError}</span>}

                        <button type="button" className={styles.confirmButton} onClick={handleSubmit}>
                            {translations.checkoutSubmit}
                        </button>
                    </aside>
                </div>
            </div>
        </div>
    );
}

const root = createRoot(document.getElementById('checkout'));

root.render(<Checkout />);