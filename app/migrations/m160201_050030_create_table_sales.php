<?php

/**
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class m160201_050030_create_table_sales extends \yii\db\Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%sales}}', [
            'id' => $this->primaryKey(),
            'type' => $this->integer()->notNull(),
            'number' => $this->string(20)->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'vendor_id' => $this->integer(),
            'date' => $this->date()->notNull(),
            'value' => $this->float()->notNull(),
            'discount' => $this->float(),
            'status' => $this->integer()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            ], $tableOptions);

        $this->createTable('{{%sales_dtl}}', [
            'id' => $this->primaryKey(),
            'sales_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'qty' => $this->float()->notNull(),
            'price' => $this->float()->notNull(),
            'cogs' => $this->float()->notNull(),
            'discount' => $this->float(),
            'tax' => $this->float(),
            'extra' => $this->string(),
            // constrain
            'FOREIGN KEY ([[sales_id]]) REFERENCES {{%sales}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%sales_dtl}}');
        $this->dropTable('{{%sales}}');
    }
}
