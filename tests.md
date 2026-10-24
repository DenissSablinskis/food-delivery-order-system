# Testēšanas rezultāti / Testing Results

## Laravel Feature testi / Laravel Feature Tests

### Pasūtījumu izveide / Order Creation

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 1 | Pārbaude, vai klients var izveidot pasūtījumu un vai attiecīgā produkta daudzums datubāzē samazinās.<br>Check whether a customer can create an order and whether the quantity of the corresponding product in the database decreases. |
| 2 | Pārbaude, vai nepietiekama preces daudzuma gadījumā pasūtījums netiek izveidots un preces atlikums noliktavā nesamazinās.<br>Check whether an order is not created and the product stock remains unchanged when there is insufficient stock. |
| 3 | Pārbaude, vai pasūtījums netiek izveidots, ja sistēmā nav neviena operatora.<br>Check whether an order is not created when no operator is available in the system. |
| 4 | Pārbaude, vai pasūtījumu nevar izveidot ar tukšu grozu.<br>Check whether an order cannot be created with an empty cart. |
| 5 | Pārbaude, vai pasūtījumu nevar izveidot, ja grozā ir prece, kas neeksistē datubāzē.<br>Check whether an order cannot be created when the cart contains a product that does not exist in the database. |
| 6 | Pārbaude, vai pasūtījumu nevar izveidot, ja preces daudzums ir nulle.<br>Check whether an order cannot be created when the product quantity is zero. |
| 7 | Pārbaude, vai pasūtījumu nevar izveidot, ja preces daudzums ir negatīvs.<br>Check whether an order cannot be created when the product quantity is negative. |
| 8 | Pārbaude, vai neautorizēts lietotājs nevar izveidot pasūtījumu.<br>Check whether an unauthenticated user cannot create an order. |

```text
PASS  Tests\Feature\OrderTest
✓ customer can create order
✓ order cannot be created when stock is insufficient
✓ order cannot be created when no operator is available
✓ order cannot be created with empty cart
✓ order cannot be created with nonexistent product
✓ order cannot be created with zero product quantity
✓ order cannot be created with negative product quantity
✓ guest cannot create order
Tests: 8 passed
```

### Lietotāju autentifikācija / User Authentication

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 9 | Pārbaude, vai lietotājs nevar pieslēgties sistēmai ar nepareizu paroli.<br>Check whether a user cannot log in with an incorrect password. |
| 10 | Pārbaude, vai lietotājs var veiksmīgi pieslēgties sistēmai, ievadot pareizu paroli.<br>Check whether a user can log in successfully with the correct password. |
| 11 | Pārbaude, vai jauns lietotājs var reģistrēties sistēmā un pēc reģistrācijas tiek automātiski autorizēts.<br>Check whether a new user can register and is automatically authenticated after registration. |
| 12 | Pārbaude, vai lietotājs nevar reģistrēties ar jau reģistrētu e-pasta adresi.<br>Check whether a user cannot register with an email address that is already registered. |

```text
PASS  Tests\Feature\LoginTest
✓ user cannot login with incorrect password
✓ user can login with correct password
Tests: 2 passed;
PASS  Tests\Feature\RegistrationTest
✓ user can register
✓ user cannot register with existing email
Tests: 2 passed;
```

**Reģistrācijas testi / Registration Tests**

```text
PASS  Tests\Feature\RegistrationTest
✓ user can register
✓ user cannot register with existing email
Tests: 2 passed
```

### Operatora testi / Operator Tests

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 13 | Pārbaude, vai operators var uzsākt viņam piešķirtā pasūtījuma apstrādi.<br>Check whether an operator can start processing an order assigned to them. |
| 14 | Pārbaude, vai operators nevar uzsākt cita operatora pasūtījuma apstrādi.<br>Check whether an operator cannot start processing an order assigned to another operator. |
| 15 | Pārbaude, vai operators nevar atkārtoti uzsākt pasūtījuma apstrādi, ja pasūtījums jau tiek apstrādāts.<br>Check whether an operator cannot start processing an order again if it is already being processed. |

```text
PASS  Tests\Feature\OperatorTest
✓ operator can start processing assigned order
✓ operator cannot start processing order assigned to another operator
✓ operator cannot start processing order that is not new
Tests: 3 passed
```

### Kurjera testi / Courier Tests

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 16 | Pārbaude, vai kurjers var uzsākt viņam piešķītā pasūtījuma piegādi.<br>Check whether a courier can start delivering an order assigned to them. |
| 17 | Pārbaude, vai kurjers nevar uzsākt cita kurjera pasūtījuma piegādi.<br>Check whether a courier cannot start delivering an order assigned to another courier. |
| 18 | Pārbaude, vai kurjers var veiksmīgi pabeigt piegādi un mainīt pasūtījuma statusu uz “delivered”.<br>Check whether a courier can complete a delivery successfully and change the order status to "delivered". |

