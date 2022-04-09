#!/bin/bash
PORT=$(dconf read /org/vas/remote-dconf/port)
if test -z "$PORT"
then export PORT='8000'
fi

TOKEN=$(dconf read /org/vas/remote-dconf/token)
if test -z "$TOKEN"
then export TOKEN='test'
fi

if test "$1" = 'production'
then
    echo 'starting remote-dconf'
    php -S 0.0.0.0:$PORT index.php 2> /dev/null
    echo 'remote-dconf killed'
else php -S 0.0.0.0:$PORT index.php
fi
