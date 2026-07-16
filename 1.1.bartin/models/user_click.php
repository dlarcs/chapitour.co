<?php

/**
 *
 */
class UserClick
{
  private PDO $db;
  private $direccionPagina = "";
  private $fechaHora = "";

    public function __construct(Database $connection){
    $this->db = $connection->getConnection();
  }
  public function setdireccionPagina($direccionPagina){$this->direccionPagina = $direccionPagina;}
  public function setfechaHora($fechaHora){$this->fechaHora = $fechaHora;}

    public function createclick(){

      try {

        $sql = $this->db->prepare("
        INSERT INTO registro_clicks_whatsapp (direccion_pagina, fecha_hora)
        Values (:direccion_pagina, :fecha_hora)
        ");

        $sql->bindParam(':direccion_pagina', $this->direccion_pagina, PDO::PARAM_STR);
        $sql->bindParam(':fecha_hora', $this->fecha_hora, PDO::PARAM_STR);

        $sql->execute();
      } catch (\Exception $e) {

      }


    }

  }

 ?>
