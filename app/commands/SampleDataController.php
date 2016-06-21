<?php

namespace app\commands;

use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use yii\helpers\ArrayHelper;
use yii\db\Query;

/**
 * Description of SampleDataController
 *
 * @author Misbahul D Munir <misbahuldmunir@gmail.com>
 * @since 1.0
 */
class SampleDataController extends Controller
{
    /**
     * @var string the default command action.
     */
    public $defaultAction = 'create';

    /**
     * Create sample data
     */
    public function actionCreate()
    {
        if (!Console::confirm('Are you sure you want to create sample data. Old data will be lose')) {
            return self::EXIT_CODE_NORMAL;
        }

        $command = Yii::$app->db->createCommand();
        $schema = Yii::$app->db->schema;
        $sampleDir = __DIR__ . '/samples';

        // TRUNCATE TABLE
        $command->delete('{{%product_stock}}')->execute();
        $command->delete('{{%gl_detail}}')->execute();
        $command->delete('{{%gl_header}}')->execute();
        $command->delete('{{%goods_movement_dtl}}')->execute();
        $command->delete('{{%goods_movement}}')->execute();

        $command->delete('{{%sales_dtl}}')->execute();
        $command->delete('{{%sales}}')->execute();

        $command->delete('{{%invoice_dtl}}')->execute();
        $command->delete('{{%invoice}}')->execute();

        $command->delete('{{%warehouse}}')->execute();
        $command->delete('{{%branch}}')->execute();
        $command->delete('{{%orgn}}')->execute();
        $command->resetSequence('{{%warehouse}}')->execute();
        $command->resetSequence('{{%branch}}')->execute();

        $command->delete('{{%vendor}}')->execute();
        $command->resetSequence('{{%vendor}}')->execute();

        $command->delete('{{%product_detail}}')->execute();
        $command->resetSequence('{{%product_detail}}')->execute();

        $command->delete('{{%cogs}}')->execute();
        $command->delete('{{%price}}')->execute();
        $command->delete('{{%price_category}}')->execute();
        $command->delete('{{%product_code}}')->execute();
        $command->delete('{{%product}}')->execute();
        $command->delete('{{%product_group}}')->execute();
        $command->delete('{{%category}}')->execute();

        $command->delete('{{%entri_sheet}}')->execute();
        $command->delete('{{%coa}}')->execute();
        $command->resetSequence('{{%coa}}')->execute();
        $command->delete('{{%payment_method}}')->execute();

        // orgn & branch & whs
        $command->insert('{{%orgn}}', [
            'id' => 1,
            'code' => '101.0001',
            'name' => 'Dee Corp.',
            'created_at' => time(),
            'created_by' => 1
        ])->execute();
        $command->resetSequence('{{%orgn}}')->execute();
        $rows = require $sampleDir . '/branch.php';
        $total = count($rows);
        echo "\ninsert table {{%branch}} and {{%warehouse}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $whs = $row['whs'];
            $row = [
                'orgn_id' => 1,
                'code' => sprintf('102.001.%04d', $i + 1),
                'name' => $row[0],
                'created_at' => time(),
                'created_by' => 1,
            ];
            $pks = $schema->insert('{{%branch}}', $row);
            foreach ($whs as $j => $row) {
                $row = [
                    'branch_id' => $pks['id'],
                    'code' => sprintf('103.%03d.%04d', $pks['id'], $j + 1),
                    'type' => $row[0],
                    'name' => $row[1],
                    'created_at' => time(),
                    'created_by' => 1,
                ];
                $command->insert('{{%warehouse}}', $row)->execute();
            }
            Console::updateProgress($i + 1, $total);
        }

        $command->resetSequence('{{%branch}}')->execute();
        $command->resetSequence('{{%warehouse}}')->execute();
        Console::endProgress();

