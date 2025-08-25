<?php

use yii\db\Migration;

/**
 * Class m250820_183315_add_audit_fields_to_baterias
 */
class m250820_183315_add_audit_fields_to_baterias extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        // Agregar campos de auditoría a la tabla baterias
        $this->addColumn('baterias', 'fecha_creacion', $this->timestamp()->null());
        $this->addColumn('baterias', 'fecha_ultima_edicion', $this->timestamp()->null());
        $this->addColumn('baterias', 'ultimo_editor', $this->string(100)->null());
        
        // Actualizar registros existentes con valores por defecto
        $this->update('baterias', [
            'fecha_creacion' => new \yii\db\Expression('NOW()'),
            'fecha_ultima_edicion' => new \yii\db\Expression('NOW()'),
            'ultimo_editor' => 'Sistema'
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // Eliminar campos de auditoría
        $this->dropColumn('baterias', 'ultimo_editor');
        $this->dropColumn('baterias', 'fecha_ultima_edicion');
        $this->dropColumn('baterias', 'fecha_creacion');
    }
}
