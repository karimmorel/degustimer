# Welcome to Degustimer!

Hi Degusta Box! As requested, I have been working on your PHP developer exercise : **The Time Tracker.**
I hope you will like it, I tried to give the best of me in the allotted time.

# GitHub

I uploaded the project in a <a target="_blank" href="https://github.com/karimmorel/degustimer">GitHub repository</a>, as requested.
You can clone it directly on a local repository.
>**cd myfavoriterepository**<br/>
>**git clone https://github.com/karimmorel/degustimer.git**<br/>

Because I am more used to Gitlab, I will also upload it on my <a target="_blank" href="https://gitlab.com/karimmorelGitlab">Gitlab Account.</a>

## Docker

For this project, I set up a docker image that helps you to deploy the application efficiently.
You can find the repository of this image on <a target="_blank" href="https://hub.docker.com/repository/docker/karimmorel/degustimer">DockerHub.</a>
There is a docker-compose file in the root of the project. So to start the container, a "docker-compose up" in the repository containing the project locally should work. 
(He will need the port 80, if you want it to use another port, you can edit the docker-compose.yml)
> **docker pull karimmorel/degustimer**<br/>
> **docker-compose up**<br/>

When the container is completely initialized, the application should be accessible on <a target="_blank" href="http://localhost:80">localhost</a>, on the port 80.
But the database and composer are not set, I need you to use doctrine to migrate the database.
So you have to connect to your container :
> **docker exec -ti <name_of_the_container> bin/bash**<br/>

The container's name should be something like : degustabox_degustamer_1

And to use doctrine :
> **cd /app**<br/>
> **composer install**<br/>
> **php bin/console doctrine:migrations:migrate**

Now the application should be accessible : <a target="_blank" href="http://localhost:80">localhost</a>


## The exercise

With this project, I tried to do the exercise you sent me as seriously as possible.
There is may be things that can vary with what you were expecting, I did the application as I understood it.

For example, in my application, there is only one task running at a time (like in Toggl). When you add a new task and there is already one running, the previous stops automatically.

I tried to make the application working on each navigator, on phone or on a desktop, with or without javascript (of course, the timer wont work without javascript).

## Stack

For this exercise, I have been using Symfony 4.4.10 with mysql.
I tried to make the application as SOLID as possible, while implementing as much functionalities as I could.
I left the dev environment, in case you want to use the debugbar.

## PhpMyAdmin
I don't know if you will need it, but <a target="_blank" href="http://localhost/phpmyadmin">PhpMyAdmin</a> is accessible :
User : degustadmin
Password : degustadmin

## One step further

I added some commands accessible in the container :
> **docker exec -ti <name_of_the_container> bin/bash**<br/>
> **cd /app**<br/>
> **php bin/console task:start "Name of the task"**<br/>
or<br/>
> **php bin/console task:stop "Name of the task"**<br/>
or<br/>
> **php bin/console task:list**<br/>

As I specified it in the comments of the command script, I added to the task:stop an argument "Name of the task" because it was requested in the exercise, but I don't know if it is necessary because there is only one task running at a time.
So here the argument is used to be sure the user wants to stop the running task, if he enters a wrong task name, the running task continues.

## Thank you for the interest you are giving to my application

I hope you will like my application, I tried to follow the exercise as much as possible.
If there is anything wrong or different with what you were expecting, don't hesitate to ask me for more informations.

Have a great day !

Karim.