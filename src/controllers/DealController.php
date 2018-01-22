<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;
/**
 * Description of DealController
 *
 * @author yongquizheng
 */
class DealController {
    //put your code here
    protected $container;
    public function __construct(ContainerInterface $container) {
        $this->container =$container;
    }
    
    public function createDeal($request, $response, $args){
        // create deal base on business id
    }
}
