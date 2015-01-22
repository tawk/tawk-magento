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
 * to support@tawk.to so we can send you a copy immediately.
 *
 * @copyright   Copyright (c) 2014 Tawk.to
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

class Tawk_Widget_Block_Embed extends Mage_Core_Block_Template {

	const TAWK_EMBED_URL = 'https://embed.tawk.to';

	private $model;

	protected function _construct() {
		$this->model = $this->getWidgetModel();

		parent::_construct();

		$this->setTemplate('tawk/embed.phtml');
	}

	private function getWidgetModel() {
		$store = Mage::app()->getStore();

		$storeId   = $store->getId();
		$groupId   = $store->getGroup()->getId();
		$websiteId = $store->getWebsite()->getId();

		//order in which we select widget
		$ids = array($websiteId.'_'.$groupId.'_'.$storeId, $websiteId.'_'.$groupId, $websiteId, 'global');

		foreach ($ids as $id) {
			$model = Mage::getModel('tawkwidget/widget')->loadByForStoreId($id);

			if($model->hasId()) {
				return $model;
			}
		}

		return null;
	}

	protected function _toHtml() {
		if(is_null($this->model)) { //if we couldn't match any of the widgets
			return '';
		}

		return parent::_toHtml();
	}

	public function getEmbedUrl() {
		return self::TAWK_EMBED_URL.'/'.$this->model->getPageId().'/'.$this->model->getWidgetId();
	}
}