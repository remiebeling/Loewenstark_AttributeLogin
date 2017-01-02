<?php

class Loewenstark_AttributeLogin_Model_Observer 
{

    /**
     * Customers May login with customer number OR email
     */
    public function loginCustomer()
    {
        if (Mage::getStoreConfigFlag('attributelogin/settings/active'))
        {
            $obj = new Varien_Object();
            $obj->setValid(true);
            Mage::dispatchEvent('attribute_login_before', array(
                'object' => $obj,
                'model'  => $this,
            ));
            $loginData = Mage::app()->getRequest()->getParam('login');
            if (isset($loginData['username']) && $obj->getValid())
            {
                $attribute = Mage::getStoreConfig('attributelogin/settings/attribute_code');
                if ($attribute != "")
                {
                    $value = trim($loginData['username']);
                    $customer = Mage::getModel('customer/customer')
                            ->getCollection()
                            ->addAttributeToSelect(array('email'))
                            ->addAttributeToFilter($attribute, $value)
                            ->setCurPage(1)
                            ->setPageSize(1)
                            ->getFirstItem()
                    ;
                    /* @var $customer Mage_Customer_Model_Customer */
                    if ($customer && $customer->getId())
                    {
                        $loginData['username'] = $customer->getEmail();
                        Mage::app()->getRequest()->setParam('login_attribute', $value);
                        Mage::app()->getRequest()->setParam('login', $loginData);
                        Mage::app()->getRequest()->setPost('login', $loginData);
                    }
                }
            }
        }
    }
    
    /**
     * Prevent login Form from displaying E-Mail Adress when login fails.
     * to do So, Block Customer_Form_Login is also rewritten.
     */
    public function afterLoginTry()
    {
        $params = Mage::app()->getRequest()->getParams();
        if(isset($params['login_attribute']) && $params['login_attribute'] != "" && Mage::getModel('customer/session')->getMessages()->count() > 0)
        {
            Mage::getModel('core/session')->setAltUsername($params['login_attribute']);
        }
    }
            

}
