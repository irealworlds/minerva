export function clearFileInput(control: HTMLInputElement) {
    try {
        control.value = '';
    } catch(ex) { }

    if (control.value) {
        control.parentNode?.replaceChild(control.cloneNode(true), control);
    }
}
