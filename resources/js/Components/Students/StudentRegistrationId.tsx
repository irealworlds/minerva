import React, { PropsWithChildren } from 'react';
import { toast } from 'react-toastify';
import { AppToastOptions } from '@/Root';

interface StudentRegistrationIdProps extends PropsWithChildren {
    className?: string;
}

export default function StudentRegistrationId({
    children,
    className,
}: StudentRegistrationIdProps) {
    function copyToClipboard(): void {
        const id = children?.toString();

        if (!id) return;

        navigator.clipboard.writeText(id).then(
            () => {
                toast.info(
                    'Student registration id copied to clipboard.',
                    AppToastOptions
                );
            },
            () => {
                toast.error(
                    'Failed to copy student registration id to clipboard.',
                    AppToastOptions
                );
            }
        );
    }

    return (
        <button
            className={className}
            type="button"
            onClick={() => {
                copyToClipboard();
            }}>
            <code>{children}</code>
        </button>
    );
}
