<?php

use Slim\Http\Request;
use Slim\Http\Response;

// Routes

$app->get('/taxon/{id}', function (Request $request, Response $response, array $args) {
    $id = $request->getAttribute('id'); // TODO: data security - does this sanitize the string?
    $this->logger->info("Route /taxon/$id");

    require_once "taxon_model.php";
    $taxon = new Taxon($this->get('db'));
    $taxonData = $taxon->fetchTaxon($id);
    
    
    header('Content-Type: application/json');
    return json_encode($taxonData, JSON_HEX_QUOT | JSON_HEX_TAG); // Converts " < and >"


//    return "Taxon id is " . $id; // debug
// id 13001562 = Mus musculus

//    $db = $this->get('db');

    /*
    while($row = $PDOStatement->fetch()) {
        $data[] = $row;
    }
    */

//    print_r ($data);

    // Render index view
//    return $this->renderer->render($response, 'index.phtml', $args);
});
