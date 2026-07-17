<?php

/**
 *
 */
class UserClick
{

  private PDO $db;
  private $direccion_pagina = "";
  private $fecha_hora = "";

    public function __construct(Database $connection){

    $this->db = $connection->getConnection();

  }
  public function setDireccionPagina($direccion_pagina){$this->direccion_pagina = $direccion_pagina;
}

  public function setFechaHora($fecha_hora){$this->fecha_hora = $fecha_hora;}

    public function createclick(){
      // echo json_encode($this->direccion_pagina.$this->fecha_hora);exit;

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
