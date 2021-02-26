<?php
// An example callback method
class MyClass {
    static function myCallbackMethod() {
        echo 'Hello World!';
    }

    public static function __callStatic(string $name, array $arguments)
    {
//        echo $name;
//        echo is_callable([MyClass::class, 'myCallbackMethod1']);

        var_dump($arguments);
//        var_dump(explode('::', [MyClass::class, 'myCallbackMethod1']));
    }
}
// Type 2: Static class method call
//call_user_func([MyClass::class, 'myCallbackMethodasdfadf'],1);
$factory = [MyClass::class, 'myCallbackMethod1'];
$factory(1,2,2);
?>
