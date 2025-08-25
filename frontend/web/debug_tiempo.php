<?php
// Script simple para debug en el frontend
use frontend\models\Equipo;

// Obtener algunos equipos de muestra
$equipos = Equipo::find()->limit(3)->all();

echo "<h2>DEBUG TIEMPO ACTIVO</h2>";

foreach ($equipos as $equipo) {
    echo "<div style='border: 1px solid #ccc; margin: 10px; padding: 10px;'>";
    echo "<strong>ID:</strong> " . $equipo->idEQUIPO . "<br>";
    echo "<strong>Fecha de emisión:</strong> " . ($equipo->EMISION_INVENTARIO ?: 'NULL') . "<br>";
    echo "<strong>Tipo de dato:</strong> " . gettype($equipo->EMISION_INVENTARIO) . "<br>";
    
    if (!empty($equipo->EMISION_INVENTARIO)) {
        try {
            $fechaEmision = new \DateTime($equipo->EMISION_INVENTARIO);
            $fechaActual = new \DateTime();
            
            echo "<strong>Fecha emisión procesada:</strong> " . $fechaEmision->format('Y-m-d H:i:s') . "<br>";
            echo "<strong>Fecha actual:</strong> " . $fechaActual->format('Y-m-d H:i:s') . "<br>";
            
            $diferencia = $fechaActual->diff($fechaEmision);
            
            echo "<strong>Diferencia object:</strong><br>";
            echo "&nbsp;&nbsp;- days: " . $diferencia->days . "<br>";
            echo "&nbsp;&nbsp;- years: " . $diferencia->y . "<br>";
            echo "&nbsp;&nbsp;- months: " . $diferencia->m . "<br>";
            echo "&nbsp;&nbsp;- days: " . $diferencia->d . "<br>";
            echo "&nbsp;&nbsp;- invert: " . $diferencia->invert . "<br>";
            
            echo "<strong>Días activo (método):</strong> " . $equipo->getDiasActivo() . "<br>";
            echo "<strong>Años activo (método):</strong> " . $equipo->getAnosActivo() . "<br>";
            
        } catch (\Exception $e) {
            echo "<strong>ERROR:</strong> " . $e->getMessage() . "<br>";
        }
    }
    
    echo "</div>";
}
?>
