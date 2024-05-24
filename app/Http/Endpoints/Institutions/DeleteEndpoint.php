<?php

namespace App\Http\Endpoints\Institutions;

use App\ApplicationServices\Institutions\Delete\DeleteInstitutionCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Enums\Permission;
use App\Core\Models\Institution;
use App\Http\Endpoints\Endpoint;
use Codestage\Authorization\Attributes\Authorize;
use Illuminate\Http\Response;
use Spatie\RouteAttributes\Attributes\Delete;

final class DeleteEndpoint extends Endpoint
{
    function __construct(
        private readonly ICommandBus $_commandBus
    ) {
    }

    #[Delete("/Institutions/{institution}", name: "api.institutions.delete")]
    #[Authorize(permissions: Permission::InstitutionDelete)]
    public function __invoke(Institution $institution): Response
    {
        $this->_commandBus->dispatch(new DeleteInstitutionCommand($institution));

        return new Response(status: 204);
    }
}
