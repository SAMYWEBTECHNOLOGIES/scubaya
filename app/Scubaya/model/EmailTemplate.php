<?php

namespace App\Scubaya\model;

use App\Scubaya\Services\Contracts\Sendable;
use App\Http\Traits\Sendable as SendableTrait;
use Illuminate\Database\Eloquent\Model;
use App\Scubaya\Services\ViewService;
use phpDocumentor\Reflection\Types\Self_;

class EmailTemplate extends Model implements Sendable
{
    use SendableTrait;

    protected $fillable     =   [
        'user_type',
        'name',
        'action',
        'subject',
        'sender_name',
        'sender_email',
        'template_content'
    ];

    protected $data = null;

    public static function saveEmailTemplate($data)
    {
        $email_template     =   new EmailTemplate();

        foreach ($data as $key => $value){
            $email_template->$key   =   $value;
        }

        $email_template->save();
    }

    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getSubject()
    {
        $subject = str_replace('-&gt;', '->', $this->subject);

        switch ($this->action)
        {
            case 'merchant_account_verification':
                return ViewService::render($subject, [
                    'merchant' => $this->getData(),
                ]);

            case 'user_query':
                return ViewService::render($subject, [
                    'merchant' => $this->getData(),
                ]);

            case 'user_account_verification':
                return ViewService::render($subject, [
                    'user' => $this->getData(),
                ]);

            case 'password_reset':
                return ViewService::render($subject, [
                    'merchant' => $this->getData(),
                ]);
        }
    }

    public function getContent(){

        $content =  str_replace('-&gt;', '->', $this->template_content);

        switch ($this->action)
        {
            case 'merchant_account_verification':
                 return ViewService::render($content, [
                     'merchant' => $this->getData(),
                 ]);

            case 'user_query':
                return ViewService::render($content, [
                    'merchant' => $this->getData(),
                ]);

            case 'password_reset':
                return ViewService::render($content, [
                    'merchant' => $this->getData(),
                ]);

            case 'user_account_verification':
                return ViewService::render($content, [
                    'user' => $this->getData(),
                ]);
        }
    }

    public function getSender($flat = false)
    {
        if ($flat)
        {
            return sprintf('%s <%s>', $this->sender_name, $this->sender_email);
        }

        return [
            'email' => $this->sender_email,
            'name' => $this->sender_name,
        ];
    }

    public function getRecipient()
    {
        switch ($this->action)
        {
            case 'confirm_user':
                    return decrypt($this->getData()->email);

            case 'merchant_account_verification':
                    return $this->getData()->email;

            case 'user_query':
                    return $this->getData()->email;

            case 'password_reset':
                    return $this->getData()->email;

            case 'user_account_verification':
                    return User::decryptString($this->getData()->email);
        }
    }

    public function getAttachments()
    {
        return [];
    }

    public static function getTemplateByAction($userType, $action)
    {
        return EmailTemplate::where('user_type', $userType)
                     ->where('action', $action)
                     ->first();
    }
}
