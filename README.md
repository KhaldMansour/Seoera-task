
### Requirements

* **SERVER**: Apache 2 or NGINX.
* **RAM**: 3 GB or higher.
* **PHP**: 7.3 or higher.
* **For MySQL users**: 5.7.23 or higher.
* **Node**: 8.11.3 LTS or higher.
* **Composer**: 1.6.5 or higher.

### Installation and Configuration

** Install Seoer from your console.**

##### Execute these commands below, in order

1. Create a database and set its name in your .env file under (DB_DATABASE)
2. Create a Pusher account and set your credentials as it's explained in the website
> *https://dashboard.pusher.com/apps/1338462/getting_started*

3. In your terminal run these commands
~~~
 composer install
~~~
~~~
 npm i
~~~
~~~
php artisan migrate
~~~
~~~
php artisan db:seed
~~~
~~~
php artisan vendor:publish
~~~
Type 0 then press enter
~~~
php artisan key:generate
~~~
~~~
php artisan optimize
~~~
~~~
php artisan serve
~~~

you can test by those accounts 

User :
    email : test@user.com
    password : 123456789

Admin :
    email : test@admin.com
    password : 123456789
