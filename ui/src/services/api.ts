import {injectable} from "tsyringe";

export enum HttpMethod {
    GET = "GET",
    POST = "POST",
    PUT = "PUT",
    PATCH = "PATCH",
    DELETE = "DELETE",
}

export interface ApiResponse {
    isSuccess: boolean,
    message: string | null,
    data?: {},
}

export interface FetchOptions {
    method?: HttpMethod,
    headers?: Map<string, string>,
    data?: string | {},
}

@injectable()
export class Api {
    constructor(private readonly baseUri: string) {
    }

    public fetch<T extends ApiResponse | ArrayBuffer>(endpoint: string, options: FetchOptions = {}): Promise<T>
    {
        const headers = Object.assign(
            {},
            {
                "Content-Type": "application/json",
                "Accept": "application/json",
            },
            options.headers ?? {},
        );

        return fetch(
            `${this.baseUri}${this.normalizeEndpoint(endpoint)}`,
            {
                method: options.method ?? HttpMethod.GET,
                mode: "cors",
                cache: "no-cache",
                headers,
                body: typeof options.data === 'string'
                    ? options.data
                    : JSON.stringify(options.data),
            }
        )
            // @ts-ignore
            .then((response) => {
                if (response.headers.has("Content-Type") && response.headers.get("Content-Type").startsWith("application/json")) {
                    return response.json().then(j => this.transformJson(j));
                }

                return response.arrayBuffer();
            })
    }

    protected transformJson(data): ApiResponse
    {
        return {
            isSuccess: data.is_success ?? false,
            message: data.message ?? null,
            data: data.data,
        };
    }

    protected normalizeEndpoint(endpoint: string): string
    {
        if (!endpoint.startsWith('/')) {
            endpoint = `/${endpoint}`;
        }

        if (endpoint.endsWith('/')) {
            endpoint = endpoint.slice(0, -1);
        }

        return endpoint;
    }
}
