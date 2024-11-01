<?php

namespace App\exceptions\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

class NotFoundContainerException extends Exception implements NotFoundExceptionInterface {}
