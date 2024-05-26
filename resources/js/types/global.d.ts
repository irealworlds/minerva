import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';

declare global {
  interface Window {
    axios: AxiosInstance;
  }

  // noinspection ES6ConvertVarToLetConst
  var route: typeof ziggyRoute; // eslint-disable-line no-var
}
