<script lang="ts">
	import { goto } from '$app/navigation';
	import { login } from '$lib/services/authService';
	import { setAuthState } from '$lib/stores/auth';

	let email = '';
	let password = '';
	let error = '';
	let loading = false;

	async function handleLogin() {
		loading = true;
		error = '';
		try {
			const response = await login({ email, password });
			setAuthState(response.user, response.access_token);
			goto('/');
		} catch (e: any) {
			error = e.response?.data?.message || 'Login failed';
		} finally {
			loading = false;
		}
	}
</script>

<div class="flex justify-center items-center min-h-[80vh]">
	<div class="card p-8 w-full max-w-md">
		<h2 class="text-3xl font-bold mb-2">Welcome</h2>
		<p class="text-gray-400 mb-6">Log in with your account to sync your watchlist.</p>

		{#if error}
			<div
				class="bg-red-500/10 border border-red-500 text-red-500 p-3 rounded-lg mb-4 text-sm text-center"
			>
				{error}
			</div>
		{/if}

		<form on:submit|preventDefault={handleLogin} class="space-y-4">
			<div>
				<label for="email" class="block text-sm font-medium text-gray-400 mb-1">Email</label>
				<input
					type="email"
					id="email"
					bind:value={email}
					class="w-full bg-dark border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition"
					placeholder="you@example.com"
					required
				/>
			</div>

			<div>
				<label for="password" class="block text-sm font-medium text-gray-400 mb-1">Password</label>
				<input
					type="password"
					id="password"
					bind:value={password}
					class="w-full bg-dark border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition"
					placeholder="••••••••"
					required
				/>
			</div>

			<button
				type="submit"
				disabled={loading}
				class="w-full bg-gradient-to-r from-primary to-secondary text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition transform hover:scale-[1.02] disabled:opacity-50"
			>
				{loading ? 'Logging in...' : 'Log in'}
			</button>
		</form>

		<p class="mt-6 text-center text-gray-400 text-sm">
			Don't have an account yet?
			<a href="/register" class="text-primary hover:text-secondary transition">Create account</a>
		</p>
	</div>
</div>
