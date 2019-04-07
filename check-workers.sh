#! /bin/bash

process="php /var/www/scrapethat.com/artisan queue:work --tries=3"

case "$(ps aux | grep "$process" | wc -l)" in

1)  # worker not running, only grep itself shows up
    nohup $process >/dev/null 2>&1 &
    ;;
*)  # all good, process is running
    ;;
esac
