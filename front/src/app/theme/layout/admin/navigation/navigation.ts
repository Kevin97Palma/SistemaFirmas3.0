import { Injectable } from '@angular/core';

export interface NavigationItem {
  id: string;
  title: string;
  type: 'item' | 'collapse' | 'group';
  translate?: string;
  icon?: string;
  hidden?: boolean;
  url?: string;
  classes?: string;
  exactMatch?: boolean;
  external?: boolean;
  target?: boolean;
  breadcrumbs?: boolean;
  function?: any;
  badge?: {
    title?: string;
    type?: string;
  };
  children?: Navigation[];
}

export interface Navigation extends NavigationItem {
  children?: NavigationItem[];
}

const NavigationItems = [
  {
    id: 'navigation',
    title: 'Navigation',
    type: 'group',
    icon: 'icon-navigation',
    children: [
      {
        id: 'dashboard',
        title: 'Home',
        type: 'item',
        url: '/dashboard',
        icon: 'feather icon-home',
        classes: 'nav-item',
      },
    ],
  },
  {
    id: 'Firmas',
    title: 'Firmas Electronicas',
    type: 'group',
    icon: 'icon-ui',
    children: [
      {
        id: 'Gestion',
        title: 'Gestion',
        type: 'collapse',
        icon: 'feather icon-box',
        children: [
          {
            id: 'Tramitacion',
            title: 'Panel Tramitacion',
            type: 'item',
            url: '/sample-page',
          },
          {
            id: 'Reporteria',
            title: 'Reporteria',
            type: 'item',
            url: '/basic/typography',
          },
        ],
      },
    ],
  },
  {
    id: 'Administrativo',
    title: 'Administrativo',
    type: 'group',
    icon: 'icon-pages',
    children: [
      {
        id: 'Cerrar',
        title: 'Salir',
        type: 'item',
        url: 'javascript:',
        classes: 'nav-item',
        icon: 'feather icon-power',
        external: true,
      },
    ],
  },
];

@Injectable()
export class NavigationItem {
  get() {
    return NavigationItems;
  }
}
