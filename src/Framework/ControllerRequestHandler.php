<?php
declare(strict_types= 1);
namespace Framework;

class ControllerRequestHandler implements RequestHandlerInterface
{
    public function __construct(private Controller $controller,
                                private string $method,
                                private array $args)
    {
        }
    public function handle(Request $request) : Response
    {
        $this->controller->setRequest($request);
        return ($this->controller)->{$this->method}(...$this->args);
    }
}