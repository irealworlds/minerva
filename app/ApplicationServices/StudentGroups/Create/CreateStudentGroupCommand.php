<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\Create;

use App\Core\Contracts\Cqrs\ICommand;
use Illuminate\Database\Eloquent\Model;

final readonly class CreateStudentGroupCommand implements ICommand
{
    public function __construct(
        public string $id,
        public string $name,
        public Model $parent,
    ) {
    }
}
