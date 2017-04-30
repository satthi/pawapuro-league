#!/bin/sh
pg_dump -U postgres game_next > /var/www/html/game_next/game_next.sql
