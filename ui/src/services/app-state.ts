import {inject, injectable} from "tsyringe";
import {Api, ApiResponse, HttpMethod} from "@/services/api";

interface CurrentAppState {
    isInitialized: boolean,
}

@injectable()
export class AppState {
    constructor(
        @inject(Api) private readonly api: Api,
    ) {}

    public getState(): Promise<CurrentAppState>
    {
        return this
            .api
            .fetch<ApiResponse>('state')
            .then(resp => {
                if (resp.isSuccess) {
                    // @ts-ignore
                    return resp.data.is_initialized ?? false;
                }

                return Promise.reject(resp);
            });
    }
}
