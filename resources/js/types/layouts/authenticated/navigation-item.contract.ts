import React from "react";

export interface NavigationItem {
    name: string;
    href: string;
    icon: React.ForwardRefExoticComponent<Omit<React.SVGProps<SVGSVGElement>, "ref"> & {     title?: string | undefined, titleId?: string | undefined } & React.RefAttributes<SVGSVGElement>>;
    current: boolean;
}
