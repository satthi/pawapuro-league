#!/bin/sh
php bin/cake.php migrations rollback;
php bin/cake.php migrations migrate;
php bin/cake.php migrations seed;
