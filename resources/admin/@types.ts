export type Category = {
    name?: string,
    contain: CategoryContainable,
    parent?: number,
    url?: string,
    includes?: number[],
    excludes?: number[],
    isDefault?: boolean,
}

export type CategoryContainable = "unlisted" | "children" | "selected";

export type Page = {
    ID: number,
    post_author: string,
    post_date: string,
    post_date_gmt: string,
    post_content: string,
    post_title: string,
    post_excerpt: string,
    post_status: string,
    comment_status: string,
    ping_status: string,
    post_password: string,
    post_name: string,
    to_ping: string,
    pinged: string,
    post_modified: string,
    post_modified_gmt: string,
    post_content_filtered: string,
    post_parent: number,
    guid: string,
    menu_order: number,
    post_type: number,
    post_mime_type: number,
    comment_count: string,
    filter: string
}

export type PluginAPIResponse = {
    options: Category[] | [],
    pages: Page[] | []
}