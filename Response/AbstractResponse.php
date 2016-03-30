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
        return isset($this->availableDescriptions[$this->code])
            ? $this->availableDescriptions[$this->code]
            : null;
    }
}
