<?php

namespace App\Contracts;

interface Sendable
{
    public function getSendingModel($action);

    public function getAction();

    public function getSender($flat = false);

    public function getRecipient(): string;

    public function getContent(): string;

    public function logActivity($sucess);
}
