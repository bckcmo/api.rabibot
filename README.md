# api.rabibot

The backend for Rabibot. Ceated with Laravel.

## To Run Locally

### Requirments:
* Docker Desktop

Clone the repo. CD into the project root and run docker-compose up. 
You might need to generate an app key and configure the database.
The docker network contains a mysql instance, do the db connection should be set to mysql.  
For all the services to work, you'll need to add environment variables for

* GEOCODER_URI="https://maps.googleapis.com/maps/api/geocode/json"  
* GEOCODER_KEY={INSERT YOUR KEY}

The env vars for mail may also need to be configured to work with your chosen driver.

## Routes

#### /api/register

This route accepts post requests. Ex.
```json
{
	"email": "brendan@example.com",
	"password": "password",
	"password_confirmation": "password"
}
```

Example response
```json
{
    "success": true,
    "data": {
        "access_token": "TOKEN_WILL_BE_HERE",
        "name": null
    },
    "message": "User registration success"
}
```

#### /api/login

This route accepts post requests. Ex.
```json
{
	"email": "brendan@example.com",
	"password": "password"
}
```

Example response

```json
{
    "success": true,
    "data": {
        "user": {
            "id": 5,
            "email": "brendan@example.com",
            "email_verified_at": null,
            "created_at": "2019-06-08 16:05:32",
            "updated_at": "2019-06-08 16:05:32"
        },
        "access_token": "TOKEN_WILL_BE_HERE"
    },
    "message": "User registration success"
}
```
#### /api/me

#### /api/screens
This route accepts post, get, and delete requests. Get requests to /api/screens retrieve all screens for the authenticated user.
Get request can be sent to /api/screens/{id} to retrieve a single resource.
Delete requests go to /api/screens/{id}.

Example post request to /api/screens
```json
{
	"address": "1600 Pennsylvania Avenue NW",
	"city": "Washington",
	"state" : "DC", 
	"zip": "20006"
}
```
Example post response
```json
{
    "success": true,
    "data": {
        "address": "1600 Pennsylvania Avenue NW",
        "city": "Washington",
        "state": "DC",
        "zip": "20006",
        "ej_result": false,
        "user_id": 5,
        "updated_at": "2019-06-12 20:50:00",
        "created_at": "2019-06-12 20:50:00",
        "id": 52
    },
    "message": "EJ results"
}
```

Example get response
```json
{
    "success": true,
    "data": {
        "screens": [
            {
                "id": 6,
                "address": "6675 W 119th St",
                "city": "Overland Park",
                "state": "Kansas",
                "zip": "66209",
                "one_mile_report": "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx?geometry={\"x\":-94.6621744,\"y\":38.9123661,\"spatialReference\":{\"wkid\":4326}}&distance=1&unit=9035&areatype=blockgroup&areaid=&f=report",
                "blockgroup_report": "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx?geometry=&distance=&unit=9035&areatype=blockgroup&areaid=200910532011&f=report",
                "ej_result": 0,
                "user_id": 5,
                "created_at": "2019-06-10 18:44:35",
                "updated_at": "2019-06-10 18:44:35"
            },
            {
                "id": 7,
                "address": "6675 W 119th St",
                "city": "Overland Park",
                "state": "Kansas",
                "zip": "66209",
                "one_mile_report": "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx?geometry={\"x\":-94.6621744,\"y\":38.9123661,\"spatialReference\":{\"wkid\":4326}}&distance=1&unit=9035&areatype=blockgroup&areaid=&f=report",
                "blockgroup_report": "https://ejscreen.epa.gov/mapper/EJSCREEN_report.aspx?geometry=&distance=&unit=9035&areatype=blockgroup&areaid=200910532011&f=report",
                "ej_result": 0,
                "user_id": 5,
                "created_at": "2019-06-10 18:44:53",
                "updated_at": "2019-06-10 18:44:53"
            },
        ]
    },
    "message": "2 screens found"
}
```

#### /api/screens/email/{id}
This route accepts post requests. The screen with the id in the route is emailed to the authenticated user.
