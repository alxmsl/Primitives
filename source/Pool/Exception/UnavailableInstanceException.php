<?php
/*
 * This program is free software. It comes without any warranty, to
 * the extent permitted by applicable law. You can redistribute it
 * and/or modify it under the terms of the Do What The Fuck You Want
 * To Public License, Version 2, as published by Sam Hocevar. See
 * http://www.wtfpl.net/ for more details.
 */

namespace alxmsl\Primitives\Pool\Exception;
use Exception;

/**
 * Exception when process instance is unavailable
 * @author alxmsl
 * @date 8/7/14
 */ 
final class UnavailableInstanceException extends Exception {
    /**
     * @var string instance identifier
     */
    private $instanceId = '';

    /**
     * Instance identifier setter
     * @param string $instanceId instance identifier
     * @return UnavailableInstanceException self exception
     */
    public function setInstanceId($instanceId) {
        $this->instanceId = (string) $instanceId;
        return $this;
    }

    /**
     * Instance identifier getter
     * @return string instance identifier
     */
    public function getInstanceId() {
        return $this->instanceId;
    }
}
 