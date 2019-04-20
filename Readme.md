# Documentation 
![](https://img.shields.io/badge/PHP->=7.1-red.svg) 
![](https://img.shields.io/badge/Laravel->=5.6-red.svg) 
![](https://img.shields.io/badge/Lumen->=5.6-red.svg) 
![](https://img.shields.io/badge/Symfony->=4.2-red.svg) 

![](https://img.shields.io/badge/version-1.0.0--rc-green.svg) 


### Let's go
**Composer**

    composer require fabrizio-cafolla/service-response
    
**Laravel / Lumen register providers**

    ServiceResponse\Laravel\ResponseServiceProvider
    
**Register handler Execptions (LaravelHandler or LumenHandler)**

    $this->app->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class,
        \ServiceResponse\Laravel\Exceptions\.....::class
    );
    
**Use**

    use ServiceResponse\Response\RestResponse;
    use RestResponse; //Facade alias
    

    
**PHP 7.1**

    require __DIR__ . '/vendor/autoload.php';
    $response = new ServiceResponse\Response\RestResponse();

    $handler = ServiceResponse\Response\Exceptions\Handler();
    $handler->setExceptionHandler();    

**en**: [documentation](https://github.com/FabrizioCafolla/response-http/wiki/Response-http-documentation)

**it**: [documentazione](https://github.com/FabrizioCafolla/response-http/wiki/Documentazione-response-http)

***

If you find a bug or want to contribute, write to developer@fabriziocafolla.com
