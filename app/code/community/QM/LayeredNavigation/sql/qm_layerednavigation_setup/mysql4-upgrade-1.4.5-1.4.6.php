<?php
$this->startSetup();

$this->run("
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `seo_noindex`  TINYINT(1) NOT NULL;
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `seo_nofollow` TINYINT(1) NOT NULL;
    ALTER TABLE `{$this->getTable('qm_layerednavigation/filter')}` ADD `seo_rel`      TINYINT(1) NOT NULL;
"); 

$this->endSetup();