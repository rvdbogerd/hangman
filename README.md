# SIM Hangman API #

The goal of this api is quite clear, I've implemented the resources and added test coverage for all code I've created.

For convenience I've added a basic vagrant setup to run the app locally on a VM, it wasn't part of the assignment,
but hey, now my fellow developers will get it for free. That is, if this was a real project :)

## Project setup ##

Check out the repository
```
git clone git@github.com:rvdbogerd/hangman.git
```

Run composer install
```
composer install
```

## Webserver option 1: Vagrant ##
Install vagrant
Run vagrant up
```
vagrant up --provision
```

Point your local hosts file to the vm by adding the hangman url
```
192.168.33.60   hangman.local.dev
192.168.33.60   www.hangman.local.dev
```

## Webserver option 2 ##
Just set up a local database, add your credentials to parameters.yml and boot your favourite webserver (tip: you could use the internal php server by running the `app/console server:run` command.

## DB Migrations ##

run database migrations
```
$ php app/console doctrine:database:create
$ php app/console doctrine:migrations:migrate
```

## Start testing ##
Now you can start testing the API by pointing your testing tool (e.g. Postman) to hangman.local.dev/games

### Create new game: ###
* URI: hangman.local.dev/games
* Method: POST 
* Response content: game data in json format
* Response headers: Location header contains the new resource location.

### Start guessing characters on existing game: ###
* URI: hangman.local.dev/games/{id}
* Method: PUT 
* Parameters: 'character' => single alpha character
* Request Headers: should contain Content-Type header, can be one of `application/json` or `application/x-www-form-urlencoded`
* Response content: game data in json format

## TODO ##

Although I tried my best with delivering clean, well-tested code, within the amount of time available, there's still some stuff I would like to improve:

* Add a Listener which converts all exceptions to valid json output, it's quite annoying if you hit the same character twice and you get this blob of html with error output from the debugger. Would be nice to just have something like an error property in the json response object. If I had more time, I'd add it!
* Add functional controller tests. I experimented a bit with the Controller, by trying to let it have no symfony "magic" at all. Therefore I removed the container dependency and the baseclass extend, and tried to fully unit test the controller actions. Altough I succeeded in doing so and it made the controller more decoupled I'd prefer to add webtest cases instead of unit tests for the controller, because my unit tests seem to be a bit hard to maintain.




# ASSIGNMENT AS PROVIDED #

In this assignment we ask you to implement a minimal version of a hangman API using the following resources below:

## Resources ##

**/games (POST)**

Start a new game

- A list of words can be found in the MySQL database. At the start of the game a random word should be picked from this list.

**/games/[:id] (PUT)**

Guess a started game

- Guessing a correct letter doesn’t decrement the amount of tries left

- Only valid characters are a-z

## Response ##

Every response should contain the following fields:

*word*: representation of the word that is being guessed. Should contain dots for letters that have not been guessed yet (e.g. aw.so..)

*tries_left*: the number of tries left to guess the word (starts at 11)

*status*: current status of the game (busy|fail|success)
