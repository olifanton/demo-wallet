export const delay = (ms): Promise<void> => new Promise((resolve) => setTimeout(resolve, ms));
