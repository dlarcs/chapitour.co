<?php
declare(strict_types=1);
if(realpath($_SERVER['SCRIPT_FILENAME']??'')===__FILE__){http_response_code(404);exit;}

// Known website pages are presentation metadata, never approval or commercial conditions.
final class ChapitourCatalog
{
    private const PAGES=[
        'street-grill'=>['path'=>'gastronomia/streetgrill/index.php','image'=>'gastronomia/streetgrill/img/logo.jpeg','category'=>'Gastronomía'],
        'capital-queer'=>['path'=>'bar/CapitalQueer/index.php','image'=>'bar/CapitalQueer/img/logoCapitalQueer.jpg','category'=>'Bar'],
        'gran-chela'=>['path'=>'bar/Gran&Chela_Club/index.php','image'=>'bar/Gran&Chela_Club/img/logo.jpg','category'=>'Bar y discoteca'],
        'garage-disco-bar'=>['path'=>'gastrobar/GarageDiscoBar/index.php','image'=>'gastrobar/GarageDiscoBar/img/general11.jpg','category'=>'Gastrobar'],
        'pictogramas'=>['path'=>'bar/Pictograma/index.php','image'=>'bar/Pictograma/img/logo.jpeg','category'=>'Café bar'],
        'jimar-factory'=>['path'=>'juegos/JimarFactory/index.php','image'=>'juegos/JimarFactory/img/logo.jpeg','category'=>'Juegos y billar'],
    ];
    public static function key(string $slug,string $name): ?string {
        if(isset(self::PAGES[$slug]))return $slug;
        $name=preg_replace('/[^a-z0-9]/','',strtr(mb_strtolower($name),['á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u']));
        $aliases=['streetgrill'=>'street-grill','capitalqueer'=>'capital-queer','granchela'=>'gran-chela','granchelaclub'=>'gran-chela','garagediscobar'=>'garage-disco-bar','garagegastrobar'=>'garage-disco-bar','pictogramas'=>'pictogramas','pictogramascafebar'=>'pictogramas','jimarfactory'=>'jimar-factory','jimarfactorychapinero'=>'jimar-factory'];
        return $aliases[$name]??null;
    }
    public static function page(?string $key): array { return self::PAGES[$key]??['path'=>'','image'=>'','category'=>'Aliado de Chapitour']; }
}
