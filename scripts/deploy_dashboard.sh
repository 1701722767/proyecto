#!/usr/bin/env bash

# ask for doing deploy
while true; do
  read -p  "Do you want deploy dashboard?(Y/N)" yn
    case $yn in
        [Yy]* ) echo -e "\e[0;32;40mDeploy dashboard init.\e[m"; break;;
        [Nn]* ) exit;;
        * ) echo -e "\e[0;31mPlease answer Y or N.\e[m";;
    esac
done

cd ../../public_html
# verify that dist.zip exist
if ! [ -f dist.zip ];
then
  echo -e "\e[0;31mSorry dist.zip file don't exist.\e[m"
  exit 1
fi

# move old files to backup folder
echo -e "\e[0;32;40mDoing backup...\e[m"
rm -r backup
mkdir backup
mv css backup/css
mv fonts backup/fonts
mv img backup/img
mv js backup/js
mv favicon.ico backup/favicon.ico
mv index.html backup/index.html

# unzip the dist.zip file
echo -e "\e[0;32;40mUnzipping files...\e[m"
unzip dist.zip

# funtion for do rollback of deploy
rollback(){
  echo -e "\e[0;32;40mInit dashboard deploy rollback\e[m"

  echo -e "\e[0;32;40mDeleting new files\e[m"
  rm -r css
  rm -r fonts
  rm -r img
  rm -r js
  rm favicon.ico
  rm index.html

  echo -e "\e[0;32;40mRestore old files\e[m"
  mv backup/css css
  mv backup/fonts fonts
  mv backup/img img
  mv backup/js js
  mv backup/favicon.ico favicon.ico
  mv backup/index.html index.html

  echo -e "\e[0;32;40mDeploy dashboard rollback finilized.\e[m"
  return
}

# funtion for delete deploy files
delete_backup(){
  echo -e "\e[0;32;40mDeploy dashboard finilized.\e[m"

  # delete deploy file dist.zip
  rm dist.zip

  # delete the backup
  rm -r backup

 echo -e "\e[0;32;40mAll files of deploy deleted.\e[m"
  return
}

# ask if all is ok and delete the backup or do rollback
while true; do
  read -p "Everything went well? ?(Y/N)" yn
    case $yn in
        [Yy]* ) delete_backup; exit;;
        [Nn]* ) rollback; exit;;
        * ) echo -e "\e[0;31mPlease answer Y or N.\e[m";;
    esac
done


