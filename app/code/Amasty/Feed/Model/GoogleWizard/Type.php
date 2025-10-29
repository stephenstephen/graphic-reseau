<?php

namespace Amasty\Feed\Model\GoogleWizard;

use Amasty\Feed\Model\Export\Product as ExportProduct;

/**
 * Class Type
 */
class Type extends Element
{
    protected $type = 'attribute';

    protected $tag = 'g:product_type';

    protected $modify = 'html_escape';

    protected $value = ExportProduct::PREFIX_CATEGORY_ATTRIBUTE . '|category';

    protected $name = 'product type';

    protected $description = 'Your category of the item';
}
