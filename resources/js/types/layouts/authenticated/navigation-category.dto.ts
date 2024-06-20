import { NavigationItem } from '@/types/layouts/authenticated/navigation-item.dto';

export class NavigationCategory {
    name: string;
    items: NavigationItem[];

    constructor(name: string, items: NavigationItem[]) {
        this.name = name;
        this.items = items;
    }
}