        // vendor
        $rows = require $sampleDir . '/vendor.php';
        $total = count($rows);
        echo "\ninsert table {{%vendor}}\n";
        Console::startProgress(0, $total);
        $nums = [];
        foreach ($rows as $i => $row) {
            $row = $this->toAssoc($row, ['id', 'type', 'name', 'contact_name', 'contact_number']);
            if (isset($nums[$row['type']])) {
                $nums[$row['type']] ++;
            } else {
                $nums[$row['type']] = 1;
            }
            $row['code'] = sprintf('%3d.%06d.%s', 110 + $row['type'], $nums[$row['type']], $this->toCode($row['name']));
            $row['status'] = 10;
            $command->insert('{{%vendor}}', $row)->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%vendor}}')->execute();
        Console::endProgress();

        // product category
        $rows = require $sampleDir . '/category.php';
        $total = count($rows);
        echo "\ninsert table {{%category}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $row = $this->toAssoc($row, ['id', 'name']);
            $row['code'] = sprintf('131.%04d', $row['id']);
            $command->insert('{{%category}}', $row)->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%category}}')->execute();
        Console::endProgress();

        // product group
        $rows = require $sampleDir . '/product_group.php';
        $total = count($rows);
        echo "\ninsert table {{%product_group}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $row = $this->toAssoc($row, ['id', 'name']);
            $row['code'] = sprintf('132.%04d', $row['id']);
            $command->insert('{{%product_group}}', $row)->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%product_group}}')->execute();
        Console::endProgress();

        // price category
        $rows = require $sampleDir . '/price_category.php';
        $total = count($rows);
        echo "\ninsert table {{%price_category}}\n";
        Console::startProgress(0, $total);
        $pc_ids = [];
        foreach ($rows as $i => $row) {
            $row = $this->toAssoc($row, ['id', 'name']);
            $pc_ids[] = $row['id'];
            $row['code'] = sprintf('133.%04d', $row['id']);
            $command->insert('{{%price_category}}', $row)->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%price_category}}')->execute();
        Console::endProgress();

        // product
        $rows = require $sampleDir . '/product.php';
        $total = count($rows);
        echo "\ninsert table {{%product}}\n";
        Console::startProgress(0, $total);
        $pdtl = 1;
        foreach ($rows as $i => $row) {
            $id = $row[0];
            $row = $this->toAssoc($row, ['id', 'group_id', 'category_id', 'code', 'name', 'status']);
            $row['code'] = sprintf('141.%04d', $id);
            $command->insert('{{%product}}', $row)->execute();
            $batch = [
                [$pdtl, $id, sprintf('142.%04d.01', $id), $row['name'], 'Pcs', 1],
                [$pdtl + 1, $id, sprintf('142.%04d.02', $id), $row['name'] . '(Dzn)', 'Dzn', 12],
            ];
            $command->batchInsert('{{%product_detail}}', ['id', 'product_id', 'code', 'name', 'uom', 'isi'], $batch)->execute();
            $price = mt_rand(95, 150) * 1000;
            $batch = [];
            foreach ($pc_ids as $pc_id) {
                $batch[] = [$pdtl, $pc_id, $price - $pc_id * 3000];
                $batch[] = [$pdtl + 1, $pc_id, ($price - $pc_id * 3000) * 11];
            }
            $command->batchInsert('{{%price}}', ['item_id', 'category_id', 'price'], $batch)->execute();
            $command->insert('{{%cogs}}', [
                'product_id' => $row['id'],
                'cogs' => $price * 0.65,
                'last_purchase_price' => $price - 20000,
                'created_at' => time(),
                'created_by' => 1,
            ])->execute();
            $pdtl += 2;
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%product}}')->execute();
        $command->resetSequence('{{%product_detail}}')->execute();
        Console::endProgress();
        // coa
        $rows = require $sampleDir . '/coa.php';
        $total = count($rows);
        echo "\ninsert table {{%coa}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $this->insertCoa($schema, $row);
            Console::updateProgress($i + 1, $total);
        }
        Console::endProgress();

        $coa_map = ArrayHelper::map((new Query())
                    ->select(['id', 'number'])
                    ->from('{{%coa}}')
                    ->all(), 'number', 'id');

        // entrisheet
        $rows = require $sampleDir . '/entri_sheet.php';
        $total = count($rows);
        echo "\ninsert table {{%entri_sheet}}\n";
        Console::startProgress(0, $total);
        foreach ($rows as $i => $row) {
            $row = $this->toAssoc($row, ['id', 'code', 'name', 'd_coa_id', 'k_coa_id']);
            $row['code'] = '172.' . $row['code'];
            $row['d_coa_id'] = $coa_map[$row['d_coa_id']];
            $row['k_coa_id'] = $coa_map[$row['k_coa_id']];
            $command->insert('{{%entri_sheet}}', $row)->execute();
            Console::updateProgress($i + 1, $total);
        }
        $command->resetSequence('{{%entri_sheet}}')->execute();
        Console::endProgress();

        // payment method
        $rows = require $sampleDir . '/payment_method.php';
        $ids = (new Query())->select('id')->from('{{%branch}}')->column();
        $total = count($rows) * count($ids);
        echo "\ninsert table {{%payment_method}}\n";
        Console::startProgress(0, $total);
        $i = 1;
        foreach ($rows as $row) {
            $row = $this->toAssoc($row, ['method', 'coa_id', 'potongan', 'potongan_coa_id']);
            $row['coa_id'] = $coa_map[$row['coa_id']];
            if (isset($row['potongan_coa_id'])) {
                $row['potongan_coa_id'] = $coa_map[$row['potongan_coa_id']];
            }
            foreach ($ids as $id) {
                $row['branch_id'] = $id;
                $command->insert('{{%payment_method}}', $row)->execute();
                Console::updateProgress($i++, $total);
            }
        }
        Console::endProgress();
    }

    protected function toAssoc($array, $fields, $time = true)
    {
        $result = [];
        foreach ($fields as $i => $field) {
            $result[$field] = $array[$i];
        }
        if ($time) {
            return array_merge([
                'created_at' => time(),
                'created_by' => 1,
                ], $result);
        }
        return $result;
    }

    protected function toCode($name, $length = 6)
    {
        $name = strtoupper($name);
        $s = '';
        for ($i = 0; $i < strlen($name); $i++) {
            if ($name[$i] >= 'A' && $name[$i] <= 'Z') {
                $s .= $name[$i];
            }
        }
        return substr($s, 0, $length);
    }

    /**
     *
     * @param \yii\db\Schema $schema
     * @param type $row
     * @param type $parent
     */
    protected function insertCoa($schema, $row, $parent = null)
    {
        if (isset($row['items'])) {
            $items = $row['items'];
            unset($row['items']);
        }
        $row = $this->toAssoc($row, ['number', 'name', 'type', 'balance']);
        $row['code'] = '171.' . $row['number'];
        $row['parent_id'] = $parent;
        $primary = $schema->insert('{{%coa}}', $row);
        if (isset($items)) {
            foreach ($items as $item) {
                $this->insertCoa($schema, $item, $primary['id']);
            }
        }
    }

    public function actionStockOpname($warehouse)
    {
        $query = (new Query())
            ->select(['p.code', 's.qty'])
            ->from(['p' => '{{%product}}'])
            ->innerJoin(['s' => '{{%product_stock}}'], '[[p.id]]=[[s.product_id]]')
            ->where(['s.warehouse_id' => $warehouse]);

        $codes = [];
        mt_srand(time());
        foreach ($query->all() as $row) {
            $r = mt_rand(0, 25);
            if ($r == 5) {
                $row['qty'] ++;
            } elseif ($r == 6 && $row['qty'] > 2) {
                $row['qty'] --;
            }
            for ($i = 0; $i < $row['qty']; $i++) {
                $codes[] = $row['code'];
            }
        }
        $file = \Yii::getAlias('@runtime/so-' . time() . '.txt');
        file_put_contents($file, implode("\n", $codes));
        echo "Done... '$file' created\n";
    }
}
