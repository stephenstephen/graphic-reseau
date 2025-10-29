<?php

namespace Amasty\Feed\Model\GoogleWizard;

use Amasty\Feed\Model\Export\Product as ExportProduct;

/**
 * Class Title
 */
class Title extends Element
{
    protected $type = 'attribute';

    protected $tag = 'title';

    protected $limit = 150;

    protected $modify = 'html_escape';

    protected $value = ExportProduct::PREFIX_PRODUCT_ATTRIBUTE . '|name';

    protected $required = true;

    protected $name = 'title';

    protected $description = 'Title of the item';

    public function getModify()
    {
        $modify = $this->modify;
        if ($this->limit) {
            $modify .= '|length:' . $this->limit;
        }

        return $modify;
    }
}
