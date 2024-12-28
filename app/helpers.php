<?php

if (! function_exists('team')) {
    function team()
    {
        return request()->team();
    }
}
