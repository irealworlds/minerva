import { EducatorSuggestionViewModel } from '@/types/view-models/educator-suggestion.view-model';
import React from 'react';
import EducatorSuggestionEntry from '@/Pages/Admin/Institutions/Partials/Manage/Educators/EducatorSuggestionEntry';

interface EducatorSuggestionsProps {
    suggestions: EducatorSuggestionViewModel[];
}

export default function EducatorSuggestions({
    suggestions,
}: EducatorSuggestionsProps) {
    return (
        <div className="mt-10">
            <h3 className="text-sm font-medium text-gray-500">
                Suggested educators
            </h3>
            <ul
                role="list"
                className="mt-4 divide-y divide-gray-200 border-b border-t border-gray-200">
                {suggestions.map(suggestion => (
                    <EducatorSuggestionEntry
                        key={suggestion.id}
                        suggestion={suggestion}
                    />
                ))}
            </ul>
        </div>
    );
}
