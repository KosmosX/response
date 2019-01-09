# Documentation 
![](https://img.shields.io/badge/version-1.0.1--rc-green.svg) 

**en**: [documentation](https://github.com/FabrizioCafolla/response-http/wiki/Response-http-documentation)

**it**: [documentazione](https://github.com/FabrizioCafolla/response-http/wiki/Documentazione-response-http)

#### Compatibility 

with PHP >=7.1

with Laravel or Lumen >=5.6
 
with Symfony >=4.2 

#### Let's go
    
    composer require fabrizio-cafolla/response-http
    
    //Laravel or Lumen register providers
    ResponseServiceProvider
    use ResponseHTTP\Response\HttpResponse;
    use ServiceResponse; (Facade alias)
    
    //Register handler Execptions (LaravelHandler or LumenHandler)
    $this->app->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class,
        \ResponseHTTP\Response\Laravel\Exceptions\.....::class
    );
    
    //PHP 7.1
    require __DIR__ . '/vendor/autoload.php';
    $response = new ResponseHTTP\Response\HttpResponse();

    $handler = ResponseHTTP\Response\Exceptions\Handler();
    $handler->setExceptionHandler();    
***

If you find a bug or want to contribute, write to developer@fabriziocafolla.com

![meme](http://blog.davidjs.com/wp-content/uploads/2018/09/debugging.jpg)
