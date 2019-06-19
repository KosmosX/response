# Documentation 
![](https://img.shields.io/badge/version-1.0.2-green.svg) 
![](https://img.shields.io/badge/Laravel->=7.1-blue.svg) 
![](https://img.shields.io/badge/Symfony->=4.2-blue.svg) 

### Let's go
**Composer**

    composer require kosmosx/response

    require __DIR__ . '/vendor/autoload.php';
    $response = new Kosmosx\Response\RestResponse();

    $handler = Kosmosx\Response\Exceptions\Handler();
    $handler->setExceptionHandler();  

**use it**

    $response = new Kosmosx\Response\RestResponse($content, $status, $headers); //constructor
    $response = new Kosmosx\Response\Factory\FactoryResponse::success(); //factory
    
    $response->withData(true); //add to json array with key 'data'
    
## Example

    $response->success()
             ->withData(['test' => true])
             ->withData(['test2' => false])
             ->withMessage('Microservice Lumen work')
             ->withState();
    
    //response 				
    {
        "data": {
            "test": true,
            "test2": false
        },
        "messages": "Microservice Lumen work",
        "state": "OK"
    }
    
**en**: [documentation](https://github.com/FabrizioCafolla/response-http/wiki/Response-http-documentation)

**it**: [documentazione](https://github.com/FabrizioCafolla/response-http/wiki/Documentazione-response-http)

***

If you find a bug or want to contribute, write to developer@fabriziocafolla.com
