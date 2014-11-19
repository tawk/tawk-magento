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

class Tawk_Widget_Block_Admin_Customization extends Mage_Adminhtml_Block_Template {
	const BASE_URL = 'https://plugins.tawk.to';

	private $model;
	private $widgets;

	protected function _construct() {

		$this->model = Mage::getModel('tawkwidget/widget');
		$this->model->load(0);

		$this->widgets = Mage::getModel('tawkwidget/widget')->getCollection();

		parent::_construct();

		$this->setTemplate('tawk/customization.phtml');
		$this->setFormAction(Mage::getUrl('*/*/savewidget'));
	}

	public function getIframeUrl() {
		return $this->getBaseUrl()
			.'/generic/widgets'
			.'?parentDomain='.Mage::getBaseUrl (Mage_Core_Model_Store::URL_TYPE_WEB)
			.'&selectType=singleIdSelect'
			.'&selectText=Store';
	}

	public function getHierarchy() {
		$websites = Mage::app()->getWebsites();

		$h = array();

		$h[] = array(
			'id'      => 'global',
			'name'    => 'Global',
			'childs'  => array(),
			'current' => $this->getCurrentValuesFor('global')
		);

		foreach ($websites as $website) {
			$parsed = array();

			$parsed['id']      = $website->getId();
			$parsed['name']    = $website->getName();
			$parsed['childs']  = $this->parseGroups($website->getGroups());
			$parsed['current'] = $this->getCurrentValuesFor($website->getId());

			$h[] = $parsed;
		}

		return $h;
	}

	private function parseGroups($groups) {
		$return = array();

		foreach ($groups as $group) {
			$parsed = array();

			$parsed['id']      = $group->getWebsiteId().'_'.$group->getId();
			$parsed['name']    = $group->getName();
			$parsed['childs']  = $this->parseStores($group->getStores());
			$parsed['current'] = $this->getCurrentValuesFor($parsed['id']);

			$return[] = $parsed;
		}

		return $return;
	}

	private function parseStores($stores) {
		$return = array();

		foreach ($stores as $store) {
			$parsed = array();

			$parsed['id']      = $store->getWebsiteId().'_'.$store->getGroupId().'_'.$store->getId();
			$parsed['name']    = $store->getName();
			$parsed['childs']  = array();
			$parsed['current'] = $this->getCurrentValuesFor($parsed['id']);

			$return[] = $parsed;
		}

		return $return;
	}

	private function getCurrentValuesFor($id) {

		foreach ($this->widgets as $widget) {
			if($widget->getForStoreId() === $id) {
				return array(
					'pageId'   => $widget->getPageId(),
					'widgetId' => $widget->getWidgetId()
				);
			}
		}

		return array();
	}

	public function getBaseUrl() {
		return self::BASE_URL;
	}

	public function getRemoveUrl() {
		return Mage::helper('adminhtml')->getUrl('tawkwidget/admin/removewidget');
	}
}