export function combineClassNames(
    ...classNames: (string | null | boolean | undefined)[]
) {
    return classNames.filter(Boolean).join(' ');
}
