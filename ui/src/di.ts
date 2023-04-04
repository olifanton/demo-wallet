// @ts-nocheck

import {container} from "tsyringe";
import * as env from 'env';

import {Api} from "@/services/api";
import {MockApi} from "@/services/mock-api";

container.register<Api>(Api, {
    useValue: new MockApi(env.UI_API_BASE_URI),
});

container.register<Api>('realApi', {
    useValue: new Api(env.UI_API_BASE_URI),
});
