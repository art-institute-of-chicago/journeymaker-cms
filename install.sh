#!/bin/bash

# Requires:
#  * drush
#  * MySQL 5.7 or greater

# Color markup
R=`tput setaf 1`
G=`tput setaf 2`
Y=`tput setaf 3`
C=`tput setaf 6`
W=`tput setaf 7`

echo "${C}Welcome to the JourneyMaker CMS installer.${W}"

function usage() {
    echo "${W}Usage: ./build.sh [/path/to/journeymaker-cms]${W}"
    echo ""
}


# Test for arguments
# You can specify a folder to the path of your JourneyMaker CMS project,
# otherwise we'll assume it's in your current directory.
DIR_ROOT=$1
if [ ! $DIR_ROOT ] || [ ! -d $DIR_ROOT ]; then
    DIR_ROOT="."
fi


# Test for .editorconfig - to ensure this is indeed a JourneyMaker project directory
if [ ! -f $DIR_ROOT/.editorconfig ]; then
    echo "${R}Error: This doesn't look like a journeymaker-cms project folder (no .editorconfig){$W}"
    echo $DIR_ROOT/.editorconfig
    echo
    usage
    exit
fi


# Test for trailing slash - should not be present
dpath=$(echo $DIR_ROOT | awk '{print substr($0,length,1)}')
if [ $dpath = '/' ]; then
    echo "${R}Error: Please don't include a slash '/' at the end of the path.${W}"
    echo
    usage
    exit
fi

# Clear the install log
echo "${G}Clearing old install log...${W}"
rm -f $DIR_ROOT/install.log
touch $DIR_ROOT/install.log

