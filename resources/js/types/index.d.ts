import { Config } from 'ziggy-js';
import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: AuthenticatedUserViewModel;
  };
  ziggy: Config & { location: string };
};
