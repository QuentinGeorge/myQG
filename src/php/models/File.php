<?php
namespace Models;

use Models\Model as Model;

class File extends Model {
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

    public function fGetGroupFiles( $sGroup ) {
        return $aFiles = array_diff( scandir( FILES_DIRECTORY . $sGroup . '/' ), DIR_SCAN_EXCEPT );
    }

    public function fUploadFile( $sGroup ) {
        if ( $_SERVER[ 'REQUEST_METHOD' ] === 'POST' ) {
            if ( isset( $_FILES[ 'file' ] ) ) {
                if ( !$_FILES[ 'file' ][ 'error' ] ) {
                    $sTmpPath = $_FILES[ 'file' ][ 'tmp_name' ];
                    $aTypeParts = explode( '/', $_FILES[ 'file' ][ 'type' ] );
                    $sExt = '.' . $aTypeParts[ count( $aTypeParts ) -1 ];
                    $sFileName = 'f' . time() . rand( 1000, 9999 ) . $sExt;
                    $sDest = FILES_DIRECTORY . $sGroup . '/' . $sFileName;

                    if ( move_uploaded_file( $sTmpPath, $sDest ) ) {
                        $sFeedback = 'Le fichier a été télécharger';
                    } else {
                        $sFeedback = 'Le fichier n\'a pus être télécharger';
                    }

                    return $sFeedback;
                }
            }
        }
    }
}
