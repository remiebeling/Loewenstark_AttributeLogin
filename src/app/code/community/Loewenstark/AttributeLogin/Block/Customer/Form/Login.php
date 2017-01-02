<?php

class Loewenstark_AttributeLogin_Block_Customer_form_Login extends Mage_Customer_Block_Form_Login
{

    /**
     * Retrieve username for form field
     *
     * @return string
     */
    public function getUsername()
    {
        if (-1 === $this->_username || is_null($this->_username))
        {
            $this->_username = Mage::getSingleton('customer/session')->getUsername(true);
        }
        if (!is_null(Mage::getSingleton('core/session')->getAltUsername()) && Mage::getSingleton('core/session')->getAltUsername() != "")
        {
            $this->_username = Mage::getSingleton('core/session')->getAltUsername(true);
        }
        return $this->_username;
    }

}
