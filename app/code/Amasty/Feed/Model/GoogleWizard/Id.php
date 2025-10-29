<?php

namespace Amasty\Feed\Model\GoogleWizard;

use Amasty\Feed\Model\Export\Product as ExportProduct;

/**
 * Class Id
 */
class Id extends Element
{
    protected $type = 'attribute';

    protected $tag = 'g:id';

    protected $limit = 50;

    protected $modify = 'html_escape';

    protected $value = ExportProduct::PREFIX_BASIC_ATTRIBUTE . '|sku';

    protected $required = true;

    protected $name = 'id';

    protected $description = 'An identifier of the item';
}
