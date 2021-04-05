<?php

namespace App\Services;

use Exception;
use Google_Client;
use Google_Service_Drive;
use Google_Service_Drive_DriveFile;

class GoogleDriveService
{

    /**
     * Returns an authorized API client.
     * @return Google_Client the authorized client object
     */
    static public function getClient()
    {
        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP Quickstart');
        $client->setScopes(Google_Service_Drive::DRIVE_METADATA_READONLY);
        $client->setAuthConfig(__DIR__ . '/credentials.json');
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = 'token.json';
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = '4/1AY0e-g4OUs8xJlTyDuBcmVyV1hWRmFAPzcCO-vaxaESZNGr8y2hdw83My5E';

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }
        return $client;
    }

    function insert_file_to_drive($file_path, $file_name, $parent_file_id = null)
    {
        $client = self::getClient();
        $service = new Google_Service_Drive($client);
        $file = new Google_Service_Drive_DriveFile();

        $file->setName($file_name);

        if (!empty($parent_file_id)) {
            $file->setParents([$parent_file_id]);
        }

        $result = $service->files->create(
            $file,
            array(
                'data' => file_get_contents($file_path),
                'mimeType' => 'application/octet-stream',
            )
        );

        $is_success = false;

        if (isset($result['name']) && !empty($result['name'])) {
            $is_success = true;
        }

        return $is_success;
    }

    function create_folder( $folder_name, $parent_folder_id=null ){

        $folder_list = check_folder_exists( $folder_name );

        // if folder does not exists
        if( count( $folder_list ) == 0 ){
            $service = new Google_Service_Drive( $GLOBALS['client'] );
            $folder = new Google_Service_Drive_DriveFile();

            $folder->setName( $folder_name );
            $folder->setMimeType('application/vnd.google-apps.folder');
            if( !empty( $parent_folder_id ) ){
                $folder->setParents( [ $parent_folder_id ] );
            }

            $result = $service->files->create( $folder );

            $folder_id = null;

            if( isset( $result['id'] ) && !empty( $result['id'] ) ){
                $folder_id = $result['id'];
            }

            return $folder_id;
        }

        return $folder_list[0]['id'];

    }

    public static function fnu_t()
    {
        // Get the API client and construct the service object.
        $client = self::getClient();
        $service = new Google_Service_Drive($client);

// Print the names and IDs for up to 10 files.
        $optParams = array(
            'pageSize' => 10,
            'fields' => 'nextPageToken, files(id, name)'
        );
        $results = $service->files->listFiles($optParams);

        if (count($results->getFiles()) == 0) {
            return "No files found.\n";
        } else {
            print "Files:\n";
            foreach ($results->getFiles() as $file) {
                printf("%s (%s)\n", $file->getName(), $file->getId());
            }
        }
    }


}
