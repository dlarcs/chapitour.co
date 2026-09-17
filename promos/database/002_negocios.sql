INSERT IGNORE INTO cp_campanas (id,nombre) VALUES (1,'Chapinero te premia');
-- Los datos provienen de las páginas principales / ubicación del repositorio.
INSERT IGNORE INTO cp_negocios (id,slug,nombre,categoria,direccion,whatsapp,logo,pagina) VALUES
(1,'street-grill','Street Grill','Gastronomía','Carrera 9 #57-85','573143580355','gastronomia/streetgrill/img/logo.jpeg','gastronomia/streetgrill/index.php'),
(2,'capital-queer','Capital Queer','Bar','Carrera 9 #59-38','573007795016','bar/CapitalQueer/img/logoCapitalQueer.jpg','bar/CapitalQueer/index.php'),
(3,'jimar-factory','Jimar Factory','Juegos y billar','Calle 58 #13-93','573165180649','juegos/JimarFactory/img/logo.jpeg','juegos/JimarFactory/index.php'),
(4,'garage-disco-bar','Garage Disco Bar','Gastrobar','Calle 59 #9-39','573156175056','gastrobar/GarageDiscoBar/img/general11.jpg','gastrobar/GarageDiscoBar/index.php'),
(5,'pictogramas','Pictogramas Café Bar','Café bar','Calle 59 #13-20','573502835648','bar/Pictograma/img/logo.jpeg','bar/Pictograma/index.php'),
(6,'gran-chela','Gran&Chela Club','Bar y discoteca','Calle 59 #10-24','573224680419','bar/Gran&Chela_Club/img/logo.jpg','bar/Gran&Chela_Club/index.php');
-- No se activan descuentos sin conocer la oferta y condiciones aprobadas por cada aliado.
INSERT IGNORE INTO cp_promociones (negocio_id,titulo,condiciones,activa)
SELECT id,'Beneficio por configurar','Completar y aprobar las condiciones antes de activar.',0 FROM cp_negocios;
