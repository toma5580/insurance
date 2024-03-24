<?php

namespace App\Exceptions;

use RuntimeException;

class InsuraModelNotFoundException extends RuntimeException {

    /**
     * Status of the intended response.
     *
     * @var bool
     */
    protected $back = false;

    /**
     * Return the back status.
     *
     * @return bool
     */
    public function getBack() {
        return $this->back;
    }

    /**
     * Set the back status.
     *
     * @param  bool   $back
     * @return $this
     */
    public function setBack($back) {
        $this->back = $back;
        return $this;
    }
};
