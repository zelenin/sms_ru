<?php

namespace Zelenin\SmsRu\Response;

class AbstractResponse
{
    /**
     * @var string
     */
    public $code;

    /**
     * @var array
     */
    protected $availableDescriptions = [];

    /**
     * @param string $code
     */
    public function __construct($code)
    {
        $this->code = $code;
    }

    /**
     * @return null|string
     */
    public function getDescription()
    {
        if (isset($this->availableDescriptions[$this->code])) {
            return $this->availableDescriptions[$this->code];
        }
        return null;
    }
}
