# VODUnion
VODUnion is a basic web application that allows users to view videos, create accounts, upload videos, make comments, and more.

## Installation
In order to run a local version of VODUnion you will need to install:
* PHP ^7.3 or ^8.0
* [Composer](https://getcomposer.org/download/)
* [Node.js](https://nodejs.org/en/download/)
* [A Laravel supported database of your choice](https://laravel.com/docs/8.x/database)

Next, you will need to clone the repository into a directory of your choice. From here, make a .env file and copy over the contents of the .env.example file.

In the .env file you will need to update DB connection, host, port, username, & password to match your local environment. You will also need to create a database with the name vodunion, or otherwise update the DB_DATABASE variable to match your local database name.

You will also need to input your [Cloudflare](https://www.cloudflare.com/) Account ID and Cloudflare API Token keys into the CLOUD_ACCOUNT & CLOUD_TOKEN variables respectively. This is required in order to enable video uploading, processing, and viewing. 

NOTE: Cloudflare’s Stream service is a paid service, and incurs a monthly cost of $5 USD per 1,000 minutes of video uploaded.

From the command line, CD into your working directory, then run `php artisan migrate` to generate the necessary tables & `php artisan db:seed --class=RolesSeeder` to seed the roles table.

You will also need to run `php artisan key:generate` to generate the Laravel application key.

Finally, run `composer install`, `npm install`, & `npm run dev` (or `npm run watch`), followed by `php artisan serve`. This will create a local server instance that you can access in your browser of choice from either 127.0.0.1:8000 or localhost:8000.

By default, VODUnion is set to a lenient uploading policy through the LENIENT_UPLOAD environment variable. This means that any account is able to upload and post videos. You can change this by setting the LENIENT_UPLOAD variable in your .env to false.

If you want to make use of admin functionality you will need to manually update your own account role to admin level, which will allow you to access localhost:8000/admin & localhost:8000/admin/upgradeKeys, the latter of which will allow you to give users a code to unlock uploading functionality when LENIENT_UPLOAD is set to false.
