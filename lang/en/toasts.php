<?php

declare(strict_types=1);

return [
    'login.success' => 'You have signed into your account.',
    'logout.success' => 'You have signed out of your account.',
    'identity' => [
        'password' => [
            'created' => 'Password created successfully.',
        ],
    ],
    'profile' => [
        'updated' => 'Changes saved successfully.',
    ],
    'institutions' => [
        'created' => 'Institution created successfully.',
        'updated' => 'Institution details updated.',
        'deleted' => 'Institution removed from the system.',
        'discipline_associated' =>
            'Discipline added to this institution\'s offer.',
        'discipline_removed' => 'Discipline removed from the institution.',

        'educators' => [
            'roles' => [
                'created' => 'Role :role added to educator.',
                'deleted' => 'Role :role removed from educator.',
            ],
            'created' => 'Educator added to institution.',
            'deleted' => 'Educator removed from institution.',
        ],
    ],
    'studentEnrolments' => [
        'cannot_suggest_institution' =>
            'Cannot automatically determine institution for this discipline. Select associations manually.',
        'created' => 'Student enroled successfully.',
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
    'educatorInvitations' => [
        'created' => 'Invitation sent successfully.',
        'deleted' => 'Invitation revoked successfully.',
        'response_failed' =>
            'Failed to respond to the invitation. Please try again!',
        'accepted' => 'Invitation accepted successfully.',
        'declined' => 'Invitation declined successfully.',
    ],
    'educators' => [
        'created' => 'Educator created successfully.',
        'disciplines' => [
            'created' => 'Educator added to discipline.',
            'removed' => 'Educator removed from discipline.',
        ],
    ],
    'grades' => [
        'created' => 'Grade created successfully.',
    ],
];
