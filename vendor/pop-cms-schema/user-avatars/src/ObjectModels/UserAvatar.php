<?php

declare (strict_types=1);
namespace PoPCMSSchema\UserAvatars\ObjectModels;

class UserAvatar
{
    /**
     * @readonly
     * @var string|int
     */
    public $id;
    /**
     * @readonly
     * @var string
     */
    public $src;
    /**
     * @readonly
     * @var int
     */
    public $size;
    /**
     * @param string|int $id
     */
    public function __construct($id, string $src, int $size)
    {
        $this->id = $id;
        $this->src = $src;
        $this->size = $size;
    }
}
