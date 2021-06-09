<?php

namespace nnwebSmartsuppLiveChat;

use Shopware\Components\Plugin\Context\ActivateContext;
use Shopware\Components\Plugin\Context\DeactivateContext;
use Shopware\Components\Plugin\Context\InstallContext;
use Shopware\Components\Plugin\Context\UpdateContext;
use Shopware\Models\Shop\Shop;

class nnwebSmartsuppLiveChat extends \Shopware\Components\Plugin {

	public static function getSubscribedEvents() {
		return [
			'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch' 
		];
	}

	public function activate(ActivateContext $context) {
		$context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
		parent::activate($context);
	}

	public function deactivate(DeactivateContext $context) {
		$context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
		parent::deactivate($context);
	}

	public function update(UpdateContext $context) {
		$context->scheduleClearCache(InstallContext::CACHE_LIST_DEFAULT);
		parent::update($context);
	}

	public function onFrontendPostDispatch(\Enlight_Controller_ActionEventArgs $args) {
        $shop = false;
        if ($this->container->initialized('shop')) {
            $shop = $this->container->get('shop');
        }

        if (!$shop) {
            $shop = $this->container->get('models')->getRepository(Shop::class)->getActiveDefault();
        }

        $config = $this->container->get('shopware.plugin.cached_config_reader')->getByPluginName($this->getName(), $shop);
        if (!(bool) $config['nnwebSmartsuppLiveChat_active']) {
            return;
        }
		
		$controller = $args->get('subject');
		$view = $controller->View();
		
		$view->assign('smartsuppKey', $config["nnwebSmartsuppLiveChat_key"]);
		
		$this->container->get('template')->addTemplateDir($this->getPath() . '/Resources/views/');
		
		// User Status
		$userLoggedIn = Shopware()->Modules()->Admin()->sCheckUser();
		
		if ($userLoggedIn) {
			$userData = Shopware()->Modules()->Admin()->sGetUserData();
			
			$view->assign('smartsuppUserId', $userData["additional"]["user"]["id"]);
			$view->assign('smartsuppCustomerNumber', $userData["additional"]["user"]["customernumber"]);
			$view->assign('smartsuppCustomerGroup', $userData["additional"]["user"]["customergroup"]);
			$view->assign('smartsuppName', $userData["additional"]["user"]["firstname"] . " " . $userData["additional"]["user"]["lastname"]);
			$view->assign('smartsuppEmail', $userData["additional"]["user"]["email"]);
		} else {
			$view->assign('smartsuppUserId', "-");
			$view->assign('smartsuppCustomerNumber', "-");
			$view->assign('smartsuppCustomerGroup', "Gast");
			$view->assign('smartsuppName', "");
			$view->assign('smartsuppEmail', "");
		}
		
		$amount = Shopware()->Modules()->Basket()->sGetAmount();
		$amount = empty($amount) ? 0 : $amount["totalAmount"];
		
		$view->assign('smartsuppBasketAmount', $amount);
	}

	private function getInvalidateCache() {
		return [
			'frontend' 
		];
	}
}
