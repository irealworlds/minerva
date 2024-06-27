<?php

declare(strict_types=1);

namespace App\Http\Web\Controllers\Admin\Educators;

use App\ApplicationServices\Educators\Create\CreateEducatorProfileCommand;
use App\Core\Contracts\Cqrs\ICommandBus;
use App\Core\Dtos\PersonalNameDto;
use App\Http\Web\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Spatie\RouteAttributes\Attributes\Post;
use Throwable;

final readonly class StoreEducatorController extends Controller
{
    public function __construct(
        private Redirector $_redirector,
        private ICommandBus $_commandBus,
    ) {
    }

    /**
     * @throws Throwable
     */
    #[Post('/Admin/Educators/Create', name: 'admin.educators.store')]
    public function __invoke(StoreEducatorRequest $request): RedirectResponse
    {
        $this->_commandBus->dispatch(
            new CreateEducatorProfileCommand(
                username: $request->idNumber,
                name: new PersonalNameDto(
                    prefix: empty($request->namePrefix)
                        ? null
                        : $request->namePrefix,
                    firstName: $request->firstName,
                    middleNames: $request->middleNames,
                    lastName: $request->lastName,
                    suffix: empty($request->nameSuffix)
                        ? null
                        : $request->nameSuffix,
                ),
                email: $request->email,
                password: $request->optionalString('password', false),
            ),
        );

        return $this->_redirector
            ->action(ListEducatorsController::class)
            ->with('success', [__('toasts.educators.created')]);
    }
}