```text
PASS  Tests\Feature\CourierTest
✓ courier can start delivery assigned order
✓ courier cannot start delivery assigned to another courier
✓ courier can complete delivery
Tests: 3 passed
```

### Pasūtījumu administrēšana / Order Administration

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 19 | Pārbaude, vai administrators var atcelt pasūtījumu un atgriezt pasūtītās preces noliktavā.<br>Check whether an administrator can cancel an order and return the ordered products to stock. |
| 20 | Pārbaude, vai administrators nevar mainīt jau piegādāta pasūtījuma statusu.<br>Check whether an administrator cannot change the status of an order that has already been delivered. |
| 21 | Pārbaude, vai administrators nevar atcelt jau atceltu pasūtījumu atkārtoti.<br>Check whether an administrator cannot cancel an order that has already been cancelled. |
| 22 | Pārbaude, vai administrators var atjaunot atceltu pasūtījumu un atkārtoti atskaitīt preces no noliktavas.<br>Check whether an administrator can restore a cancelled order and deduct the products from stock again. |
| 23 | Pārbaude, vai administrators nevar atjaunot atceltu pasūtījumu, ja noliktavā nav pietiekami daudz preču.<br>Check whether an administrator cannot restore a cancelled order when there is insufficient stock. |
| 24 | Pārbaude, vai administrators var palielināt preces daudzumu pasūtījumā un attiecīgi samazināt preces atlikumu noliktavā.<br>Check whether an administrator can increase the quantity of a product in an order and decrease the corresponding stock quantity. |
| 25 | Pārbaude, vai administrators nevar palielināt preces daudzumu pasūtījumā, ja noliktavā nav pietiekami daudz preču.<br>Check whether an administrator cannot increase the quantity of a product in an order when there is insufficient stock. |
| 26 | Pārbaude, vai administrators var samazināt preces daudzumu pasūtījumā un attiecīgi palielināt preces atlikumu noliktavā.<br>Check whether an administrator can decrease the quantity of a product in an order and return the corresponding quantity to stock. |
| 27 | Pārbaude, vai administrators nevar samazināt preces daudzumu pasūtījumā zem vienas vienības.<br>Check whether an administrator cannot decrease the quantity of a product in an order below one unit. |
| 28 | Pārbaude, vai administrators var dzēst preci no pasūtījuma un atgriezt tās daudzumu noliktavā.<br>Check whether an administrator can remove a product from an order and return its quantity to stock. |
| 29 | Pārbaude, vai administrators nevar dzēst pēdējo preci no pasūtījuma.<br>Check whether an administrator cannot remove the last product from an order. |
| 30 | Pārbaude, vai administrators nevar dzēst preci no jau piegādāta pasūtījuma.<br>Check whether an administrator cannot remove a product from an order that has already been delivered. |
| 31 | Pārbaude, vai administrators var pievienot preci pasūtījumam un attiecīgi samazināt preces atlikumu noliktavā.<br>Check whether an administrator can add a product to an order and decrease the corresponding stock quantity. |
| 32 | Pārbaude, vai administrators var palielināt pasūtījumā jau esošās preces daudzumu, neveidojot dublējošu ierakstu, un samazināt preces atlikumu noliktavā.<br>Check whether an administrator can increase the quantity of a product already in an order without creating a duplicate record, while decreasing the corresponding stock quantity. |
| 33 | Pārbaude, vai administrators nevar pievienot pasūtījumam preci, kas nav pieejama noliktavā.<br>Check whether an administrator cannot add a product to an order when it is out of stock. |
| 34 | Pārbaude, vai administrators nevar pievienot preci atceltam pasūtījumam.<br>Check whether an administrator cannot add a product to a cancelled order. |
| 35 | Pārbaude, vai administrators nevar pievienot preci jau piegādātam pasūtījumam.<br>Check whether an administrator cannot add a product to an order that has already been delivered. |
| 36 | Pārbaude, vai operators nevar mainīt pasūtījuma statusu, izmantojot administratora maršrutu.<br>Check whether an operator cannot change an order's status using an administrator route. |

