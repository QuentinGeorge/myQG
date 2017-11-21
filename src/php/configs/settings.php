<?php
define( 'DB_INI_FILE', 'configs/db.ini' );
define( 'PROJECT_PATH', 'http://homestead.app/myQG/build/' );
// define( 'PROJECT_PATH', 'http://www.quentin-george.com/myQG/' );

// Groups name & access
define( 'ADMIN_NAME', 'quentin' );
define( 'GROUPS_ACCESS', array(
    ADMIN_NAME => [ ADMIN_NAME ],
    'famille' => [ 'famille', 'ami' ],
    'ami' => [ 'ami' ],
) );

// Files types
define( 'IMG_FILES_EXT', array( 'jpg', 'jpeg', 'bmp', 'png', 'gif', 'svg' ) );
define( 'AUDIO_FILES_EXT', array( 'mp3', 'wav', 'wma', 'm4a', 'flac' ) );
define( 'VIDEO_FILES_EXT', array( 'avi', 'mov', 'wmv', 'mpg', 'mpeg', 'm4v', 'mkv', 'mp4' ) );

// Upload
define( 'SIZE_CONVERTION_UNIT', 1024 * 1024 ); // MO
define( 'MAX_UPLOAD_SIZE', 126 * SIZE_CONVERTION_UNIT );  // 126 mo
define( 'UPLOAD_UNIT', 'MO' );
define( 'MAX_HDD_ALLOWED_SPACE', 50 * 1024 * 1024 * 1024 ); // 50 GO

// Thumbs
define( 'THUMBS_DIRECTORY', 'thumb' );
define( 'THUMB_WIDTH', 320 ); // px

// Tools
define( 'IMG_DIRECTORY', './assets/img/' );
define( 'FILES_DIRECTORY', './data/' );
define( 'DIR_SCAN_EXCEPT', array( '.', '..', THUMBS_DIRECTORY ) );
define( 'FILES_NAME_SEPARATOR', '~' );
define( 'FILES_NAME_SEPARATOR_REPLACEMENT_CHAR', '-' );
