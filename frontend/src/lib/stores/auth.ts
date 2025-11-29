import { derived, writable } from 'svelte/store';
import { browser } from '$app/environment';

export interface User {
	id: number;
	name: string;
	email: string;
}

export interface AuthState {
	user: User | null;
	token: string | null;
	isAuthenticated: boolean;
}

const initialState: AuthState = {
	user: null,
	token: null,
	isAuthenticated: false
};

const storageKey = 'auth';
const storedAuth = browser ? localStorage.getItem(storageKey) : null;
const initial = storedAuth ? JSON.parse(storedAuth) : initialState;

export const auth = writable<AuthState>(initial);

export const authUser = derived(auth, ($auth) => $auth.user);
export const isAuthenticated = derived(auth, ($auth) => $auth.isAuthenticated);

export function setAuthState(user: User, token: string) {
	auth.set({ user, token, isAuthenticated: true });
}

export function clearAuth() {
	auth.set(initialState);
}

if (browser) {
	auth.subscribe((value) => {
		localStorage.setItem(storageKey, JSON.stringify(value));
	});
}
