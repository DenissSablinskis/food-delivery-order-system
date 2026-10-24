[[LV]](#latviesu) [[EN]](#english)

<a id="latviesu"></a>

# Testēšanas rezultāti

## Laravel Feature testi

### Pasūtījumu izveide

1. Pārbaude, vai klients var izveidot pasūtījumu un vai attiecīgā produkta daudzums datubāzē samazinās.
2. Pārbaude, vai nepietiekama preces daudzuma gadījumā pasūtījums netiek izveidots un preces atlikums noliktavā nesamazinās.
3. Pārbaude, vai pasūtījums netiek izveidots, ja sistēmā nav neviena operatora.
4. Pārbaude, vai pasūtījumu nevar izveidot ar tukšu grozu.
5. Pārbaude, vai pasūtījumu nevar izveidot, ja grozā ir prece, kas neeksistē datubāzē.
6. Pārbaude, vai pasūtījumu nevar izveidot, ja preces daudzums ir nulle.
7. Pārbaude, vai pasūtījumu nevar izveidot, ja preces daudzums ir negatīvs.
8. Pārbaude, vai neautorizēts lietotājs nevar izveidot pasūtījumu.

```bash
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

### Lietotāju autentifikācija

9. Pārbaude, vai lietotājs nevar pieslēgties sistēmai ar nepareizu paroli.
10. Pārbaude, vai lietotājs var veiksmīgi pieslēgties sistēmai, ievadot pareizu paroli.
11. Pārbaude, vai jauns lietotājs var reģistrēties sistēmā un pēc reģistrācijas tiek automātiski autorizēts.
12. Pārbaude, vai lietotājs nevar reģistrēties ar jau reģistrētu e-pasta adresi.

```bash
  PASS  Tests\Feature\LoginTest
  ✓ user cannot login with incorrect password
  ✓ user can login with correct password
  Tests: 2 passed 

  PASS  Tests\Feature\RegistrationTest
  ✓ user can register
  ✓ user cannot register with existing email
  Tests: 2 passed 
```

### Operatora testi

13. Pārbaude, vai operators var uzsākt viņam piešķirtā pasūtījuma apstrādi.
14. Pārbaude, vai operators nevar uzsākt cita operatora pasūtījuma apstrādi.
15. Pārbaude, vai operators nevar atkārtoti uzsākt pasūtījuma apstrādi, ja pasūtījums jau tiek apstrādāts.

```bash
   PASS  Tests\Feature\OperatorTest
  ✓ operator can start processing assigned order
  ✓ operator cannot start processing order assigned to another operator
  ✓ operator cannot start processing order that is not new
  Tests: 3 passed
```

### Kurjera testi 

16. Pārbaude, vai kurjers var uzsākt viņam piešķītā pasūtījuma piegādi.
17. Pārbaude, vai kurjers nevar uzsākt cita kurjera pasūtījuma piegādi.
18. Pārbaude, vai kurjers var veiksmīgi pabeigt piegādi un mainīt pasūtījuma statusu uz “delivered”.

```bash
   PASS  Tests\Feature\CourierTest
  ✓ courier can start delivery assigned order
  ✓ courier cannot start delivery assigned to another courier
  ✓ courier can complete delivery
  Tests: 3 passed
```

### Pasūtījumu administrēšana

19. Pārbaude, vai administrators var atcelt pasūtījumu un atgriezt pasūtītās preces noliktavā.
20. Pārbaude, vai administrators nevar mainīt jau piegādāta pasūtījuma statusu.
21. Pārbaude, vai administrators nevar atcelt jau atceltu pasūtījumu atkārtoti.
22. Pārbaude, vai administrators var atjaunot atceltu pasūtījumu un atkārtoti atskaitīt preces no noliktavas.
23. Pārbaude, vai administrators nevar atjaunot atceltu pasūtījumu, ja noliktavā nav pietiekami daudz preču.
24. Pārbaude, vai administrators var palielināt preces daudzumu pasūtījumā un attiecīgi samazināt preces atlikumu noliktavā.
25. Pārbaude, vai administrators nevar palielināt preces daudzumu pasūtījumā, ja noliktavā nav pietiekami daudz preču.
26. Pārbaude, vai administrators var samazināt preces daudzumu pasūtījumā un attiecīgi palielināt preces atlikumu noliktavā.
27. Pārbaude, vai administrators nevar samazināt preces daudzumu pasūtījumā zem vienas vienības.
28. Pārbaude, vai administrators var dzēst preci no pasūtījuma un atgriezt tās daudzumu noliktavā.
29. Pārbaude, vai administrators nevar dzēst pēdējo preci no pasūtījuma.
30. Pārbaude, vai administrators nevar dzēst preci no jau piegādāta pasūtījuma.
31. Pārbaude, vai administrators var pievienot preci pasūtījumam un attiecīgi samazināt preces atlikumu noliktavā.
32. Pārbaude, vai administrators var palielināt pasūtījumā jau esošās preces daudzumu, neveidojot dublējošu ierakstu, un samazināt preces atlikumu noliktavā.
33. Pārbaude, vai administrators nevar pievienot pasūtījumam preci, kas nav pieejama noliktavā.
34. Pārbaude, vai administrators nevar pievienot preci atceltam pasūtījumam.
35. Pārbaude, vai administrators nevar pievienot preci jau piegādātam pasūtījumam.
36. Pārbaude, vai operators nevar mainīt pasūtījuma statusu, izmantojot administratora maršrutu.

```bash
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
### Produktu administrēšana

37. Pārbaude, vai administrators nevar dzēst preci, kas ir saistīta ar pasūtījumu.
38. Pārbaude, vai administrators var dzēst preci, kas nav saistīta ar pasūtījumu.
39. Pārbaude, vai lietotājs bez administratora tiesībām nevar dzēst preci.
40. Pārbaude, vai neautorizēts lietotājs nevar dzēst preci.
41. Pārbaude, vai administrators nevar dzēst neesošu preci.
42. Pārbaude, vai administrators var pievienot jaunu produktu.

```bash
   PASS  Tests\Feature\AdminProductTest
  ✓ admin cannot delete product used in order
  ✓ admin can delete product not used in order
  ✓ customer cannot delete product
  ✓ guest cannot delete product
  ✓ admin cannot delete nonexistent product
  ✓ admin can create product
  Tests: 6 passed
```

### Lietotāju administrēšana

43. Pārbaude, vai administrators var apskatīt lietotāju sarakstu.
44. Pārbaude, vai administrators var rediģēt lietotāja datus.
45. Pārbaude, vai klients nevar rediģēt cita lietotāja datus.

```bash
   PASS  Tests\Feature\AdminUserTest
  ✓ admin can view users
  ✓ admin can update user
  ✓ customer cannot update user
  Tests: 3 passed
```

## React testēšanas rezultāti

### CartItem komponentes testi

1. Daudzuma palielināšana

Pārbauda, vai, nospiežot pogu +, tiek izsaukta funkcija updateQuantity ar pareizu produkta ID un jauno daudzumu.

2. Produkta noņemšana no groza

Pārbauda, vai, nospiežot pogu ×, tiek izsaukta funkcija removeFromCart ar pareizu produkta ID.

3. Daudzuma samazināšanas ierobežojums

Pārbauda, vai produkta daudzumu nevar samazināt zem 1 un vai, nospiežot pogu −, funkcija updateQuantity netiek izsaukta, ja daudzums jau ir 1.

4. Produkta informācijas attēlošana

Pārbauda, vai groza elementā tiek attēlots pareizs produkta nosaukums, cena un daudzums.

### Cart komponentes testi

5. Tukša groza attēlošana

Pārbauda, vai tukšā grozā tiek attēlots atbilstošs paziņojums un nav redzama poga saites veidā pārejai uz pasūtījuma noformēšanu.

6. Kopējās cenas aprēķināšana

Pārbauda, vai kopējā cena tiek aprēķināta pareizi, ņemot vērā katra produkta cenu un daudzumu.

7. Daudzuma saglabāšana localStorage

Pārbauda, vai pēc produkta daudzuma palielināšanas jaunais daudzums tiek saglabāts pārlūkprogrammas localStorage.

8. Pasūtījuma noformēšanas saites attēlošana

Pārbauda, vai grozā ar vismaz vienu produktu tiek attēlota saite uz /checkout.

```bash
PASS  tests/js/Cart.test.jsx
PASS  tests/js/CartItem.test.jsx
Test Suites: 2 passed, 2 total
Tests:       8 passed, 8 total
```

<a id="english"></a>

# Testing Results

## Laravel Feature Tests

### Order Creation

1. Check whether a customer can create an order and whether the quantity of the corresponding product in the database decreases.
2. Check whether an order is not created and the product stock remains unchanged when there is insufficient stock.
3. Check whether an order is not created when no operator is available in the system.
4. Check whether an order cannot be created with an empty cart.
5. Check whether an order cannot be created when the cart contains a product that does not exist in the database.
6. Check whether an order cannot be created when the product quantity is zero.
7. Check whether an order cannot be created when the product quantity is negative.
8. Check whether an unauthenticated user cannot create an order.

```bash
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

### User Authentication

9. Check whether a user cannot log in with an incorrect password.
10. Check whether a user can log in successfully with the correct password.
11. Check whether a new user can register and is automatically authenticated after registration.
12. Check whether a user cannot register with an email address that is already registered.

```bash
PASS  Tests\Feature\LoginTest
✓ user cannot login with incorrect password
✓ user can login with correct password
Tests: 2 passed

PASS  Tests\Feature\RegistrationTest
✓ user can register
✓ user cannot register with existing email
Tests: 2 passed
```

### Operator Tests

13. Check whether an operator can start processing an order assigned to them.
14. Check whether an operator cannot start processing an order assigned to another operator.
15. Check whether an operator cannot start processing an order again if it is already being processed.

```bash
PASS  Tests\Feature\OperatorTest
✓ operator can start processing assigned order
✓ operator cannot start processing order assigned to another operator
✓ operator cannot start processing order that is not new
Tests: 3 passed
```

### Courier Tests

16. Check whether a courier can start delivering an order assigned to them.
17. Check whether a courier cannot start delivering an order assigned to another courier.
18. Check whether a courier can complete a delivery successfully and change the order status to "delivered".

```bash
PASS  Tests\Feature\CourierTest
✓ courier can start delivery assigned order
✓ courier cannot start delivery assigned to another courier
✓ courier can complete delivery
Tests: 3 passed
```

### Order Administration

19. Check whether an administrator can cancel an order and return the ordered products to stock.
20. Check whether an administrator cannot change the status of an order that has already been delivered.
21. Check whether an administrator cannot cancel an order that has already been cancelled.
22. Check whether an administrator can restore a cancelled order and deduct the products from stock again.
23. Check whether an administrator cannot restore a cancelled order when there is insufficient stock.
24. Check whether an administrator can increase the quantity of a product in an order and decrease the corresponding stock quantity.
25. Check whether an administrator cannot increase the quantity of a product in an order when there is insufficient stock.
26. Check whether an administrator can decrease the quantity of a product in an order and return the corresponding quantity to stock.
27. Check whether an administrator cannot decrease the quantity of a product in an order below one unit.
28. Check whether an administrator can remove a product from an order and return its quantity to stock.
29. Check whether an administrator cannot remove the last product from an order.
30. Check whether an administrator cannot remove a product from an order that has already been delivered.
31. Check whether an administrator can add a product to an order and decrease the corresponding stock quantity.
32. Check whether an administrator can increase the quantity of a product already in an order without creating a duplicate record, while decreasing the corresponding stock quantity.
33. Check whether an administrator cannot add a product to an order when it is out of stock.
34. Check whether an administrator cannot add a product to a cancelled order.
35. Check whether an administrator cannot add a product to an order that has already been delivered.
36. Check whether an operator cannot change an order's status using an administrator route.

```bash
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

### Product Administration

37. Check whether an administrator cannot delete a product that is associated with an order.
38. Check whether an administrator can delete a product that is not associated with an order.
39. Check whether a user without administrator privileges cannot delete a product.
40. Check whether an unauthenticated user cannot delete a product.
41. Check whether an administrator cannot delete a product that does not exist.
42. Check whether an administrator can add a new product.

```bash
PASS  Tests\Feature\AdminProductTest
✓ admin cannot delete product used in order
✓ admin can delete product not used in order
✓ customer cannot delete product
✓ guest cannot delete product
✓ admin cannot delete nonexistent product
✓ admin can create product
Tests: 6 passed
```

### User Administration

43. Check whether an administrator can view the list of users.
44. Check whether an administrator can edit a user's details.
45. Check whether a customer cannot edit another user's details.

```bash
PASS  Tests\Feature\AdminUserTest
✓ admin can view users
✓ admin can update user
✓ customer cannot update user
Tests: 3 passed
```

## React Testing Results

### CartItem component tests

1. Increasing product quantity

Verifies that clicking the + button calls the updateQuantity function with the correct product ID and updated quantity.

2. Removing a product from the cart

Verifies that clicking the × button calls the removeFromCart function with the correct product ID.

3. Minimum quantity restriction

Verifies that the product quantity cannot be decreased below 1 and that the updateQuantity function is not called when the quantity is already 1 and the − button is clicked.

4. Displaying product information

Verifies that the cart item displays the correct product name, price, and quantity.

### Cart component tests

5. Displaying an empty cart

Verifies that an appropriate message is displayed when the cart is empty and that the checkout link is not visible.

6. Calculating the total price

Verifies that the total price is calculated correctly based on each product's price and quantity.

7. Saving quantity to localStorage

Verifies that the updated product quantity is saved to the browser's localStorage after clicking the + button.

8. Displaying the checkout link

Verifies that the link to /checkout is displayed when the cart contains at least one product.

```bash
PASS  tests/js/Cart.test.jsx
PASS  tests/js/CartItem.test.jsx
Test Suites: 2 passed, 2 total
Tests:       8 passed, 8 total
```


