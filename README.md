# APIs for Social media network

in this project we created APIs for simple Social network application.

## Login API
Specific API for login users, and update status to be `active - online` user. and create unique JWT `_token`.

## Register API
Just store valid users and create profile and `Uuid` for each new user.

## Create Post API
if the request is valid, come from valide user and its token is not timed Out, then the post will be added.

## List All Posts API
not all users able to access this API. the user must be logged in and has valid `access token` 

## List My Own Posts API
As like as list all posts API. but this post return with my own posts only.

## Online users API.
maybe, in the future you will need to add `follow` functionality to your project. It's ready to extend..

# Design Pattern Used in this Project

## MVC
Model for each single entity, Controller to deal with models and APIs.

## Observer
Before saving data, updating it, we gonna to do some logic. such as:
after login you must be online. and after logout you go back offline.
Update token after specific actions.
and so on...

# Uuid instead of id.
instead of store user's and post's id like `1 or 12` we used Uuid for more security
id will be formed like that: `7f296792-31cd-56d0-8370-aa5a24280382`
For more details [link](https://github.com/webpatser/laravel-uuid)

# Return HTTP Status Code, used inside the project

* 200 : success (OK).
* 201 : Created.
* 204 : No Content.
* 404 : Not found.
* 500 : Internal Server Error

## How to use

* `composer install` to install Composer PHP Packages.
* `npm install` to install all required libraries listed in `packages.json`.
* `cp .env.example .env` and configure your database.
* `php artisan key:generate` to generate your app encryption key.
* `php artisan migrate` to generate database tables.
* `php artisan serve` to run server up on your local browser.


# Author
Copyright © Ashraf Amer