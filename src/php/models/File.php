<?php
namespace Models;

use Models\Model as Model;

class File extends Model {
    public function fCreateMissingFilesFolders() {
        if ( !file_exists( FILES_DIRECTORY ) && !is_dir( FILES_DIRECTORY ) ) {
            mkdir( FILES_DIRECTORY );
        }
        foreach ( GROUPS_ACCESS as $key => $value ) {
            if ( !file_exists( FILES_DIRECTORY . '/' . $key ) && !is_dir( FILES_DIRECTORY . '/' . $key ) ) {
                mkdir( FILES_DIRECTORY . '/' . $key );
            }
        }
    }

    public function fGetAuthorizedGroupsFilesByUserName() {
        $aAccessiblesGroups = [];

        if ( $_SESSION[ 'user' ][ 'name' ] === ADMIN_NAME ) {
            foreach ( GROUPS_ACCESS as $key => $value ) {
                array_push( $aAccessiblesGroups, $key );
            }

            return $aAccessiblesGroups;
        } else {
            foreach ( GROUPS_ACCESS as $key => $value ) {
                if ( $key === $_SESSION[ 'user' ][ 'name' ] ) {
                    return $value;
                }
            }
        }
    }

    public function fGetFileType( $sFile ) {
        $aNameParts = explode( '.', $sFile );
        $sExt = $aNameParts[ count( $aNameParts ) -1 ];
        // Check if is img
        foreach ( IMG_FILES_EXT as $sImgExt ) {
            if ( $sImgExt === $sExt ) {
                return 'img';
            }
        }
        // Check if is audio
        foreach ( AUDIO_FILES_EXT as $sAudioExt ) {
            if ( $sAudioExt === $sExt ) {
                return 'audio';
            }
        }
        // Check if is video
        foreach ( VIDEO_FILES_EXT as $sVideoExt ) {
            if ( $sVideoExt === $sExt ) {
                return 'video';
            }
        }

        return 'other';
    }

    public function fGetGroupFiles( $sGroup ) {
        // Get all files by ascending name. Because of the prefixe the lasted uploaded file will be the first of the list.
        $aFiles = array_diff( scandir( FILES_DIRECTORY . $sGroup . '/', 1 ), DIR_SCAN_EXCEPT );

        // Prepare files array
        foreach ( $aFiles as $key => $sPrefixedFileName ) {
            // Unprefix file to retreview the original name
            $aNameParts = explode( FILES_NAME_SEPARATOR, $sPrefixedFileName );
            $sUnPrefixedFileName = $aNameParts[ count( $aNameParts ) -1 ];
            // Get the file type
            $sFileType = $this->fGetFileType( $sPrefixedFileName );
            // Push all informations in array
            $aFiles[ $key ] = array( 'servername' => $sPrefixedFileName, 'originalname' => $sUnPrefixedFileName, 'type' => $sFileType, 'thumb' => THUMBS_DIRECTORY . '/' . $sPrefixedFileName );
        }

        return $aFiles;
    }

    public function fCreateThumbnail( $sDirectory, $sFile ) {
        if ( preg_match( '/[.](jpg|jpeg)$/', $sFile ) ) {
            $oImg = imagecreatefromjpeg( $sDirectory . $sFile );
        } else if ( preg_match( '/[.](bmp)$/', $sFile ) ) {
            $oImg = imagecreatefrombmp( $sDirectory . $sFile );
        } else if ( preg_match( '/[.](png)$/', $sFile ) ) {
            $oImg = imagecreatefrompng( $sDirectory . $sFile );
        } else if ( preg_match( '/[.](gif)$/', $sFile ) ) {
            $oImg = imagecreatefromgif( $sDirectory . $sFile );
        } else {
            return;
        }

        $iOriginalWidth = imagesx( $oImg );
        $iOriginalHeight = imagesy( $oImg );
        $iThumbWidth = THUMB_WIDTH;
        $iThumbHeight = floor( $iOriginalHeight * ( THUMB_WIDTH / $iOriginalWidth ) );

        $oThumbImg = imagecreatetruecolor( $iThumbWidth, $iThumbHeight );
        imagecopyresampled( $oThumbImg, $oImg, 0, 0, 0, 0, $iThumbWidth, $iThumbHeight, $iOriginalWidth, $iOriginalHeight );

        // If thumb directory doesn't exist, create it
        if ( !file_exists( $sDirectory . THUMBS_DIRECTORY ) && !is_dir( $sDirectory . THUMBS_DIRECTORY ) ) {
            mkdir( $sDirectory . THUMBS_DIRECTORY );
        }

        imagejpeg( $oThumbImg, $sDirectory . THUMBS_DIRECTORY . '/' . $sFile );
    }

    public function fUploadFile( $sGroup ) {
        if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
            if ( isset( $_FILES[ 'file' ] ) ) {
                if ( !$_FILES[ 'file' ][ 'error' ] ) {
                    if ( $_FILES[ 'file' ][ 'size' ] < MAX_UPLOAD_SIZE ) {
                        $sTmpPath = $_FILES[ 'file' ][ 'tmp_name' ];
                        $sIDPrefix = 'f' . time() . rand( 1000, 9999 );
                        $sOriginalName = str_replace( FILES_NAME_SEPARATOR, FILES_NAME_SEPARATOR_REPLACEMENT_CHAR, $_FILES[ 'file' ][ 'name' ] ); // to be sure FILES_NAME_SEPARATOR isn't used in the original name because it's used later as separator
                        $sFileName = $sIDPrefix . FILES_NAME_SEPARATOR . $sOriginalName;
                        $sDirectory = FILES_DIRECTORY . $sGroup . '/';
                        $sDest = $sDirectory . $sFileName;

                        if ( move_uploaded_file( $sTmpPath, $sDest ) ) {
                            $this->fCreateThumbnail( $sDirectory, $sFileName );
                            $sFeedback = 'Le fichier a été télécharger';
                        } else {
                            $sFeedback = 'Le fichier n´a pus être télécharger';
                        }
                    } else {
                        $sFeedback = 'La taille du fichier dépasse la limite autorisée de ' . MAX_UPLOAD_SIZE / SIZE_CONVERTION_UNIT . ' GO';
                    }
                } else {
                    $sFeedback = 'Aucun fichier séléctionner';
                }

                return $sFeedback;
            }
        }
    }
}
