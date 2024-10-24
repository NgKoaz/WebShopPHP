<?php

abstract class Middleware
{
    abstract public function handle($request, Closure $next);
}
