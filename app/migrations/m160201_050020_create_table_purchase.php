<?php

/**
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class m160201_050020_create_table_purchase extends \yii\db\Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%purchase}}', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'number' => $this->string(20)->notNull(),
            'vendor_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'value' => $this->float()->notNull(),
            'discount' => $this->float(),
            'status' => $this->integer()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%purchase_dtl}}', [
            'id' => $this->primaryKey(),
            'purchase_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'qty' => $this->float()->notNull(),
            'price' => $this->float()->notNull(),
            'discount' => $this->float(),
            'extra' => $this->string(),
            // constrain
            'FOREIGN KEY ([[purchase_id]]) REFERENCES {{%purchase}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%purchase_dtl}}');
        $this->dropTable('{{%purchase}}');
    }
}
