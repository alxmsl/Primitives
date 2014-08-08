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
 * Exception when no available instances in generator
 * @author alxmsl
 * @date 8/7/14
 */ 
final class NoInstancesFoundException extends Exception {}
 