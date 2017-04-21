#!/bin/bash
TCP_HOST=mysqldb
TCP_PORT=3306


 while ! exec 6<>/dev/tcp/${TCP_HOST}/${TCP_PORT}; do
     #echo -ne "$(date) - still trying to connect to mysql at ${TCP_HOST}\r"
     sleep 5
 done

