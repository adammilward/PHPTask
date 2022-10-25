# Weather API

### requires php 8.1

## Installation

To install, run the flowing commands

 - git clone https://github.com/adammilward/PHPTask.git
 - cd PHPTask
 - git checkout phpTask
 - composer install
 - cp .env.example .env
 - - set your database credentials in .env
 - php artisan migrate
 - - if prompted, answer "yes" to create database
 - php artisan jwt:secret
 - npm install
 - npm run build
 - php artisan serve

## User Authentication by JWT

The weather api requires a user to be logged in and provide a JWT to access any endpoint.
User authentication was added from this tutorial:
https://blog.logrocket.com/implementing-jwt-authentication-laravel-9/#test-application

To create a JWT secret run the command ```php artisan jwt:secret```

## The two end points below allow a user to register and login.

### Register ```api/register```

Send a post request to: ```api/register```
containing the following form-data parameters
 - name
 - email
 - password

Returns a JWT authorisation token, type: bearer

### Login ```api/login```

Send a post request to ```api/login```
containing the following form-data parameters
 - email
 - password

Returns a JWT authorisation token, type: bearer

The JWT authorisation token can be sent, with get requests to the weather api. In postman go to the authorization tab, 
select 
type bearer and paste the token into the text box.

## The below endpoints require a JWT for access.

### Logout ```api/logout```
Send a post request to `````api/logout`````
The user matching the JWT is logged out.

### Refresh ```api/refresh```
Send a post request to ```api/refresh```
A new JWT is issued and the old one (provided in the Authorization header) is invalidated.


## Using the API
Accessing a weather forecast is expected to be a two step process (not including registering and logging in).

If you already know a city ID you can go straight to step 2.

The endponts below all take get requests.

### Step 1 - Finding a city

- ### ```weather/allCities```
  Returns all available cities.
  This could be used to populate a search box.
- ### ```weather/cities/match/{nameString}```
  Will return cities matching the value "nameString".
  This could be used to provide a shortlist of cities IE. for a search box.
- ### ```weather/city/lat/{latitude}/lon/{longitude}```
  Returns the city closest to the coordinates provided in degrees.

### Step 2 - request forecast by city ID
- ### ```weather/forecast/cityId/{id}```
  Returns the weather forecast for the city matching ID, along with the city's data
  Returns error response if no city is found matching ID

### Other Endpoints
- ### ```weather/city/id/{id}```
  Returns the city data for a city.

## Limitations and Considerations

### Storing the cities data in a local file.
I recognise that retrieving the data from a file, as a large array, before searching is  slower than database
lookups.
However for the purposes of the exercise it is quick and simple and does not require copying the database when
installing on a new machine for testing.

I also understand that files within the storage/public folder should not be committed to the repository, but this
seemed
simplest for a proof of concept.

It would be better to do away with all locally stored data, and only use the Open Weather api for
retrieving all data. This would prevent the possibility of the local data being out of sync with Open Weather.
