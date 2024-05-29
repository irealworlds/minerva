<?php

declare(strict_types=1);

namespace App\ApplicationServices\StudentGroups\AssociateDisciplines;

use App\Core\Contracts\Cqrs\ICommandHandler;
use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

/**
 * @implements ICommandHandler<AssociateDisciplinesToStudentGroupCommand>
 */
final readonly class AssociateDisciplinesToStudentGroupHandler implements
    ICommandHandler
{
    public function __construct(private ConnectionResolverInterface $_database)
    {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $group = $command->studentGroup;
        $disciplines = $command->disciplines;
        $this->_database
            ->connection()
            ->transaction(static function () use ($group, $disciplines): void {
                foreach ($disciplines as $discipline) {
                    $group->disciplines()->attach($discipline->getKey());
                }
            });
    }
}
