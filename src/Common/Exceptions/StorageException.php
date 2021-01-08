<?php

namespace AwemaPL\Storage\Common\Exceptions;
use AwemaPL\BaseJS\Exceptions\PublicException;

class StorageException extends PublicException
{
    const ERROR_CATEGORY_CONTAINS_PRODUCTS = 'ERROR_CATEGORY_CONTAINS_PRODUCTS';
    const ERROR_SAME_VALUES = 'ERROR_SAME_VALUES';
}
