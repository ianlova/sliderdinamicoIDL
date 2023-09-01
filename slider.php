
<?php 
global $nombres_imagenes;

$nombres_imagenes = array();

function redimensionarImagen($imagen, $ancho_final){
        $rutaImagen = "imagenesFinales/".$imagen;
    //Extraemos algunos atributos de la imagen
        list($ancho_orig, $alto_orig, $nroTipo) = getimagesize($rutaImagen);

        //Creamos una variable imagen a partir de la imagen original segun el tipo
        switch ($nroTipo) {
            case 2: $img_original=imagecreatefromjpeg($rutaImagen);
                break;
            case 3: $img_original=imagecreatefrompng($rutaImagen);
                break;
            default:
                echo "tipo de archivo incorrecto";
                break;
        }

    //En base al Ancho_final calculamos el Alto_final que debe tener la imagen para //mantener el aspecto 
            $aspecto = $ancho_orig / $alto_orig;
            $alto_final = $ancho_final / $aspecto;

    //Creamos una imagen en blanco de tamaño $ancho_final  por $alto_final 
            $nueva_img = imageCreateTrueColor($ancho_final, $alto_final);

    //Copiamos $img_original sobre la imagen que acabamos de crear en blanco 
    imagecopyresampled($nueva_img, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho_orig, $alto_orig);

    //Se destruye variable $img_original para liberar memoria
        imagedestroy($img_original);

    //Definimos la calidad de la imagen final
        $calidad=70;
        
    //Definimos el nombre final de la imagen concatenando al nombre original
    //un identificador unico con la funcion time()
        $nombre_imagen = time()."-".$imagen;

        global $nombres_imagenes;

        array_push($nombres_imagenes, $nombre_imagen);

    //Se crea la imagen final en el directorio indicado
        imagejpeg($nueva_img,"imagenesFinales/".$nombre_imagen,$calidad);

    //retornamos el nombre de la nueva imagen
        return $nombre_imagen;}
?>
<?php                                 
function redimensionarRecortarImagen($imagen, $ancho_final, $alto_final, $desplazamiento) {
    // Tomemos de ejemplo una imagen original de 1024 x 768 y una imagen final deseada de 400 x 400 
    // Extraemos algunos atributos de la imagen
    $rutaImagen = "imagenesFinales/" . $imagen;
    list($ancho_orig, $alto_orig, $nroTipo) = getimagesize($rutaImagen);

    // Creamos una variable imagen a partir de la imagen original según el tipo
    switch ($nroTipo) {
        case 2: $img_original = imagecreatefromjpeg($rutaImagen);
            break;
        case 3: $img_original = imagecreatefrompng($rutaImagen);
            break;
        default:
            echo "tipo de archivo incorrecto";
            break;
    }

    // Calculamos los Ratios: proporción existente en entre dos magnitudes, en este caso la proporción entre los anchos(final y original) y entre los altos (final y original)
    $ratio_ancho = $ancho_final / $ancho_orig; // $ratio_ancho  
    $ratio_alto = $alto_final / $alto_orig; // $ratio_alto 

    // Obtenemos el máximo entre los dos ratios
    $ratio_max = max($ratio_ancho, $ratio_alto); //$ratio_max 

    // Utilizamos los ratios calculados para determinar un nuevo ancho y alto para la imagen
    $nuevo_ancho = $ancho_orig * $ratio_max; //$nuevo_ancho = 533.33
    $nuevo_alto = $alto_orig * $ratio_max; //$nuevo_alto = 400 (ya tenemos el valor final)

    // Calculamos los recortes a realizar
    $recorte_ancho = $nuevo_ancho - $ancho_final; //$recorte_ancho = 133.33
    $recorte_alto = $nuevo_alto - $alto_final; // $recorte_alto = 0

    // Ahora el desplazamiento 
    //'top-left': recortará la imagen arriba o a la izquierda
    //‘center’: se quedará con la parte central de la imagen
    //'bottom-right': recortará la imagen abajo o a la derecha
    // 0 >= $desplazamiento >= 1 (cualquier valor entre 0 y 1): desplazará la ventana de recorte hacia la derecha o abajo. Podemos interpretarlo como un porcentaje.

    if ($desplazamiento == 'center')
        $desplazamiento = 0.5;
    elseif ($desplazamiento == 'top-left')
        $desplazamiento = 0;
    elseif ($desplazamiento == 'bottom-right')
        $desplazamiento = 1;

    // Creamos una imagen en blanco de tamaño $ancho_final por $alto_final 
    $nueva_img = imageCreateTrueColor($ancho_final, $alto_final);

    // Copiamos $img_original sobre la imagen que acabamos de crear en blanco 

    // imagecopyresampled($nueva_img, $img_original, -133.33*0.5, -0*0.5, 0, 0, 400+133.33, 400+0, 1024, 768);

    // imagecopyresampled($nueva_img, $img_original, -66.66, 0, 0, 0, 533.33, 400, 1024, 768); 

    imagecopyresampled(
        $nueva_img,
        $img_original,
        -$recorte_ancho * $desplazamiento,
        -$recorte_alto * $desplazamiento,
        0,
        0,
        $ancho_final,
        $alto_final,
        $nuevo_ancho,
        $nuevo_alto
    );

    // Se destruye variable $img_original para liberar memoria
    imagedestroy($img_original);

    // Definimos la calidad de la imagen final
    $calidad = 70;

    // Definimos el nombre final de la imagen concatenando al nombre original un identificador único con la función time()
    $nombre_imagen = time() . "-" . $imagen;

    global $nombres_imagenes;

    array_push($nombres_imagenes, $nombre_imagen);

    // Se crea la imagen final en el directorio indicado
    imagejpeg($nueva_img, "imagenesFinales/" . $nombre_imagen, $calidad);

    // Retornamos el nombre de la nueva imagen
    return $nombre_imagen;
}
?>

