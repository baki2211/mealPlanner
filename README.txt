### Meal Planner Project.

### Web application built with Symfony 7.1, Php, Js
### You can register, log in ad access you personal week Planner. Chose from a variety of recipes uploaded by all the subscribed users. 

### Get the Repository:
### Open new VSCode windove and select:
Clone git Repository 
### Insert the link to the repository:
https://github.com/baki2211/mealPlanner.git

### In the folder project:
### open terminal and launch: 
composer install

### go to phpmyadmin
### import the database from the project folder
### Edit .env file. 
Go to line 27 and replace the root:root with your credential

### Before you start doing anything else: //Need to do this only the first time you clone the repository
### Create a branch to work on 
### At the bottom corner of VSCode click on "master" and from the dropdown select:
Create new branch from:...
Select the master/origin

### Ensure Local Repository is Up-to-Date:

git checkout master
git pull origin master

### Update  Branch with Latest Changes from Master:

git checkout branch-name
git merge master

### Each one should work on one different twig file to avoid conflict.

### When finished and commit changes:

### Push Changes to the Remote  Branch:

git push origin branch-name

### Create a pull request on GitHub, to merge the feature branch into the master branch. I will approve it and deal with conflict if any