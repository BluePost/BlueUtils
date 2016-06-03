<?php

namespace BluePost;

/**
 * Class ErrorScheme
 * Represents an error type
 * @package BluePost
 */
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

    /**
     * ErrorScheme constructor.
     * @param string $code The code of the error
     * @param string $message The human readable message
     * @param ErrorScheme $super The container ErrorScheme
     */
    function __construct($code, $message, $super = null)
    {
        $this->CODE = $code;
        $this->MESSAGE = $message;
        $this->SUPER = $super;
    }

    /**
     * Returns the codes as an array (takes the parents' codes)
     * @return array
     */
    function codes() {
        if ($this->SUPER == null) return array($this->CODE);
        $codes = $this->SUPER->codes();
        $codes[] = $this->CODE;
        return $codes;
    }

    /**
     * Return this error as an array
     * @see blueErrorArray
     * @return array
     */
    function  build () {
        return blueErrorArray($this->codes(), $this->MESSAGE);
    }

    /**
     * Dynamically create a sub error from this scheme and return it as an array
     * @param string $code The code of the sub error
     * @param string $message The human readable message for the errors
     * @return array
     */
    function custom($code, $message) {
        //TODO: Make this more efficient
        return (new ErrorScheme($code, $message, $this))->build();
    }

}