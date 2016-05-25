<?php

namespace BluePost;

class ErrorScheme {

    /**
     * @var string
     */
    public $CODE;
    /**
     * @var string
     */
    public $MESSAGE;
    /**
     * @var ErrorScheme
     */
    public $SUPER = null;

    function __construct($code, $message, $super = null)
    {
        $this->CODE = $code;
        $this->MESSAGE = $message;
        $this->SUPER = $super;
    }

    function codes() {
        if ($this->SUPER == null) return array($this->CODE);
        $codes = $this->SUPER->codes();
        $codes[] = $this->CODE;
        return $codes;
    }

    /**
     * @return array
     */
    function  build () {
        return blueErrorArray($this->codes(), $this->MESSAGE);
    }

    /**
     * @param $code
     * @param $message
     * @return array
     */
    function custom($code, $message) {
        //TODO: Make this more efficient
        return (new ErrorScheme($code, $message))->build();
    }

}