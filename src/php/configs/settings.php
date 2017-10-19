<?php

define( 'DB_INI_FILE', 'configs/db.ini' );
define( 'PROJECT_PATH', 'http://homestead.app/myQG/build/' );
define( 'ADMIN_NAME', 'quentin' );
define( 'GROUPS_ACCESS', array(
    ADMIN_NAME => [ ADMIN_NAME ],
    'famille' => [ 'famille', 'amis' ],
    'amis' => [ 'amis' ],
) );
