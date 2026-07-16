<?php
header('Content-Type: application/json; charset=utf-8');

include "../../1.1.bartin/controller/config/database.php";

include "../../1.1.bartin/models/user_click.php";

/**
 *
 */
class ClassClick
{
  function HandleClick(){
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    switch ($data['action']) {
      case 'click':

        $this->click($data);
        break;


      default:
        echo json_encode([
          "success" => false,
          "message" => "Acción no reconocida"
        ]);
        break;

    }
  }

  private function click($data){

    $connection = new Database();
    $user = new UserClick($connection);
    echo json_encode("hola nosotros");exit;

    $fecha_hora = date("Y-m-d H:i:s");
    $user->setdireccionPagina($data['url_page']??'');
    $user->setfechaHora($fecha_hora);

    $response = $user->createclick();
    echo json_encode([
      "success" => true,
      "message" => $response
    ]);

  }



}



$clickClass = new ClassClick();
$clickClass->HandleClick();



 ?>
