import {Api, ApiResponse, FetchOptions} from "@/services/api";
import {delay} from "@/helpers";
import {container} from "tsyringe";

export class MockApi extends Api {
    fetch<T extends ApiResponse | ArrayBuffer>(endpoint: string, options: FetchOptions = {}): Promise<T> {
        const realApi = container.resolve<Api>('realApi');

        endpoint = this.normalizeEndpoint(endpoint);

        switch (endpoint) {
            case "/wallet/save-wallet":
                // @ts-ignore
                return delay(300).then(() => {
                    return {
                        isSuccess: true,
                    }
                });

            default:
                return realApi.fetch(endpoint, options);
        }
    }
}
