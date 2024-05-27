import { createContext, PropsWithChildren } from 'react';
import { combineClassNames } from '@/utils/combine-class-names.function';

export const ButtonRadioInputContext = createContext<InputContext>({
  value: undefined,
  setSelectedValue: () => {
    // Do nothing
  },
});

interface InputContext {
  value: unknown;
  setSelectedValue: (value: unknown) => void;
  disabled?: boolean;
}

export default function ButtonRadioInput<TValue = unknown>({
  value,
  children,
  onChange,
  disabled,
}: PropsWithChildren<{
  value: TValue;
  onChange: InputContext['setSelectedValue'];
  disabled?: boolean;
}>) {
  const initialContext: InputContext = {
    value,
    disabled: disabled,
    setSelectedValue: onChange,
  };
  return (
    <>
      <ButtonRadioInputContext.Provider value={initialContext}>
        <div
          className={combineClassNames(
            'inline-block rounded-md overflow-hidden border border-gray-900 dark:border-gray-200 divide-x divide-gray-900 dark:divide-gray-200',
            disabled ? 'opacity-25 cursor-not-allowed' : ''
          )}>
          {children}
        </div>
      </ButtonRadioInputContext.Provider>
    </>
  );
}
