#!/bin/sh
python2.6 ~/bin/dropbox.py start
pg_dump -U postgres game_next > /home/vagrant/Dropbox/game_next.sql
pg_dump -U postgres game_next > /home/vagrant/game_next.sql
