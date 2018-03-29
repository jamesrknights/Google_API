<?php 

require_once($_SERVER['DOCUMENT_ROOT'].'app_config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . AppConfig::APP_DIR . 'service/_AbstractService.php');


    class GoogleService extends AbstractService {

        public function __construct () {
            parent::__construct();
        }

        /**
         * Service: Google Drive
         * Params: 
         * @action: String - The Action for Google Drive
         * @opts: Array - Array of options
         */
        public function drive ($action, $opts) {
            $result = null;
            if (!empty($action)) {
                try {
                    $result = parent::google(AppConfig::DRIVE, $action, $opts);
                } catch (Exception $e) {
                    print_r($e->getMessage());
                }
            }
            return $result;
        }

        /**
         * Service: Google Play
         * Params: 
         * @action: String - The Action for Google Play
         * @opts: Array - Array of options
         */
        public function play ($action, $opts) {
            $result = null;
            if (!empty($action)) {
                try {
                    $result = parent::google(AppConfig::PLAY, $action, $opts);
                } catch (Exception $e) {
                    print_r($e->getMessage());
                }
            }
            return $result;
        }

        /**
         * Service: Google Translate
         * Params: 
         * @data : String - Data to translate
         */
        public function translate ($data) {
            $result = null;
            if (!empty($data)) {
                try {
                    $result = parent::translate($data);
                } catch (Exception $e) {
                    print_r($e->getMessage());
                }
            }
            return $result;
        }

        

    }