<!-- y cuando llamo a las funciones lo hago así

$nombreImagen3 = redimensionarRecortarImagen("ir.png", 600, 798, "top-left");
$nombreImagen5 = redimensionarRecortarImagen("imagen.png", 700, 350, "top-left");

-->


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Slider Dinámico</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="esti.css">
    
</head>
<body>
    <?php
        if (is_uploaded_file($_FILES['imagenes']['tmp_name'][0])) {
            $canti= count($_FILES['imagenes']['name']);
                for ($i=0; $i < $canti; $i++) { 
                $dir_temporal=$_FILES['imagenes']['tmp_name'][$i];
                $nbr_archivo='imagen'.$i.'.png';
                $subir = move_uploaded_file($dir_temporal, 'imagenesFinales/'.$nbr_archivo);
                    $archivo='imagenesFinales/'.$nbr_archivo.'';
            }
     }
    $titulo=$_POST['titulo'];

    ?>
    <header>
    <h1>
        <?php
        echo''.$titulo.''
        ?>
    </h1>
    </header>
    <div class="container-all">
        <?php
        for ($i=0; $i < $canti; $i++) { 
        echo '
        <input type="radio" id="'.$i.'" name="image-slide" hidden>';
        } 
        ?>
            <div class="slide">                                
                <?php
                $nombreImagen1 = redimensionarImagen("imagen0.png", 600);
                $nombreImagen2 = redimensionarImagen("imagen1.png", 600);
                $nombreImagen3 = redimensionarRecortarImagen("imagen2.png", 600, 800, "top-left");
                $nombreImagen4 = redimensionarImagen("imagen3.png", 600);
                $nombreImagen5 = redimensionarRecortarImagen("imagen4.png", 1600, 800, "bottom-right");

                global $nombres_imagenes;
                for ($i=0; $i < $canti; $i++) { 
                    # code...
                    if($i==3){
                        echo'
                        <div class="item-slide">                           
                        <img class="margin" src="imagenesFinales/'.$nombres_imagenes[$i].'" alt="imagen1">;
                        </div>';    
                    }
                    else{                    
                    echo'
                    <div class="item-slide">                           
                        <img src="imagenesFinales/'.$nombres_imagenes[$i].'" alt="imagen1">;
                    </div>';
                    }
                }
                
                ?>
            </div> 
                <?php
                echo'
                <div class="pagination">';
                for ($i=0; $i < $canti; $i++) { 
                    echo'
                        <label class="pagination-item" for="'.$i.'">
                            <img src="imagenesFinales/imagen'.$i.'.png">
                        </label>
                <style>
                    input[id="'.$i.'"]:checked ~ .slide{
                        animation: none;
                        transform: translate3d(calc(-100%*'.$i.'),0,0);
                    }
                    input[id="'.$i.'"]:checked ~ .pagination .pagination-item[for="'.$i.'"]{
                        background: #fff;
                    }
                    @media (max-width: 750px) {
                     
                    input[id="'.$i.'"]:checked ~ .slide{
                        animation: none;
                        transform: translate3d(calc(-100%*'.$i.'),0,0);
                    }
                    input[id="'.$i.'"]:checked ~ .pagination .pagination-item[for="'.$i.'"]{
                        background: #fff;
                    }
                </style>
                    ';
                }
                echo'
                <style>
                .slide{
                    display:flex;
                    transform: translate3d(0,0,0);
                    transition: all 700ms;
                    animation-name: autoplay;
                    animation-duration: 10.5s;
                    animation-delay: 4s;
                    animation-direction: alternate;
                    animation-fill-mode: forwards;
                    animation-iteration-count: infinite;    
                    background-color: none;
                    background: none;
                    color: none;    
                }
                @keyframes autoplay {
                    0% {
                        transform: translateX(0);
                      }
                        ';
                        $conta=(100/$canti);
                        $contad=$conta;
                        $contador=0;
                        for ($i=1; $i <= 100 ; $i+$conta) { 
                            echo '
                            '.($contad).'% {
                                transform: translateX(calc(-'.($contador).'*600px));
                            }
                            ';
                            if (($contad+$conta)<=100 ) {
                            $contad+=$conta;
                            }else{
                                $i=101; 
                            echo '100% {
                                transform: translateX(calc(-'.($contador).'*600px));
                            }
                            ';
                            }

                            if (($contador+1)<$canti) {
                            $contador+=1;
                            }else{
                                $i==99;
                            }
                        }
                        echo'
                  
                }
                @media (max-width: 750px) {
                 
                    @keyframes autoplay {
                        
                        0% {
                            transform: translateX(0);
                          }';
                          $conta=(100/$canti);
                          $contad=$conta;
                          $contador=0;
                          for ($i=1; $i <= 100 ; $i+$conta) { 
                              echo '
                              '.($contad).'% {
                                  transform: translateX(calc(-'.($contador).'*350px));
                              }
                              ';
                              if (($contad+$conta)<=100 ) {
                              $contad+=$conta;
                              }else{
                                  $i=101; 
                              echo '100% {
                                  transform: translateX(calc(-'.($contador).'*350px));
                              }
                              ';
                              }
  
                              if (($contador+1)<$canti) {
                              $contador+=1;
                              }else{
                                  $i==99;
                              }
                          }
                          echo'
                        }
                </style>
                </div>
                ';    
                ?>
</body>
</html>