```text
PASS  Tests\Feature\AdminOrderTest
✓ admin can cancel order and restore product stock
✓ admin cannot change delivered order status
✓ admin cannot cancel order twice
✓ admin can restore cancelled order and deduct product stock
✓ admin cannot restore cancelled order when stock is insufficient
✓ admin can increase ordered product quantity
✓ admin cannot increase ordered product quantity when stock is insufficient
✓ admin can decrease ordered product quantity
✓ admin cannot decrease ordered product quantity below one
✓ admin can delete product from order and restore stock
✓ admin cannot delete last product from order
✓ admin cannot delete product from delivered order
✓ admin can add product to order and reduce stock
✓ admin can add existing product to order
✓ admin cannot add product when stock is empty
✓ admin cannot add product to cancelled order
✓ admin cannot add product to delivered order
✓ operator cannot update order status through admin route
Tests: 18 passed
```

### Produktu administrēšana / Product Administration

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 37 | Pārbaude, vai administrators nevar dzēst preci, kas ir saistīta ar pasūtījumu.<br>Check whether an administrator cannot delete a product that is associated with an order. |
| 38 | Pārbaude, vai administrators var dzēst preci, kas nav saistīta ar pasūtījumu.<br>Check whether an administrator can delete a product that is not associated with an order. |
| 39 | Pārbaude, vai lietotājs bez administratora tiesībām nevar dzēst preci.<br>Check whether a user without administrator privileges cannot delete a product. |
| 40 | Pārbaude, vai neautorizēts lietotājs nevar dzēst preci.<br>Check whether an unauthenticated user cannot delete a product. |
| 41 | Pārbaude, vai administrators nevar dzēst neesošu preci.<br>Check whether an administrator cannot delete a product that does not exist. |
| 42 | Pārbaude, vai administrators var pievienot jaunu produktu.<br>Check whether an administrator can add a new product. |

```text
PASS  Tests\Feature\AdminProductTest
✓ admin cannot delete product used in order
✓ admin can delete product not used in order
✓ customer cannot delete product
✓ guest cannot delete product
✓ admin cannot delete nonexistent product
✓ admin can create product
Tests: 6 passed
```

### Lietotāju administrēšana / User Administration

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 43 | Pārbaude, vai administrators var apskatīt lietotāju sarakstu.<br>Check whether an administrator can view the list of users. |
| 44 | Pārbaude, vai administrators var rediģēt lietotāja datus.<br>Check whether an administrator can edit a user's details. |
| 45 | Pārbaude, vai klients nevar rediģēt cita lietotāja datus.<br>Check whether a customer cannot edit another user's details. |

```text
PASS  Tests\Feature\AdminUserTest
✓ admin can view users
✓ admin can update user
✓ customer cannot update user
Tests: 3 passed
```

## React testēšanas rezultāti / React Testing Results

### CartItem komponentes testi / CartItem Component Tests

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 1 | Daudzuma palielināšana<br>Increasing product quantity |
| 2 | Produkta noņemšana no groza<br>Removing a product from the cart |
| 3 | Daudzuma samazināšanas ierobežojums<br>Minimum quantity restriction |
| 4 | Produkta informācijas attēlošana<br>Displaying product information |

### Cart komponentes testi / Cart Component Tests

| Nr. | Testa apraksts / Test Description |
|---:|---|
| 5 | Tukša groza attēlošana<br>Displaying an empty cart |
| 6 | Kopējās cenas aprēķināšana<br>Calculating the total price |
| 7 | Daudzuma saglabāšana localStorage<br>Saving quantity to localStorage |
| 8 | Pasūtījuma noformēšanas saites attēlošana<br>Displaying the checkout link |

```text
PASS  tests/js/Cart.test.jsx
PASS  tests/js/CartItem.test.jsx
Test Suites: 2 passed, 2 total
Tests:       8 passed, 8 total
```

## Manuālā testēšana / Manual Testing

