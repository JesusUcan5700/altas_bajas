<?php
require 'vendor/autoload.php';
require 'common/config/bootstrap.php';
require 'frontend/config/bootstrap.php';

$config = yii\helpers\ArrayHelper::merge(
    require 'common/config/main.php',
    require 'frontend/config/main.php'
);

new yii\web\Application($config);

use frontend\models\Equipo;

echo "=== PROBANDO SISTEMA DE AUDITORÍA ===\n";

// Buscar un equipo para actualizar
$equipo = Equipo::findOne(2);
if ($equipo) {
    echo "📋 Equipo encontrado: {$equipo->MARCA} {$equipo->MODELO}\n";
    echo "🔍 Estado actual:\n";
    echo "   - Último editor: " . ($equipo->ultimo_editor ?? 'No definido') . "\n";
    echo "   - Fecha última edición: " . ($equipo->fecha_ultima_edicion ?? 'No definida') . "\n";
    
    // Actualizar el equipo
    $equipo->ultimo_editor = 'Juan Pérez';
    $equipo->MARCA = $equipo->MARCA . ' (Actualizado)';
    
    if ($equipo->save()) {
        echo "\n✅ Equipo actualizado correctamente\n";
        echo "📝 Nuevos datos:\n";
        echo "   - Último editor: {$equipo->ultimo_editor}\n";
        echo "   - Fecha última edición: {$equipo->fecha_ultima_edicion}\n";
        echo "   - Marca actualizada: {$equipo->MARCA}\n";
    } else {
        echo "\n❌ Error al actualizar equipo:\n";
        print_r($equipo->errors);
    }
} else {
    echo "❌ Equipo con ID 2 no encontrado\n";
}

echo "\n=== VERIFICAR ÚLTIMO EQUIPO EDITADO ===\n";
$ultimoEditado = Equipo::find()->orderBy('fecha_ultima_edicion DESC')->one();
if ($ultimoEditado) {
    echo "🆔 ID: {$ultimoEditado->idEQUIPO}\n";
    echo "🏷️  Equipo: {$ultimoEditado->MARCA} {$ultimoEditado->MODELO}\n";
    echo "👤 Último editor: {$ultimoEditado->ultimo_editor}\n";
    echo "📅 Fecha edición: {$ultimoEditado->fecha_ultima_edicion}\n";
}
?>
