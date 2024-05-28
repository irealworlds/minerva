import { ButtonHTMLAttributes } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';

export default function DangerButton({
    className = '',
    disabled,
    children,
    ...props
}: ButtonHTMLAttributes<HTMLButtonElement>) {
    return (
        <button
            {...props}
            className={combineClassNames(
                `inline-flex items-center hover:bg-red-500 px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150`,
                disabled ? 'opacity-25 pointer-events-none select-none' : '',
                className
            )}
            disabled={disabled}>
            {children}
        </button>
    );
}
