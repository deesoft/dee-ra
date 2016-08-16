<?php

/**
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class m160201_050050_create_table_accounting extends \yii\db\Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%coa}}', [
            'id' => $this->primaryKey(),
            'parent_id' => $this->integer(),
            'code' => $this->string(20)->notNull(),
            'number' => $this->string(16)->notNull(),
            'name' => $this->string(64)->notNull(),
            'type' => $this->char(1)->notNull(),
            'balance' => $this->char(1)->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[parent_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%acc_periode}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(32)->notNull(),
            'date_from' => $this->date()->notNull(),
            'date_to' => $this->date()->notNull(),
            'status' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);
//
        $this->createTable('{{%entri_sheet}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(32)->notNull(),
            'name' => $this->string(128),
            'd_coa_id' => $this->integer()->notNull(),
            'k_coa_id' => $this->integer()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[d_coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            'FOREIGN KEY ([[k_coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%gl_header}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'date' => $this->date()->notNull(),
            'periode_id' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'reff_type' => $this->integer()->notNull(),
            'reff_id' => $this->integer(),
            'description' => $this->string()->notNull(),
            'status' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[periode_id]]) REFERENCES {{%acc_periode}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%gl_detail}}', [
            'id' => $this->primaryKey(),
            'header_id' => $this->integer()->notNull(),
            'coa_id' => $this->integer()->notNull(),
            'amount' => $this->float()->notNull(),
            // constrain
            'FOREIGN KEY ([[header_id]]) REFERENCES {{%gl_header}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%invoice}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'type' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'due_date' => $this->date()->notNull(),
            'vendor_id' => $this->integer()->notNull(),
            'reff_type' => $this->integer(),
            'reff_id' => $this->integer(),
            'status' => $this->integer(),
            'description' => $this->string(64),
            'value' => $this->float()->notNull(),
            'tax' => $this->string(64),
            'tax_value' => $this->float(),
            'paid' => $this->float(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%invoice_dtl}}', [
            'id' => $this->primaryKey(),
            'invoice_id' => $this->integer()->notNull(),
            'item' => $this->string(64)->notNull(),
            'item_id' => $this->integer(),
            'qty' => $this->float(),
            'value' => $this->float()->notNull(),
            // constrain
            'FOREIGN KEY ([[invoice_id]]) REFERENCES {{%invoice}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%payment_method}}', [
            'id' => $this->primaryKey(),
            'branch_id' => $this->integer()->notNull(),
            'method' => $this->string(32)->notNull(),
            'coa_id' => $this->integer()->notNull(),
            'potongan' => $this->float(),
            'potongan_coa_id' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'type' => $this->integer()->notNull(),
            'branch_id' => $this->integer()->notNull(),
            'vendor_id' => $this->integer()->notNull(),
            'date' => $this->date()->notNull(),
            'method' => $this->string(32)->notNull(),
            'coa_id' => $this->integer()->notNull(),
            'value' => $this->float()->notNull(),
            'potongan_coa_id' => $this->integer(),
            'potongan' => $this->float(),
            'status' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            'FOREIGN KEY ([[potongan_coa_id]]) REFERENCES {{%coa}} ([[id]]) ON DELETE RESTRICT ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%payment_dtl}}', [
            'id' => $this->primaryKey(),
            'payment_id' => $this->integer()->notNull(),
            'invoice_id' => $this->integer()->notNull(),
            'value' => $this->float()->notNull(),
            // constrain
            'FOREIGN KEY ([[payment_id]]) REFERENCES {{%payment}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[invoice_id]]) REFERENCES {{%invoice}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%payment_dtl}}');
        $this->dropTable('{{%payment}}');
        $this->dropTable('{{%payment_method}}');
        $this->dropTable('{{%invoice_dtl}}');
        $this->dropTable('{{%invoice}}');
        $this->dropTable('{{%gl_detail}}');
        $this->dropTable('{{%gl_header}}');
        $this->dropTable('{{%entri_sheet}}');
        $this->dropTable('{{%acc_periode}}');
        $this->dropTable('{{%coa}}');
    }
}
