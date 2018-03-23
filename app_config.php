<?php

class AppConfig {

    //General Configuration

    const APPLICATION_NAME = "Google Api Service";
    const APP_DIR = "/link/to/application"; //Document Root is Appended First i.e http://www.example.com/{APP_DIR}
    const SERVICE_ACCOUNT_FILE = "/link/to/service/account/file"; //Link to JSON Service account
    
    //Service names
    const DRIVE = "drive";
    const PLAY = "play";


    //translate options
    const GOOGLE_TRANSLATE_API_KEY = "API_KEY"; //API Key from Google API Console
    const GOOGLE_TRANSLATE_TARGET_LANGUAGE = "en";
    
}