import {
    forwardRef,
    InputHTMLAttributes,
    useEffect,
    useImperativeHandle,
    useRef,
} from 'react';
import { XMarkIcon } from '@heroicons/react/24/outline';
import { combineClassNames } from '@/utils/combine-class-names.function';

export default forwardRef(function TextChipsInput(
    {
        type = 'text',
        className = '',
        isFocused = false,
        separator = ',',
        onChange = () => {
            // Do nothing
        },
        value = [],
        ...props
    }: Omit<
        InputHTMLAttributes<HTMLInputElement>,
        'onChange' | 'onChangeCapture' | 'string'
    > & {
        value?: string[];
        isFocused?: boolean;
        separator?: string;
        onChange?: (newValue: string[]) => void;
    },
    ref
) {
    const localRef = useRef<HTMLInputElement>(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    useEffect(() => {
        if (isFocused) {
            localRef.current?.focus();
        }
    }, []);

    const updateValue = (target: HTMLInputElement) => {
        const inputValue = target.value;
        if (inputValue.endsWith(separator)) {
            const newValue = inputValue.slice(0, -separator.length).trim();
            if (newValue.length) {
                onChange([...value, newValue]);
                target.value = '';
            }
        }
    };

    const removeChipIndex = (idx: number) => {
        onChange(value.filter((_, i) => i !== idx));
    };

    return (
        <div
            className={combineClassNames(
                'flex items-center flex-wrap gap-x-1 px-3 py-2 gap-y-1 border border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus-within:border-indigo-500 dark:focus-within:border-indigo-600 focus-within:ring-indigo-500 dark:focus-within:ring-indigo-600 rounded-md shadow-sm overflow-hidden',
                className
            )}>
            {value.map((chip, idx) => (
                <div
                    key={idx}
                    className="flex justify-between items-center h-8 leading-loose py-[5px] px-3 text-[13px] font-normal bg-zinc-100 dark:text-white dark:bg-neutral-700 rounded-2xl transition-[opacity] duration-300 ease-linear [word-wrap: break-word] shadow-none normal-case hover:!shadow-none inline-block font-medium leading-normal text-center no-underline align-middle select-none border-[.125rem] border-solid border-transparent py-1.5 text-xs rounded">
                    <span>{chip}</span>
                    <button
                        type="button"
                        onClick={() => {
                            removeChipIndex(idx);
                        }}
                        className="w-4 float-right cursor-pointer ps-1 text-[16px] dark:text-white/30 opacity-[.53] transition-all duration-200 ease-in-out hover:text-black/50 text-black/30 dark:hover:text-white/50 [&amp;>svg]:h-4 [&amp;>svg]:w-4">
                        <XMarkIcon className="size-4" />
                    </button>
                </div>
            ))}
            <input
                {...props}
                type={type}
                className="!border-0 !ring-0 !outline-0 !p-0 grow"
                onBlur={e => {
                    if (e.target.value.trim().length > 0) {
                        e.target.value += separator;
                        updateValue(e.target);
                    }
                }}
                onChange={e => {
                    updateValue(e.target);
                    e.preventDefault();
                }}
            />
        </div>
    );
});
