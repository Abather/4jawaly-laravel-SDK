<?php

namespace Sms4jawaly\Laravel;

class Message
{
    protected $message;

    protected $phone;
    protected $sender;

    public function __construct(string $message)
    {
        $this->message($message);
    }

    public function phone($phone)
    {
        $this->phone = $phone;
        return $this;
    }

    public function message($message)
    {
        $this->message = $message;
        return $this;
    }

    public function sender($sender)
    {
        $this->sender = $sender;
        return $this;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getSender()
    {
        return $this->sender?:config("sms-4-jawaly.default_sender");
    }

    public function toArray()
    {
        return [
            $this->getMessage(),
            [$this->getPhone()],
            $this->getSender(),
        ];
    }

    public static function make(...$attributes)
    {
        return new static(...$attributes);
    }
}