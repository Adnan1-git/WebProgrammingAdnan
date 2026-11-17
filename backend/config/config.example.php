<?php
class Config
{
    public static function DB_HOST()     { return self::get_env('DB_HOST', '127.0.0.1'); }
    public static function DB_PORT()     { return self::get_env('DB_PORT', 3306); }
    public static function DB_NAME()     { return self::get_env('DB_NAME', 'Adnan Radoncic'); }
    public static function DB_USER()     { return self::get_env('DB_USER', 'root'); }
    public static function DB_PASSWORD() { return self::get_env('DB_PASSWORD', ' '); }

    // Environment variables (set on the server) win over the default values above.
    public static function get_env( string $name, $default=null )
    {
        $value = getenv($name);
        return ($value !== false && trim($value) !== '') ? $value : $default;
    }


        public static function WEATHER_API_KEY()
    {
        return getenv('WEATHER_API_KEY') ?: 'PUT_YOUR_KEY_HERE';
    }

    public static function WEATHER_API_BASE_URL()
    {
        return 'https://api.openweathermap.org/data/2.5';
    }

    public static function WEATHER_CONNECT_TIMEOUT()
    {
        return 3; 
    }

    public static function WEATHER_TIMEOUT()
    {
        return 5; // seconds
    }




}