echo "${G}Downloading Drupal...${W}"
curl -s -k -L -o $DIR_ROOT/drupal-7.53.tar.gz https://ftp.drupal.org/files/projects/drupal-7.53.tar.gz
tar -xz -C $DIR_ROOT -f $DIR_ROOT/drupal-7.53.tar.gz
rm $DIR_ROOT/drupal-7.53.tar.gz
find $DIR_ROOT/drupal-7.53 -maxdepth 1 -not -path "$DIR_ROOT/drupal-7.53" -not -path "$DIR_ROOT/drupal-7.53/sites" -not -path "$DIR_ROOT/drupal-7.53/.gitignore" | xargs -I {} mv {} $DIR_ROOT/
chmod u+w $DIR_ROOT/sites/default
mv $DIR_ROOT/drupal-7.53/sites/default/default.settings.php $DIR_ROOT/sites/default/settings.php
rm -rf $DIR_ROOT/drupal-7.53
mkdir $DIR_ROOT/sites/default/files
mkdir $DIR_ROOT/sites/default/files/json
chmod -R a+wx $DIR_ROOT/sites/default/files
cp -r $DIR_ROOT/.sites-default-files/* $DIR_ROOT/sites/default/files/

echo "${G}Setting up Drupal install...${W}"


# Get database info
read -p "${W}Which database are you using [mysql]: ${W}" driver
driver=${driver:-mysql}

read -p "${W}What is the database host? [127.0.0.1]: ${W}" host
host=${host:-127.0.0.1}

read -p "${W}What is database name? (Will be created it if it does not exist) [drupal]: ${W}" database
database=${database:-drupal}

read -p "${W}What is the database user? (Will be created it if it does not exist) [$database]: ${W}" username
username=${username:-$database}

read -s -p "${W}What is the user password? [$database]: ${W}" password
password=${password:-$database}
echo

read -s -p "${W}What is your database ROOT password? (If not entered, database and user will not be created): ${W}" rootpassword
echo


# Create database if it doesn't exist
if [ $driver = 'mysql' ]; then
    if [ $rootpassword ]; then
	mysql -s -uroot -p${rootpassword} -e "CREATE DATABASE IF NOT EXISTS $database"
    fi
else
    echo "${Y}Warning: not creating database if it doesn't already exists. (install.sh not tested with PostgreSQL)${W}"
fi


# Create username if it doesn't exist
if [ $driver = 'mysql' ]; then
    if [ $rootpassword ]; then
	mysql -s -uroot -p${rootpassword} -e "CREATE USER IF NOT EXISTS '${username}'@'${host}' IDENTIFIED BY '${password}';"
	mysql -s -uroot -p${rootpassword} -e "GRANT ALL PRIVILEGES ON ${database}.* TO '${username}'@'${host}';"
    fi
else
    echo "${Y}Warning: not creating user if it doesn't already exists. (not tested with PostgreSQL)${W}"
fi


# Add it to settings.php
cat <<EOT>> $DIR_ROOT/sites/default/settings.php

\$databases['default']['default'] = array(
  'driver' => '$driver',
  'database' => '$database',
  'username' => '$username',
  'password' => '$password',
  'host' => php_sapi_name() == 'cli' ? '127.0.0.1' : '$host',
  'prefix' => '',
);
EOT


# Run Drupal installation
echo "${G}Installing Drupal...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT site-install --db-url=mysql://$username:$password@$host/$database --yes >> install.log 2>&1


# Enable/disable modules
echo "${G}Enabling modules...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT pm-disable --yes overlay >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT pm-enable --yes \
      tracker \
      save_edit \
      aic_api \
      aic_artwork_list \
      aic_csv \
      aic_json \
      aic_base_install \
      ctools_ajax_sample \
      ctools_custom_content \
      page_manager \
      field_group \
      references_dialog \
      cron_key \
      libraries \
      require_login \
      chosen \
      views_bulk_operations \
      views_ui \
      field_collection \
      node_reference \
      references \
      features \
      node_export_features >> install.log 2>&1

./vendor/bin/drush --root=$DIR_ROOT cache-clear all >> install.log 2>&1


# Adding/removing permissions
echo "${G}Adding permissions...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'authenticated user' "use ctools import,edit own comments,access contextual links,access content overview,use save and edit,administer save and edit,administer search,search content,use advanced search,administer shortcuts,customize shortcut links,switch shortcut sets,access administration pages,edit terms in 1,delete terms in 1,change own username,cancel account,view own unpublished content,view revisions,revert revisions,delete revisions,access toolbar" >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'anonymous user' 'access comments' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'Editor' 'administer nodes' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-add-perm 'Publisher' "administer nodes,publish json data" >> install.log 2>&1

./vendor/bin/drush --root=$DIR_ROOT role-remove-perm 'anonymous user' 'use text format filtered_html' >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT role-remove-perm 'authenticated user' "use text format filtered_html" >> install.log 2>&1


# Set theme
echo "${G}Setting theme...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT vset theme_default seven >> install.log 2>&1


# Set configurations
echo "${G}Setting configurations...${W}"
echo "${W}Drush takes over from here...${W}"

php -r "print \"json\naic_background\nsites/default/files/*\";" | ./vendor/bin/drush --root=$DIR_ROOT vset require_login_excluded_paths - >> install.log 2>&1
php -r "print json_encode(array('activity_template','artwork','aic_theme'));" | ./vendor/bin/drush --root=$DIR_ROOT vset --format=json save_edit_node_types - >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT vset site_frontpage "admin/content" >> install.log 2>&1


# Add menu shortcuts
echo "${G}Adding menu shortcuts...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT sql-query "INSERT INTO menu_links (menu_name, link_path, router_path, link_title, options, module, weight, p1, depth) VALUES ('shortcut-set-1', 'artwork-list', 'artwork-list', 'Art by Theme', 'a:0:{}', 'menu', 47, mlid, 1)" >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT sql-query "INSERT INTO menu_links (menu_name, link_path, router_path, link_title, options, module, weight, p1, depth) VALUES ('shortcut-set-1', 'search', 'search', 'Search', 'a:0:{}', 'menu', 48, mlid, 1)" >> install.log 2>&1
./vendor/bin/drush --root=$DIR_ROOT sql-query "INSERT INTO menu_links (menu_name, link_path, router_path, link_title, options, module, weight, p1, depth) VALUES ('shortcut-set-1', 'admin/publish-data', 'admin/publish-data', 'Publish Data', 'a:0:{}', 'menu', 49, mlid, 1)" >> install.log 2>&1


# Generate initial derivatives
echo "${G}Generate image derivatives...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT seed_derivatives >> install.log 2>&1


# Disable unneeded modules
echo "${G}Disable modules that aren't needed beyond this point...${W}"
echo "${W}Drush takes over from here...${W}"

./vendor/bin/drush --root=$DIR_ROOT pm-disable --yes \
      node_export_features \
      node_export >> install.log 2>&1

./vendor/bin/drush --root=$DIR_ROOT cache-clear all >> install.log 2>&1


# Set admin password
echo "${G}Setting admin password...${W}"
echo "${W}Drush takes over from here...${W}"

drpassword=$(date | md5 | base64 | head -c 12)
./vendor/bin/drush user-password admin --password="$drpassword" >> install.log 2>&1


# All done!
echo "${G}JourneyMaker CMS install complete!${W}"

echo "${G}Congrats! You've successfully installed the JourneyMaker CMS. Point the document root of${W}"
echo "${G}your web server to the journeymaker-cms folder and your admin site should be up and running.${W}"
echo
echo "${G}You can log in with the following credentials:${W}"
echo
echo "${G}   Username: admin${W}"
echo "${G}   Password: $drpassword${W}"
echo
echo "${G}If you're interested in connecting the CMS to your own Collections API, go to admin/settings/aic-api${W}"
echo "${G}of your site and enter the queries to use.${W}"
