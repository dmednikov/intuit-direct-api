<?php

/**
 * ConfigurationManager - This class taken as is from Intuit SDK and meant to manage
 *                        request for configuration options
 *
 * @author Intuit SDK
 */
class ConfigurationManager {

    private static function getSettignsFromFile() {
        $fileName = getcwd() . "/App.config";
        return simplexml_load_file($fileName);
    }

    private static function getSettings($xmlObj, $xpath, $name = null) {
        $result = $xmlObj->xpath($xpath);

        $returnVal = NULL;
        if ($result && $result[0]) {
            foreach ($result[0]->attributes() as $attrName => $attrVal) {
                switch ($attrName) {
                    case 'value': $returnVal = (string) $attrVal;
                        break 2; // break switch and foreach

                    case $name: $returnVal = (string) $attrVal;
                        break 2; // break switch and foreach
                }
            }
        }

        return $returnVal;
    }

    /**
     * App specific settings
     * @param string targetSetting
     */
    public static function AppSettings($targetSetting) {
        $xmlObj = self::getSettignsFromFile();
        return self::getSettings($xmlObj, '//appSettings/add[@key="' . $targetSetting . '"]');
    }

    /**
     * Get base url depends from the service
     */
    public static function BaseURLSettings($service) {
        $xmlObj = self::getSettignsFromFile();
        return self::getSettings($xmlObj, '//baseUrl', $service);
    }

}
