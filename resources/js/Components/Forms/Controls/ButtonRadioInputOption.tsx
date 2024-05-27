import { combineClassNames } from '@/utils/combine-class-names.function';
import { PropsWithChildren, useContext, useMemo } from 'react';
import { ButtonRadioInputContext } from '@/Components/Forms/Controls/ButtonRadioInput';

interface ButtonRadioInputOptionProps<T> extends PropsWithChildren {
  value: T;
}

export default function ButtonRadioInputOption<T>({
  value,
  children,
}: ButtonRadioInputOptionProps<T>) {
  const {
    value: selectedValue,
    setSelectedValue,
    disabled,
  } = useContext(ButtonRadioInputContext);

  const selected = useMemo(
    () => value === selectedValue,
    [value, selectedValue]
  );

  return (
    <>
      <button
        disabled={disabled}
        onClick={() => {
          setSelectedValue(value);
        }}
        type="button"
        className={combineClassNames(
          `inline-flex items-center px-4 py-2 font-semibold text-xs uppercase tracking-widest focus:outline-none transition ease-in-out duration-150`,
          selected
            ? 'bg-gray-800 dark:bg-gray-200 hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 text-white dark:text-gray-800'
            : 'bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300'
        )}>
        {children}
      </button>
    </>
  );
}
