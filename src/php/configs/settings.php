<?php


define( 'DB_INI_FILE', 'configs/db.ini' );
define( 'PROJECT_PATH', 'http://homestead.app/myQG/build/' );
define( 'FILES_DIRECTORY', './data/' );
define( 'DIR_SCAN_EXCEPT', array( '.', '..' ) );
define( 'SIZE_CONVERTION_UNIT', 1024 * 1024 * 1024 ); // GO
define( 'MAX_UPLOAD_SIZE', 1 * SIZE_CONVERTION_UNIT );  // 1 go

define( 'ADMIN_NAME', 'quentin' );

define( 'GROUPS_ACCESS', array(
    ADMIN_NAME => [ ADMIN_NAME ],
    'famille' => [ 'famille', 'ami' ],
    'ami' => [ 'ami' ],
) );
