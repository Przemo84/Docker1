#!/bin/bash

for ((i=1;i<=15;i++))
   do
        echo "$BASHPID:$(docker exec -i php-fpm--- bin/console doctrine:fixtures:load --append)" >>plik1.txt &
   done
