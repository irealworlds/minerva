export function combineClassNames(
  ...classNames: (string | null | undefined)[]
) {
  return classNames.filter(Boolean).join(' ');
}
