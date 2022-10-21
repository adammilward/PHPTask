# Weather API

### requires php 8.1

## Using the API
Accessing a weather forecast is expected to be a two step process (unless you already know the cityId)

### Step One - find a city

 - ### /weather/allCities
    Returns all available cities.
    This could be used to populate a search box.
 - ### /weather/cities/match/{nameString}
    will return cities matching the value "nameString".
    This could be used providing a shortlist cities, to a search box.
 - ### /weather/city/lat/{lat}/lon/{lon}
    Returns the city closest to the coordinates provided in Degrees.

### Step Two - request forecast by city ID
 - ### /weather/forecast/cityId/{id}
    Returns the weather forecast for the city matching ID, along with the city's data
    Returns error response if no city is found matching ID  
    
### Other Endpoints
 - ### /weather/city/id/{id}
    Returns the city data for a city.

## Limitations

### Database
I could not get the mysql database working on my development machine.
In the interest of quickly producing a working demo I have used file storage, which is expected to be slower than a 
database. Alternatively more use of OpenWeather api could make local storage unnecessary.
I felt that using local storage would provide more opportunities to demonstrate coding.

Not having a database also makes user Authentication impossible.
I will see if I can produce a mock demonstration of Authentication with a JWT.
