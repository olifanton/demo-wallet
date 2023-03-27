import type {App} from "vue"

type ModifierMap = Map<string, boolean | string>;

const kebabize = (str): string => str.replace(/[A-Z]+(?![a-z])|[A-Z]/g, ($, ofs) => (ofs ? "-" : "") + $.toLowerCase());

const element = (blockName: string, elementName: string): string => `${blockName}__${kebabize(elementName)}`;

const iterateMods = (computedName: string, target: {}, mods?: ModifierMap): void => {
    if (mods) {
        Object.keys(mods).forEach((mod) => {
            const val = mod[mod];

            if (typeof val === "boolean") {
                target[`${computedName}_${mod}`] = val;
            } else if (typeof val == "string") {
                target[`${computedName}_${mod}-${val}`] = true;
            }
        });
    }
};

export default {
    install(app: App, options) {
        app.mixin({
            methods: {
                bem(elem?: false | string | ModifierMap,
                    modsOrMixin?: string | ModifierMap,
                    mods?: ModifierMap,
                ) {
                    const blockName = this.$options.name;

                    const cn = {};

                    if (elem === false && typeof modsOrMixin === "string") {
                        cn[modsOrMixin] = true;
                        iterateMods(blockName, cn, mods);
                    } else if (typeof elem === "string") {
                        const elemName = element(blockName, elem);

                        cn[elemName] = true;
                        iterateMods(elemName, modsOrMixin);
                    } else if (!elem && !modsOrMixin && !mods) {
                        cn[blockName] = true;
                    }

                    iterateMods(blockName, elem);

                    return cn;
                },
            },
        });
    }
};
