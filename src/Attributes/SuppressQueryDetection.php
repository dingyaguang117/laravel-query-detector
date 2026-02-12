<?php

namespace BeyondCode\QueryDetector\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD | Attribute::TARGET_FUNCTION)]
class SuppressQueryDetection
{
}
