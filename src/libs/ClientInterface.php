<?php
namespace App\Libs;

interface ClientInterface
{
    public function get(string $url): string;
}

