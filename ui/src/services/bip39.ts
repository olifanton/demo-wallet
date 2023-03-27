import words from "./data/bip39-english";

export const isValidWord = (word: string) => words.includes(word);
