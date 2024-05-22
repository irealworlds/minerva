export function combineClassNames(...classNames: Array<string|null|undefined>) {
    return classNames.filter(Boolean).join(" ");
}
