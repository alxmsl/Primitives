<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Pool\Signal;
use alxmsl\Primitives\Pool\IdentificationInterface;

/**
 * Abstract generator signal class
 * @author alxmsl
 * @date 8/7/14
 */ 
abstract class AbstractSignal {
    /**
     * @var IdentificationInterface|null subject instance for signal
     */
    private $Instance = null;

    /**
     * Subject instance getter
     * @return IdentificationInterface|null subject instance
     */
    public function getInstance()  {
        return $this->Instance;
    }

    /**
     * @param IdentificationInterface $Instance subject signal
     */
    public function __construct(IdentificationInterface $Instance) {
        $this->Instance = $Instance;
    }
}
 