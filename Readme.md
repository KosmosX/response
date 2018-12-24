# Documentation 
![](https://img.shields.io/badge/version-1.0.0--rc-green.svg) 

**en**: [documentation](https://github.com/FabrizioCafolla/response-http/wiki/Response-http-documentation)

**it**: [documentazione](https://github.com/FabrizioCafolla/response-http/wiki/Documentazione-response-http)

#### Let's go
    
    composer require fabrizio-cafolla/response-http
    
    //Laravel or Lumen
    ResponseServiceProvider (register in app)
    
    //Register handler Execptions (LaravelHandler or LumenHandler)
    $this->app->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class,
        \ResponseHTTP\Response\Laravel\Exceptions\.....::class
    );
    
    //Use it
    use ResponseHTTP\Response\HttpResponse;
    use HttpResponse; (Facade alias)
    
***

If you find a bug or want to contribute, write to developer@fabriziocafolla.com

![meme](http://blog.davidjs.com/wp-content/uploads/2018/09/debugging.jpg)
