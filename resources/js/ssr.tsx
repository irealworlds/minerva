import ReactDOMServer from 'react-dom/server';
import { createInertiaApp } from '@inertiajs/react';
import createServer from '@inertiajs/react/server';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { route, RouteName, RouteParams } from 'ziggy-js';
import { PageProps } from '@/types';

const appName = (import.meta.env.VITE_APP_NAME as string) || 'Laravel';

createServer(page =>
  createInertiaApp<PageProps>({
    page,
    render: ReactDOMServer.renderToString,
    title: title => `${title} - ${appName}`,
    resolve: name =>
      resolvePageComponent(
        `./Pages/${name}.tsx`,
        import.meta.glob('./Pages/**/*.tsx')
      ),
    setup: ({ App, props }) => {
      global.route<RouteName> = (name, params, absolute) =>
        route(name, params as RouteParams<RouteName> | undefined, absolute, {
          ...props.initialPage.props.ziggy,
          location: new URL(props.initialPage.props.ziggy.location),
        });

      return <App {...props} />;
    },
  })
);
