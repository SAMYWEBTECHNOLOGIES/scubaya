<?php

namespace App\Scubaya\Services\Contracts;

interface Sendable
{
    public function getSender();

    public function getRecipient();

    public function getSubject();

    public function getAction();

    public function getContent();
}