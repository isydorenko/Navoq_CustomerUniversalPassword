<?php
/**
 * Installation of CustomerUniversalPassword module tables
 */
/** @var $install Navoq_CustomerUniversalPassword_Model_Resource_Setup */
$installer = $this;

$nonceTable = $installer->getTable('navoq_customeruniversalpassword/nonce');
$customerTable = $installer->getTable('customer_entity');

$adapter = $installer->getConnection();

$table = $adapter->newTable($nonceTable);

// Add columns
$table->addColumn('nonce_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
    'identity' => true,
    'unsigned' => true,
    'nullable' => false,
    'primary'  => true
));
$table->addColumn('nonce', Varien_Db_Ddl_Table::TYPE_VARCHAR, 32, array(
    'nullable' => true,
    'default'  => null,
));
$table->addColumn('timestamp', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
    'unsigned' => true,
    'nullable' => true,
    'default'  => null,
));
$table->addColumn('customer_id', Varien_Db_Ddl_Table::TYPE_INTEGER, 10, array(
    'unsigned' => true,
    'nullable' => false,
));

// Add indexes
$table->addIndex(
    $installer->getIdxName(
        $nonceTable,
        array('customer_id'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    'customer_id'
);
$table->addIndex(
    $installer->getIdxName(
        $nonceTable,
        array('nonce'),
        Varien_Db_Adapter_Interface::INDEX_TYPE_UNIQUE
    ),
    'nonce'
);
$table->addForeignKey(
    $installer->getFkName($nonceTable, 'customer_id', $customerTable, 'entity_id'),
    'customer_id',
    $customerTable,
    'entity_id',
    Varien_Db_Ddl_Table::ACTION_CASCADE,
    Varien_Db_Ddl_Table::ACTION_CASCADE
);

$adapter->createTable($table);
$installer->endSetup();
