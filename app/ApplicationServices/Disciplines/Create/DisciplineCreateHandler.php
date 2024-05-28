<?php

declare(strict_types=1);

namespace App\ApplicationServices\Disciplines\Create;

use App\Core\Contracts\Cqrs\ICommandHandler;
use App\Core\Models\{Discipline, InstitutionDiscipline};
use Illuminate\Database\ConnectionResolverInterface;
use Throwable;

/**
 * @implements ICommandHandler<DisciplineCreateCommand>
 */
final readonly class DisciplineCreateHandler implements ICommandHandler
{
    public function __construct(private ConnectionResolverInterface $database)
    {
    }

    /**
     * @inheritDoc
     * @throws Throwable
     */
    public function __invoke(mixed $command): void
    {
        $this->database
            ->connection()
            ->transaction(static function () use ($command): void {
                /** @var DisciplineCreateCommand $command */

                /** @var Discipline $discipline */
                $discipline = Discipline::query()->make();
                $discipline->name = $command->name;
                $discipline->abbreviation = $command->abbreviation;
                $discipline->saveOrFail();

                foreach ($command->associatedInstitutions as $institution) {
                    /** @var InstitutionDiscipline $pivot */
                    $pivot = InstitutionDiscipline::query()->make();
                    $pivot->institution_id = $institution->getKey();
                    $pivot->discipline_id = $discipline->getKey();
                    $pivot->saveOrFail();
                }
            });
    }
}
