### Download Laravel
Simply run 🏃‍♂ `composer install` 🏃‍♂. 

### Setup System
Use instructions below regarding `seeding` database, to prepare date. 

### Open Laravel and View sample requests
Run the system using `php artisan server --port=8881`. Once running, open following sample requests to go through examples.
* `http://localhost:8881/api/products?perpage=10&page=1` All Products / Page 1 / 10 Per Page
* `http://localhost:8881/api/products?perpage=10&page=1&sort=az` All Products / Page 1 / 10 Per Page / Sort Alphabetically in Ascending order
* `http://localhost:8881/api/products?perpage=15&page=2&sort=high` All products / Page 2 / 15 Per Page / Sort by Price in Descending order
* `http://localhost:8881/api/products/8414?perpage=2&page=1` Products by Section ID:8414 / Page 1 / 2 Per Page
* `http://localhost:8881/api/products/8408?perpage=2&page=1&sort=az` Products by Section ID:8408 / Page 1 / 2 Per Page / Sort Alphabetically in Ascending order
* `http://localhost:8881/api/products/hoodies?perpage=2&page=1&sort=high` Products by Section Title:hoodies / Page 1 / 2 Per Page / Sort by Price in Descending order

## Townsend Music Laravel coding test

Refactor the `sectionProducts()` method in `app/store_products.php` along with all of it's functionality into Laravel.

Two routes should be created `/products` and `/products/sectionname` that return all the products and then just the products for the selected section.

A ProductsController is in place to set these up, and they should return JSON of the same info passed by the original method

The models and relationships have already been created.

### Sample Data
You'll be able to install the application by running `php artisan migrate --seed`
