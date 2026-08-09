<?php

function env(string $key, string $default): string
{
    $value = getenv($key);
    return $value === false ? $default : $value;
}
