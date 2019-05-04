# Documentation 
![](https://img.shields.io/badge/version-1.0.1-green.svg) 
![](https://img.shields.io/badge/Laravel->=5.6-blue.svg) 
![](https://img.shields.io/badge/Lumen->=5.6-blue.svg) 
![](https://img.shields.io/badge/Symfony->=4.2-blue.svg) 



### Let's go
**Composer**

    composer require kosmosx/response
    
**Laravel / Lumen register providers**

    Kosmosx\Response\Laravel\ResponseServiceProvider
    
**Register handler Execptions (LaravelHandler or LumenHandler)**

    $this->app->singleton(
        \Illuminate\Contracts\Debug\ExceptionHandler::class,
        \Kosmosx\Response\Laravel\Exceptions\.....::class
    );
    
**Use**

    use Kosmosx\Response\RestResponse;
    use RestResponse; //Facade alias
    
    
**PHP 7.1**

    require __DIR__ . '/vendor/autoload.php';
    $response = new Kosmosx\Response\RestResponse();

    $handler = Kosmosx\Response\Exceptions\Handler();
    $handler->setExceptionHandler();  
    
### Benchmark

Same PC hardware (RAM: 16GB (2x8 GB) DDR4 2133 MHz, CPU: Intel Core i5 7400 Quad Core & 4 Thread 3.0GHz)

**Illuminate/Response**

Response weight 4.26Mb in 158~161ms 

    $app = array();
    for ($x = 0; $x <= 1000; $x++) {
        $app[$x] = new JsonResponse(array('data'=>[true,false], "message" => 'Microservice Lumen work', "state" => "OK"));
    }
    return var_dump($app);

**Kosmosx/Response**

Response weight 3.6Mb in 148~151ms
 
    $app = array();
    for ($x = 0; $x <= 1000; $x++) {
        $app[$x] = new RestResponse(array('data'=>[true,false], "message" => 'Microservice Lumen work', "state" => "OK"));
    }
    return var_dump($app);

Response weight 3.6Mb in 220~223ms
 
    $app = array();
    for ($x = 0; $x <= 1000; $x++) {
        $app[$x] = $this->response->success()
            ->withMessage('Microservice Lumen work')
            ->withData(true)
            ->withData(false)
            ->withState();
    }
    return var_dump($app);

Results

    Kosmosx/Response it is 15.50% smaller than Illuminate
    Kosmosx/Response it is 6.3% faster than Illuminate (if use constructor)
    
**en**: [documentation](https://github.com/FabrizioCafolla/response-http/wiki/Response-http-documentation)

**it**: [documentazione](https://github.com/FabrizioCafolla/response-http/wiki/Documentazione-response-http)

***

If you find a bug or want to contribute, write to developer@fabriziocafolla.com
