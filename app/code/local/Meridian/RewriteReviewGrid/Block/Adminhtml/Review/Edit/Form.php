<?php
class Meridian_RewriteReviewGrid_Block_Adminhtml_Review_Edit_Form extends Mage_Adminhtml_Block_Review_Edit_Form
{
    protected function _prepareForm()
    {
        $review = Mage::registry('review_data');
        $product = Mage::getModel('catalog/product')->load($review->getEntityPkValue());
        $customer = Mage::getModel('customer/customer')->load($review->getCustomerId());

        $form = new Varien_Data_Form(array(
            'id'        => 'edit_form',
            'action'    => $this->getUrl('*/*/save', array('id' => $this->getRequest()->getParam('id'), 'ret' => Mage::registry('ret'))),
            'method'    => 'post'
        ));

        $fieldset = $form->addFieldset('review_details', array('legend' => Mage::helper('review')->__('Review Details'), 'class' => 'fieldset-wide'));

        $fieldset->addField('product_name', 'note', array(
            'label'     => Mage::helper('review')->__('Product'),
            'text'      => '<a href="' . $this->getUrl('*/catalog_product/edit', array('id' => $product->getId())) . '" onclick="this.target=\'blank\'">' . $product->getName() . '</a>'
        ));

        if ($customer->getId()) {
            $customerText = Mage::helper('review')->__('<a href="%1$s" onclick="this.target=\'blank\'">%2$s</a> <a href="mailto:%3$s">(%3$s)</a>', $this->getUrl('*/customer/edit', array('id' => $customer->getId(), 'active_tab' => 'review')), $this->escapeHtml($customer->getName()), $this->escapeHtml($customer->getEmail()));
        } else {
            if (is_null($review->getCustomerId())) {
                $customerText = Mage::helper('review')->__('Guest');
            } elseif ($review->getCustomerId() == 0) {
                $customerText = Mage::helper('review')->__('Administrator');
            }
        }

        $fieldset->addField('customer', 'note', array(
            'label'     => Mage::helper('review')->__('Posted By'),
            'text'      => $customerText,
        ));
        $fieldset->addField('created_at', 'date', array(
            'name'               => 'created_at',
            'label'              => Mage::helper('rewritereviewgrid')->__('Date'),
            'image'              => $this->getSkinUrl('images/grid-cal.gif'),
            'format'             => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT) ,
            'value'              => date( Mage::app()->getLocale()->getDateStrFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                strtotime('next weekday') )
        ));
        $fieldset->addField('summary_rating', 'note', array(
            'label'     => Mage::helper('review')->__('Summary Rating'),
            'text'      => $this->getLayout()->createBlock('adminhtml/review_rating_summary')->toHtml(),
        ));

        $fieldset->addField('detailed_rating', 'note', array(
            'label'     => Mage::helper('review')->__('Detailed Rating'),
            'required'  => true,
            'text'      => '<div id="rating_detail">'
                . $this->getLayout()->createBlock('adminhtml/review_rating_detailed')->toHtml()
                . '</div>',
        ));

        $fieldset->addField('status_id', 'select', array(
            'label'     => Mage::helper('review')->__('Status'),
            'required'  => true,
            'name'      => 'status_id',
            'values'    => Mage::helper('review')->getReviewStatusesOptionArray(),
        ));

        /**
         * Check is single store mode
         */
        if (!Mage::app()->isSingleStoreMode()) {
            $field = $fieldset->addField('select_stores', 'multiselect', array(
                'label'     => Mage::helper('review')->__('Visible In'),
                'required'  => true,
                'name'      => 'stores[]',
                'values'    => Mage::getSingleton('adminhtml/system_store')->getStoreValuesForForm(),
            ));
            $renderer = $this->getLayout()->createBlock('adminhtml/store_switcher_form_renderer_fieldset_element');
            $field->setRenderer($renderer);
            $review->setSelectStores($review->getStores());
        }
        else {
            $fieldset->addField('select_stores', 'hidden', array(
                'name'      => 'stores[]',
                'value'     => Mage::app()->getStore(true)->getId()
            ));
            $review->setSelectStores(Mage::app()->getStore(true)->getId());
        }

        $fieldset->addField('nickname', 'text', array(
            'label'     => Mage::helper('review')->__('Nickname'),
            'required'  => true,
            'name'      => 'nickname'
        ));

        $fieldset->addField('title', 'text', array(
            'label'     => Mage::helper('review')->__('Summary of Review'),
            'required'  => true,
            'name'      => 'title',
        ));

        $fieldset->addField('detail', 'textarea', array(
            'label'     => Mage::helper('review')->__('Review'),
            'required'  => true,
            'name'      => 'detail',
            'style'     => 'height:24em;',
        ));

        $form->setUseContainer(true);
        $form->setValues($review->getData());
        $this->setForm($form);
        return Mage_Adminhtml_Block_Widget_Form::_prepareForm();
    }
}
			