import {Api, ApiResponse, FetchOptions} from "@/services/api";
import {delay} from "@/helpers";

export class MockApi extends Api {
    fetch<T extends ApiResponse | ArrayBuffer>(endpoint: string, options: FetchOptions = {}): Promise<T> {
        endpoint = this.normalizeEndpoint(endpoint);

        return delay(300)
            // @ts-ignore
            .then(() => {
                switch (endpoint) {
                    case "/state":
                        return {
                            isSuccess: true,
                            data: {
                                is_initialized: false,
                            }
                        };

                    case "/wallet/generate-words":
                        return {
                            isSuccess: true,
                            data: [
                                'bring',  'like',    'escape',
                                'health', 'chimney', 'pear',
                                'whale',  'peasant', 'drum',
                                'beach',  'mass',    'garden',
                                'riot',   'alien',   'possible',
                                'bus',    'shove',   'unable',
                                'jar',    'anxiety', 'click',
                                'salon',  'canoe',   'lion',
                            ],
                        };

                    case "/wallet/save-wallet":
                        return {
                            isSuccess: true,
                        };

                    default:
                        return Promise.reject({
                            isSuccess: false,
                            message: 'Not found',
                        });
                }
            });
    }
}
