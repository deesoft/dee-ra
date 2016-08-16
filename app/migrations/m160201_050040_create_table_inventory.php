<?php

/**
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class m160201_050040_create_table_inventory extends \yii\db\Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%transfer}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'branch_dest_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'status' => $this->integer(),
            'description' => $this->string(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%transfer_dtl}}', [
            'id' => $this->primaryKey(),
            'transfer_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'qty' => $this->float(),
            // constrain
            'FOREIGN KEY ([[transfer_id]]) REFERENCES {{%transfer}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%stock_opname}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'warehouse_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'status' => $this->integer(),
            'description' => $this->string(),
            'operator' => $this->string(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%stock_opname_dtl}}', [
            'id' => $this->primaryKey(),
            'opname_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'qty' => $this->float()->notNull(),
            // constrain
            'FOREIGN KEY ([[opname_id]]) REFERENCES {{%stock_opname}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%stock_adjustment}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'warehouse_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'reff_id' => $this->integer(),
            'description' => $this->string(),
            'status' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%stock_adjustment_dtl}}', [
            'id' => $this->primaryKey(),
            'adjustment_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'qty' => $this->float()->notNull(),
            'value' => $this->float()->notNull(),
            // constrain
            'FOREIGN KEY ([[adjustment_id]]) REFERENCES {{%stock_adjustment}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%goods_movement}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'type' => $this->integer()->notNull(),
            'warehouse_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'reff_type' => $this->integer(),
            'reff_id' => $this->integer(),
            'vendor_id' => $this->integer(),
            'description' => $this->string(),
            'extra_data' => $this->string(),
            'status' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%goods_movement_dtl}}', [
            'id' => $this->primaryKey(),
            'movement_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'reff_id' => $this->integer(),
            'qty' => $this->float()->notNull(),
            'cogs' => $this->float()->notNull(),
            'value' => $this->float(),
            // constrain
            'FOREIGN KEY ([[movement_id]]) REFERENCES {{%goods_movement}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%product_stock_history}}', [
            'id' => $this->primaryKey(),
            'warehouse_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'qty_movement' => $this->float()->notNull(),
            'qty' => $this->float()->notNull(),
            'movement_id' => $this->integer(),
            ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%product_stock_history}}');

        $this->dropTable('{{%goods_movement_dtl}}');
        $this->dropTable('{{%goods_movement}}');

        $this->dropTable('{{%stock_adjustment_dtl}}');
        $this->dropTable('{{%stock_adjustment}}');

        $this->dropTable('{{%stock_opname_dtl}}');
        $this->dropTable('{{%stock_opname}}');

        $this->dropTable('{{%transfer_dtl}}');
        $this->dropTable('{{%transfer}}');
    }
}
