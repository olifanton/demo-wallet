export const delay = (ms: number): Promise<void> => new Promise((resolve) => setTimeout(resolve, ms));

export const copyToClipboard = (str: string): Promise<void> => navigator.clipboard.writeText(str);
