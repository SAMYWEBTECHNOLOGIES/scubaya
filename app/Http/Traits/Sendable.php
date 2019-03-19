<?php

namespace App\Http\Traits;

trait Sendable
{
    protected $_rendered_content = null;

    public function getSender($flat = false): string
    {
        return $this->sender;
    }

    public function getContent(): string
    {
        if (is_null($this->_rendered_content))
        {
            $this->_rendered_content = $this->renderContent();
        }

        return $this->_rendered_content;
    }

    public function getAction()
    {
        return $this->action;
    }
}