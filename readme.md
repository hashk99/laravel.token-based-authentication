# Token based Authentication system for laravel 5.2 / 5.3

# FEATURES
 
login and get an auth token
get the user object by sending the token
login system with blcking for a defined time if entered wrong password. (easy to customize)

## clone the app and send a post request to url/api/v1/login with username and password as fields.
## to get the user using the token send a get request to url/api/v1/login with token as a header (auth_token)

still have to customize the returing error message text with details. currently it's return 'error' if something went wrong with the username and passwrd. 

check for auth service file in infrastructure folder to customize login block time durations.

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as authentication, routing, sessions, queueing, and caching.


Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb inversion of control container, expressive migration system, and tightly integrated unit testing support give you the tools you need to build any application with which you are tasked.

## Official Documentation

Documentation for the framework can be found on the [Laravel website](http://laravel.com/docs).
 
 