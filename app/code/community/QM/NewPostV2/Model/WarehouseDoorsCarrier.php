<?php

class QM_NewPostV2_Model_WarehouseDoorsCarrier extends QM_NewPostV2_Model_AbstractCarrier
{
    public function __construct()
    {
        $api = Mage::helper('qm_newpostv2/api');
        $this->_carrierCode = $api::WAREHOUSE_DOORS_RATE_CODE;
        $this->_apiServiceType = $api::WAREHOUSE_DOORS_SERVICE_TYPE;
        parent::__construct();
    }

    public function getFormBlock()
    {
        return 'qm_newpostv2/checkout_onepage_optionsWarehouseDoors';
    }
}
