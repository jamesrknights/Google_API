<?php 
require_once($_SERVER['DOCUMENT_ROOT'].'app_config.php');
require_once($_SERVER['DOCUMENT_ROOT'] . AppConfig::APP_DIR . 'vendor/autoload.php');
require_once($_SERVER['DOCUMENT_ROOT'] . AppConfig::APP_DIR .  'vendor/google/cloud-translate/TranslateClient.php');
require_once($_SERVER['DOCUMENT_ROOT'] . AppConfig::APP_DIR .  'vendor/google/apiclient/src/Google/Client.php');
require_once($_SERVER['DOCUMENT_ROOT'] . AppConfig::APP_DIR .  'vendor/google/apiclient-services/src/Google/Service/AndroidPublisher.php');
putenv('GOOGLE_APPLICATION_CREDENTIALS=' . $_SERVER['DOCUMENT_ROOT'] . AppConfig::APP_DIR . AppConfig::SERVICE_ACCOUNT_FILE);

use Google\Cloud\Translate\TranslateClient;

abstract class AbstractService {

    private $Google;

    public function __construct () {}

    public function translate ($data) {
        $result= null;
        try {
            $client = new TranslateClient([
                "key" => AppConfig::GOOGLE_TRANSLATE_API_KEY
            ]);
            if (isset($client)) {
                $result = $client->translate($data, [
                    'target' => AppConfig::GOOGLE_TRANSLATE_TARGET_LANGUAGE
                ]);
            }
        } catch (Exception $e) {
            //DEAL WITH EXCEPTION
            print_r($e->getMessage());
        }
        return $result;
    }

    public function send ($message) {
        mail(AppConfig::TO_EMAIL, AppConfig::EMAIL_SUBJECT, $message, AppConfig::GET_EMAIL_HEADERS());
    }

    public function start ($service) {
        Logger::DEBUG($service . " STARTED");
    }
    /**
     * Service Account Calls
     */
    public function google ($serviceName, $action, $opts) {
        $result = null;
        if (!empty($serviceName) && !empty($action)) {
            $this->Google = new GoogleBuilder();
            switch ($serviceName) {
                case "drive":
                    $result = $this->Google->drive($action, $opts);
                    break;
                case "play":
                    $result = $this->Google->play($action, $opts);
                    break;
                default:
                    print_r("No Google Service Provided");
                    break;
            }
        }
        return $result;
    }

    public function processMessage ($data) {
		$result = null; 
		if (isset($data)) {
			try {
				$result = $data;
			} catch (Exception $e) {
				print_r("Processing Message Error" . $e->getMessage());
			}
		}
		return $result;
	}

}

final class GoogleBuilder {

    private $GoogleApiClient;
    private $play;
    private $drive;

    public function __construct () {
        $this->GoogleApiClient = new Google_Client();
        $this->GoogleApiClient->useApplicationDefaultCredentials();
    }

    public function drive ($action, $opts) {
        $result = null;
        if (isset($action)) {
            $this->GoogleApiClient->addScope(Google_Service_Drive::DRIVE);
            $drive = new Google_Service_Drive($this->GoogleApiClient);
            if (!empty($action)) {
                switch ($action) {
                    case "listFiles":
                        $result = function ($drive) {
                            return $drive->files->listFiles();
                        };
                        break;
                    default:
                        $result = function () {
                            echo "No Action Entered";
                        };
                        break;
                }
            }
        }
        return $result($drive);
    }

    public function play ($action, $opts) {
        if (isset($action)) {
            $this->GoogleApiClient->addScope(Google_Service_AndroidPublisher::ANDROIDPUBLISHER);
            $play = new Google_Service_AndroidPublisher($this->GoogleApiClient);
            if (!empty($action)) {
                switch ($action) {
                    case "listReviews":
                        $result = function ($play, $opts) {
                            return $play->reviews->listReviews($opts["packageName"])["reviews"];
                        };
                        break;
                    default:
                        $result = function () {
                            print_r("No Action Entered");
                        };
                        break;
                }
            }
        }
        return $result($play, $opts);
    }
}