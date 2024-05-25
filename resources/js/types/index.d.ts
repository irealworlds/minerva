import { Config } from 'ziggy-js';
import { AuthenticatedUserViewModel } from '@/types/authenticated-user.model';

export type PageProps<
  T extends Record<string, unknown> = Record<string, unknown>,
> = T & {
  auth: {
    user: AuthenticatedUserViewModel | null;
  };
  ziggy: Config & { location: string };
};