| Nr. | Testa apraksts / Test Description | Sagaidāmais rezultāts / Expected Result | Rezultāts / Status |
|---:|---|---|---|
| 1 | Lietotāja reģistrācija<br>User registration | Jauns lietotājs tiek veiksmīgi reģistrēts, ievadot derīgus datus.<br>A new user is registered successfully when valid data is provided. | ✅ |
| 2 | Reģistrācija ar nederīgiem datiem<br>Registration with invalid data | Sistēma parāda validācijas kļūdas un neizveido kontu.<br>The system displays validation errors and does not create an account. | ✅ |
| 3 | Lietotāja pieteikšanās<br>User login | Lietotājs veiksmīgi pieslēdzas sistēmai ar pareiziem piekļuves datiem.<br>The user logs in successfully with valid credentials. | ✅ |
| 4 | Pieteikšanās ar nepareiziem datiem<br>Login with incorrect credentials | Sistēma noraida pieteikšanās mēģinājumu un parāda kļūdas paziņojumu.<br>The system rejects the login attempt and displays an error message. | ✅ |
| 5 | Izrakstīšanās no sistēmas<br>User logout | Lietotājs tiek izrakstīts no sistēmas un nevar piekļūt aizsargātām lapām bez atkārtotas pieteikšanās.<br>The user is logged out and cannot access protected pages without logging in again. | ✅ |
| 6 | Produktu saraksta apskate<br>Viewing the product list | Sistēma parāda pieejamos produktus ar to nosaukumiem un cenām, kā arī attēliem, ja tie ir pievienoti.<br>The system displays available products with their names and prices, as well as images when provided. | ✅ |
| 7 | Produkta pievienošana grozam<br>Adding a product to the cart | Izvēlētais produkts tiek pievienots grozam ar norādīto daudzumu.<br>The selected product is added to the cart with the specified quantity. | ✅ |
| 8 | Produkta daudzuma palielināšana grozā<br>Increasing product quantity in the cart | Produkta daudzums palielinās, un groza kopējā cena tiek atjaunināta.<br>The product quantity increases, and the cart total is updated. | ✅ |
| 9 | Produkta daudzuma samazināšana grozā<br>Decreasing product quantity in the cart | Produkta daudzums samazinās, bet nav iespējams samazināt to zem minimālā atļautā daudzuma.<br>The product quantity decreases but cannot go below the minimum allowed quantity. | ✅ |
| 10 | Produkta noņemšana no groza<br>Removing a product from the cart | Izvēlētais produkts tiek noņemts no groza, un kopējā cena tiek pārrēķināta.<br>The selected product is removed from the cart, and the total price is recalculated. | ✅ |
| 11 | Tukša groza attēlošana<br>Displaying an empty cart | Ja grozā nav produktu, tiek parādīts atbilstošs paziņojums un nav pieejama pāreja uz pasūtījuma noformēšanu.<br>If the cart is empty, an appropriate message is displayed and checkout is unavailable. | ✅ |
| 12 | Groza datu saglabāšana pēc lapas pārlādes<br>Preserving cart data after page reload | Pēc lapas pārlādes grozā saglabājas iepriekš pievienotie produkti un to daudzumi.<br>After reloading the page, the previously added products and their quantities remain in the cart. | ✅ |
| 13 | Pasūtījuma noformēšana ar derīgiem datiem<br>Placing an order with valid details | Ievadot derīgus datus un noformējot pasūtījumu, sistēma izveido pasūtījumu un parāda atbilstošu apstiprinājumu vai pasūtījuma lapu.<br>When valid details are submitted, the system creates the order and displays an appropriate confirmation or order page. | ✅ |
| 14 | Pasūtījuma noformēšana ar nederīgiem datiem<br>Checkout with invalid details | Sistēma parāda validācijas kļūdas un neizveido pasūtījumu, kamēr nepieciešamie dati nav derīgi.<br>The system displays validation errors and does not create the order until the required details are valid. | ✅ |
| 15 | Pasūtījuma apskate<br>Viewing an order | Lietotājs var apskatīt savu pasūtījumu un ar to saistīto informāciju.<br>The user can view their order and its associated information. | ✅ |
| 16 | Pasūtījumu pārvaldība administratora panelī<br>Managing orders in the admin panel | Administrators var apskatīt un pārvaldīt pasūtījumus atbilstoši sistēmā pieejamajām funkcijām un piešķirtajām tiesībām.<br>The administrator can view and manage orders according to the available system features and assigned permissions. | ✅ |
| 17 | Produktu pārvaldība administratora panelī<br>Managing products in the admin panel | Administrators var pievienot un rediģēt produktus, kā arī veikt citas sistēmā paredzētās darbības.<br>The administrator can add and edit products and perform other supported actions. | ✅ |
| 18 | Lietotāju pārvaldība administratora panelī<br>Managing users in the admin panel | Administrators var apskatīt un pārvaldīt lietotājus atbilstoši piešķirtajām tiesībām.<br>The administrator can view and manage users according to their assigned permissions. | ✅ |
| 19 | Valodas pārslēgšana<br>Switching the language | Izvēloties citu pieejamo valodu, saskarnes teksti tiek parādīti izvēlētajā valodā.<br>When another available language is selected, the interface text is displayed in that language. | ✅ |
| 20 | Gaišā un tumšā motīva pārslēgšana<br>Switching between light and dark themes | Izvēlētais motīvs tiek piemērots saskarnei, un lapas elementi tiek attēloti atbilstoši izvēlētajam motīvam.<br>The selected theme is applied to the interface, and page elements are displayed according to that theme. | ✅ |

## Kopsavilkums / Summary

| Rādītājs / Metric | Rezultāts / Result |
|---|---:|
| Laravel Feature testi / Laravel Feature tests | 45 |
| React testi / React tests | 8 |
| Manuālie testi / Manual tests | 20 |
| Kopā / Total | 73 |

