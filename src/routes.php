<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->get('/[{name}]', function (Request $request, Response $response, array $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
$app->get('/api/business/location/{lat}/{lon}/{distance}', Controllers\BusinessController::class.':getBusinessBaseOnLocation');
$app->put('/api/deal/create/{bid}', Controllers\DealController::class.':createDeal');