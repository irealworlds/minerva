import { InstitutionViewModel } from '@/types/view-models/institution.view-model';
import { EducatorSuggestionViewModel } from '@/types/view-models/educator-suggestion.view-model';
import React from 'react';
import PrimaryButton from '@/Components/Buttons/PrimaryButton';
import { PaperAirplaneIcon } from '@heroicons/react/24/outline';
import EducatorSuggestions from '@/Pages/Admin/Institutions/Partials/Manage/Educators/EducatorSuggestions';

interface NoEducatorsProps {
    institution: InstitutionViewModel;
    suggestions?: EducatorSuggestionViewModel[];
    openInvitationCreation: () => void;
}

export default function NoEducators({
    suggestions,
    openInvitationCreation,
}: NoEducatorsProps) {
    return (
        <div className="mx-auto max-w-lg">
            <div>
                <div className="text-center">
                    <svg
                        className="mx-auto h-12 w-12 text-gray-400"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 48 48"
                        aria-hidden="true">
                        <path
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth={2}
                            d="M34 40h10v-4a6 6 0 00-10.712-3.714M34 40H14m20 0v-4a9.971 9.971 0 00-.712-3.714M14 40H4v-4a6 6 0 0110.713-3.714M14 40v-4c0-1.313.253-2.566.713-3.714m0 0A10.003 10.003 0 0124 26c4.21 0 7.813 2.602 9.288 6.286M30 14a6 6 0 11-12 0 6 6 0 0112 0zm12 6a4 4 0 11-8 0 4 4 0 018 0zm-28 0a4 4 0 11-8 0 4 4 0 018 0z"
                        />
                    </svg>
                    <h2 className="mt-2 text-base font-semibold leading-6 text-gray-900">
                        Register educators
                    </h2>
                    <p className="mt-1 text-sm text-gray-500">
                        No educators have been registered for this institution
                        yet. You can add educators to your institution by
                        inviting them to associate themselves as teaching there.
                    </p>
                    <PrimaryButton
                        type="submit"
                        className="mt-6"
                        onClick={() => {
                            openInvitationCreation();
                        }}>
                        Invite educator
                        <PaperAirplaneIcon className="size-4 ml-2" />
                    </PrimaryButton>
                </div>
            </div>
            {!!suggestions?.length && (
                <EducatorSuggestions suggestions={suggestions} />
            )}
        </div>
    );
}
