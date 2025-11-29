import axios from 'axios';
import { get } from 'svelte/store';
import { auth, clearAuth } from '../stores/auth';

const api = axios.create({
	baseURL: import.meta.env.VITE_API_BASE_URL ?? 'http://127.0.0.1:8001/api',
	headers: {
		'Content-Type': 'application/json',
		Accept: 'application/json'
	}
});

api.interceptors.request.use((config) => {
	const token = get(auth).token;
	if (token) {
		config.headers.Authorization = `Bearer ${token}`;
	}
	return config;
});

api.interceptors.response.use(
	(response) => response,
	(error) => {
		if (error?.response?.status === 401) {
			clearAuth();
		}
		return Promise.reject(error);
	}
);

export default api;
