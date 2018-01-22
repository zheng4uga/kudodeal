<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of business
 *
 * @author yongquizheng
 */

namespace Controllers;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \Interop\Container\ContainerInterface as ContainerInterface;

class BusinessController {
    //put your code here
    protected $container;
    protected $contstants;
    protected $business_table="business";
    protected $state_table="state";
    protected $deal_table="deal";
    
    public function __construct(ContainerInterface $container) {
       $this->container = $container;
       $this->contstants = $container['constants'];
    }
    
    public function getBusinessBaseOnLocation($request, $response, $args){
        $db = $this->container['db'];
        $lat = $args['lat'];
        $lon = $args['lon'];
        $distance = $args['distance'];
        $business_table=$this->contstants->business_table;
        $state_table=$this->contstants->state_table;
        $queryStatement ="SELECT b.* , (((acos(sin(($lat * pi()/180)) * "
                . "sin((`latitude` * pi()/180)) + cos(($lat * pi()/180)) * "
                . "cos((`latitude` * pi()/180)) * cos((($lon - `longitude` ) * "
                . "pi()/180)))) * 180/pi()) * 60 * 1.1515) as distance, s.state_abbr "
                . "FROM `$business_table` as b LEFT JOIN `$state_table` as s "
                . "ON b.state=s.id  HAVING distance <= $distance";
        $query =$db->query($queryStatement);
        $resp = array();
        foreach($query as $row){
            $bid = $row['id'];
           $deals= $this->getDealsFromBusiness($bid);
            array_push($resp, array("address"=>$row['address'],
                'dba'=>$row['dba'],
                'state'=>$row['state_abbr'],
                'zipcode'=>$row['zipcode'],
                'address2'=>$row['address2'],
                'city'=>$row['city'],
                'distance'=> floatval($row['distance']),
                'deals'=>$deals));
                
        }
        return $response->withJson($resp,200,JSON_UNESCAPED_UNICODE);
    }
    
    private function getDealsFromBusiness($id){
       $db = $this->container['db'];
       $deal_table=$this->contstants->deal_table;
       $deal_type_table=$this->contstants->deal_types_table;
       $query = $db->query("SELECT * FROM $deal_table as "
               . "d LEFT JOIN $deal_type_table as dt ON d.type=dt.id  WHERE business_fk=$id ");
       $deals = array();
       foreach($query as $row){
           $expiredTs = $row['expired_time'];
           $createdTs = $row['created_time'];
           $message = $row['message'];
           $deal_type=$row['type_name'];
           array_push($deals, array('messsage'=>$message,'deal_type'=>$deal_type,'expiredTimestamp'=>$expiredTs,'createdTimestamp'=>$createdTs));
       }
       return $deals;
    }
}
