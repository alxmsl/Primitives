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
 * Exception for unknown signals
 * @author alxmsl
 * @date 8/7/14
 */ 
final class UnknownSignalException extends Exception {
    public function __construct($Exception) {
        $type = is_object($Exception)
            ? get_class($Exception)
            : gettype($Exception);
        parent::__construct(sprintf('unknow signal type [%s]', $type));
    }
}
 