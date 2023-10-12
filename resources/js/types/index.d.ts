export interface User {
    id: number;
    name: string;
    is_admin: boolean;
    email: string;
    email_verified_at: string;
}

export interface TicketTypes {
    id: number;
    type: string;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    ticketTypesPaginated?: {
        data: TicketTypes[];
        links: {
            active: boolean;
            label: string;
            url: string;
        }[];
    };
    ticketTypes?: TicketTypes[];
};
