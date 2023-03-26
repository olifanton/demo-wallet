interface WltStoreMutation {
    storeId: string,
    events: {
        type: string,
        key: string,
        newValue: any,
        target: any,
    },
}
