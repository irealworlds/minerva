<?php

declare(strict_types=1);

return [
    'login.success' => 'You have signed into your account.',
    'logout.success' => 'You have signed out of your account.',
    'institutions' => [
        'created' => 'Institution created successfully.',
        'updated' => 'Institution details updated.',
        'deleted' => 'Institution removed from the system.',
        'discipline_associated' =>
            'Discipline added to this institution\'s offer.',
        'discipline_removed' => 'Discipline removed from the institution.',
    ],
    'studentGroups' => [
        'created' => 'Student group created successfully.',
        'updated' => 'Student group details updated.',
        'deleted' => 'Student group removed from the system.',
        'cannot_delete' => 'Cannot delete this student group.',
        'discipline_associated' =>
            'Discipline added to this student group\'s curriculum.',
        'discipline_removed' => 'Discipline removed from the student group.',
    ],
    'disciplines' => [
        'created' => 'Discipline created successfully.',
        'updated' => 'Discipline details updated.',
        'deleted' => 'Discipline removed from the system.',
        'cannot_suggest_institution' =>
            'Cannot automatically determine institution for this discipline. Select associations manually.',
    ],
];
