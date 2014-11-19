<?php

/**
 * Tawk.to
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magentocommerce.com so we can send you a copy immediately.
 *
 * @copyright   Copyright (c) 2014 Tawk.to
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

$installer = $this;
$installer->startSetup();

$installer->run("
    CREATE TABLE `{$installer->getTable('tawkwidget/widget')}` (
      `id` int(10) NOT NULL auto_increment,
      `for_store_id` varchar(50),
      `page_id` varchar(50),
      `widget_id` varchar(50),
      PRIMARY KEY  (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

	CREATE INDEX `{$installer->getTable('tawkwidget/widget')}_for_store_id_index` ON `{$installer->getTable('tawkwidget/widget')}` (`for_store_id`);
");

$installer->endSetup();