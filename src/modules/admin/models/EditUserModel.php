<?php

namespace App\modules\admin\models;

use App\core\Attributes\Validator\Phone;
use App\core\Attributes\Validator\Required;
use App\core\Model;

class EditUserModel extends Model
{
    #[Required()]
    public ?int $id = null;

    #[Required()]
    public ?string $firstname = null;

    #[Required()]
    public ?string $lastname = null;

    #[Required()]
    #[Phone()]
    public ?string $phone = null;

    public ?string $password = null;

    #[Required()]
    public ?bool $isDeleted = null;

    public array $roles = [];
}
