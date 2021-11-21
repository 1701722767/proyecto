#!/usr/bin/env bash

# ask for doung deploy
while true; do
  read -p "Do you want deploy API?(Y/N)" yn
    case $yn in
        [Yy]* ) echo -e "\e[0;32;40mDeploy API init.\e[m"; break;;
        [Nn]* ) exit;;
        * ) echo -e "\e[0;31mPlease answer yes or no.\e[m";;
    esac
done

# get the changes from repository
echo -e "\e[0;32;40mGetting changes from repository\e[m"
git pull origin main

# install new dependences
echo -e "\e[0;32;40mInstalling all dependencies\e[m"
composer install

echo -e "\e[0;32;40mDeploy API finished\e[m"