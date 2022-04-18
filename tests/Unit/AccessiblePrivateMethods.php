<?php
/**
 * Created by PhpStorm.
 * User: maayan
 * Date: 4/18/22
 * Time: 9:47 AM
 */

namespace Tests\Unit;

use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

trait AccessiblePrivateMethods
{
    /**
     * @throws ReflectionException
     */
    public function getPrivateMethods(string $className, string $methodName): ReflectionMethod
    {
        $reflection = new ReflectionClass($className);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method;
    }
}
