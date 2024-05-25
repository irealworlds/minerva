export function clearFileInput(control: HTMLInputElement) {
  try {
    control.value = '';
  } catch (ignored) {
    // Do nothing
  }

  if (control.value) {
    control.parentNode?.replaceChild(control.cloneNode(true), control);
  }
}
