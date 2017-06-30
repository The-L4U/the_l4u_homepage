# Init php
The php is accessable by the 'endpoint.php'. Whenever the cron calls it, the other files provide api functionalities and the data.json file is created. Then we can work with it the way we want in the frontend. :)

# Init cron
The cronjob is quiete simple. We execute it every hour, every day, etc.:

5 * * * * http://l4u.info/php/endpoint.php > /dev/null 2>&1
