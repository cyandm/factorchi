<?php

if (!defined('ABSPATH')) {
    exit;
}

// Class aliases for ported Factori view templates.
class_alias(Factorchi_Shop::class, 'FCI_Shop');
class_alias(Factorchi_Customer_Data::class, 'FCI_Customer_Detail');
class_alias(Factorchi_Products_Table::class, 'FCI_Products_Table');
class_alias(Factorchi_Total_Table::class, 'FCI_Total_Table');
class_alias(Factorchi_Labels::class, 'FCI_Labels');
class_alias(Factorchi_View_Render::class, 'FCI_View_Render');
class_alias(Factorchi_Date_Convert::class, 'FCI_Date_Convert');
class_alias(Factorchi_Helper::class, 'FCI_Helper');
class_alias(Factorchi_Orders_Table::class, 'FCI_Orders_Table');
class_alias(Factorchi_Order_Detail::class, 'FCI_Order_Detail');
