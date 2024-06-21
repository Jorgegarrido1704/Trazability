<?php
// Crear una imagen en blanco de 400x400 píxeles
$width = 2400;
$height = 2400;
$image = imagecreatetruecolor($width, $height);

// Asignar colores
$white = imagecolorallocate($image, 255, 255, 255);
$black = imagecolorallocate($image, 0, 0, 0);

// Rellenar el fondo de blanco
imagefill($image, 0, 0, $white);

// Coordenadas para la línea vertical central
$x_vertical = $width / 2;
$y1_vertical = 50;
$y2_vertical = $height - 50;

// Dibujar la línea vertical central
imageline($image, $x_vertical, $y1_vertical, $x_vertical, $y2_vertical, $black);

// Coordenadas para la línea horizontal superior
$x1_horizontal_top = 50;
$x2_horizontal_top = $width - 50;
$y_horizontal_top = $y1_vertical;

// Dibujar la línea horizontal superior
imageline($image, $x1_horizontal_top, $y_horizontal_top, $x2_horizontal_top, $y_horizontal_top, $black);

// Coordenadas para la línea horizontal inferior
$x1_horizontal_bottom = 50;
$x2_horizontal_bottom = $width - 50;
$y_horizontal_bottom = $y2_vertical;

// Dibujar la línea horizontal inferior
imageline($image, $x1_horizontal_bottom, $y_horizontal_bottom, $x2_horizontal_bottom, $y_horizontal_bottom, $black);

// Dibujar las líneas verticales al final de cada línea horizontal
// Línea vertical al final de la línea horizontal superior
imageline($image, $x1_horizontal_top, $y_horizontal_top - 25, $x1_horizontal_top, $y_horizontal_top + 25, $black);
imageline($image, $x2_horizontal_top, $y_horizontal_top - 25, $x2_horizontal_top, $y_horizontal_top + 25, $black);

// Línea vertical al final de la línea horizontal inferior
imageline($image, $x1_horizontal_bottom, $y_horizontal_bottom - 25, $x1_horizontal_bottom, $y_horizontal_bottom + 25, $black);
imageline($image, $x2_horizontal_bottom, $y_horizontal_bottom - 25, $x2_horizontal_bottom, $y_horizontal_bottom + 25, $black);

// Enviar la cabecera de la imagen
header('Content-Type: image/png');

// Enviar la imagen al navegador
imagepng($image);

// Liberar memoria
imagedestroy($image);
?>
