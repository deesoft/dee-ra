<?php

/**
 * 
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>  
 * @since 3.0
 */
class m160201_050010_create_table_master extends \yii\db\Migration
{

    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%orgn}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%branch}}', [
            'id' => $this->primaryKey(),
            'orgn_id' => $this->integer()->notNull(),
            'code' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[orgn_id]]) REFERENCES {{%orgn}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%warehouse}}', [
            'id' => $this->primaryKey(),
            'branch_id' => $this->integer()->notNull(),
            'type' => $this->integer()->notNull(),
            'code' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[branch_id]]) REFERENCES {{%branch}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%product_group}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'group_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'name' => $this->string(64)->notNull(),
            'status' => $this->integer()->notNull(),
            'stockable' => $this->boolean()->defaultValue(true),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[group_id]]) REFERENCES {{%product_group}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[category_id]]) REFERENCES {{%category}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%product_detail}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'product_id' => $this->integer()->notNull(),
            'barcode' => $this->string(13),
            'name' => $this->string(64)->notNull(),
            'uom' => $this->string(32)->notNull(),
            'volume' => $this->integer()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%product_code}}', [
            'barcode' => $this->string(13),
            'item_id' => $this->integer()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'PRIMARY KEY ([[barcode]])',
            'FOREIGN KEY ([[item_id]]) REFERENCES {{%product_detail}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%product_stock}}', [
            'id' => $this->primaryKey(),
            'warehouse_id' => $this->integer()->notNull(),
            'product_id' => $this->integer()->notNull(),
            'qty' => $this->integer()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[warehouse_id]]) REFERENCES {{%warehouse}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%vendor}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'type' => $this->integer()->notNull(),
            'name' => $this->string(64)->notNull(),
            'contact_name' => $this->string(64),
            'contact_number' => $this->string(64),
            'status' => $this->integer()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%product_vendor}}', [
            'id' => $this->primaryKey(),
            'product_id' => $this->integer(),
            'vendor_id' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[vendor_id]]) REFERENCES {{%vendor}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%vendor_detail}}', [
            'id' => $this->integer(),
            'distric_id' => $this->integer(),
            'addr1' => $this->string(128),
            'addr2' => $this->string(128),
            'latitude' => $this->float(),
            'longtitude' => $this->float(),
            'kab_id' => $this->integer(),
            'kec_id' => $this->integer(),
            'kel_id' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'PRIMARY KEY ([[id]])',
            'FOREIGN KEY ([[id]]) REFERENCES {{%vendor}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%price_category}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(20)->notNull()->unique(),
            'name' => $this->string(64)->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            ], $tableOptions);

        $this->createTable('{{%price}}', [
            'id' => $this->primaryKey(),
            'item_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'price' => $this->float()->notNull(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'FOREIGN KEY ([[item_id]]) REFERENCES {{%product_detail}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            'FOREIGN KEY ([[category_id]]) REFERENCES {{%price_category}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%cogs}}', [
            'product_id' => $this->integer()->notNull(),
            'cogs' => $this->float()->notNull(),
            'last_purchase_price' => $this->float(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'PRIMARY KEY ([[product_id]])',
            'FOREIGN KEY ([[product_id]]) REFERENCES {{%product}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);

        $this->createTable('{{%draft}}', [
            'id' => $this->primaryKey(),
            'url' => $this->string()->notNull(),
            'data' => $this->binary(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            ], $tableOptions);

        $this->createTable('{{%user_to_branch}}', [
            'user_id' => $this->integer(),
            'branch_id' => $this->integer(),
            // history column
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            // constrain
            'PRIMARY KEY ([[user_id]], [[branch_id]])',
            'FOREIGN KEY ([[branch_id]]) REFERENCES {{%branch}} ([[id]]) ON DELETE CASCADE ON UPDATE CASCADE',
            ], $tableOptions);
    }

    public function safeDown()
    {
        $this->dropTable('{{%user_to_branch}}');
        $this->dropTable('{{%draft}}');
        $this->dropTable('{{%cogs}}');
        $this->dropTable('{{%price}}');
        $this->dropTable('{{%price_category}}');
        $this->dropTable('{{%product_vendor}}');
        $this->dropTable('{{%product_stock}}');
        $this->dropTable('{{%product_code}}');
        $this->dropTable('{{%product_detail}}');
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%product_group}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%vendor_detail}}');
        $this->dropTable('{{%vendor}}');
        $this->dropTable('{{%warehouse}}');
        $this->dropTable('{{%branch}}');
        $this->dropTable('{{%orgn}}');
    }
}
