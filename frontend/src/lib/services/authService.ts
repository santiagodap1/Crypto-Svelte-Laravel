import api from './api';
import type { User } from '../stores/auth';

type Credentials = { email: string; password: string };
type Registration = Credentials & { name: string; password_confirmation: string };

type AuthResponse = {
	access_token: string;
	token_type: string;
	user: User;
};

export async function login(credentials: Credentials): Promise<AuthResponse> {
	const { data } = await api.post<AuthResponse>('/login', credentials);
	return data;
}

export async function register(payload: Registration): Promise<AuthResponse> {
	const { data } = await api.post<AuthResponse>('/register', payload);
	return data;
}

export async function logout(): Promise<void> {
	await api.post('/logout');
}
