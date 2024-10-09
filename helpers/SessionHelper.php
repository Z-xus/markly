<?php
class SessionHelper
{
    public static function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key)
    {
        self::startSession();
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    public static function destroy()
    {
        session_unset();
        session_destroy();
    }
}
