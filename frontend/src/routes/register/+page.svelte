<script lang="ts">
	import { goto } from '$app/navigation';
	import { register as registerUser } from '$lib/services/authService';
	import { setAuthState } from '$lib/stores/auth';

	let name = '';
	let email = '';
	let password = '';
	let password_confirmation = '';
	let error = '';
	let loading = false;

	async function handleRegister() {
		if (password !== password_confirmation) {
			error = 'Passwords do not match';
			return;
		}

		loading = true;
		error = '';

		try {
			const response = await registerUser({
				name,
				email,
				password,
				password_confirmation
			});
			setAuthState(response.user, response.access_token);
			goto('/');
		} catch (e: any) {
			error = e.response?.data?.message || 'Registration failed';
		} finally {
			loading = false;
		}
	}
</script>

<div class="flex justify-center items-center min-h-[80vh]">
	<div class="card p-8 w-full max-w-md">
		<h2 class="text-3xl font-bold mb-2">Create your account</h2>
		<p class="text-gray-400 mb-6">Save your watchlist and sync it with your profile.</p>

		{#if error}
			<div
				class="bg-red-500/10 border border-red-500 text-red-500 p-3 rounded-lg mb-4 text-sm text-center"
			>
				{error}
			</div>
		{/if}

		<form on:submit|preventDefault={handleRegister} class="space-y-4">
			<div>
				<label for="name" class="block text-sm font-medium text-gray-400 mb-1">Name</label>
				<input
					type="text"
					id="name"
					bind:value={name}
					class="w-full bg-dark border border-gray-700 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-primary focus:ring-1 focus:ring-primary transition"
					placeholder="John Doe"
					required
				/>
			</div>

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

			<div>
				<label for="password_confirmation" class="block text-sm font-medium text-gray-400 mb-1"
					>Confirm Password</label
				>
				<input
					type="password"
					id="password_confirmation"
					bind:value={password_confirmation}
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
				{loading ? 'Creating...' : 'Create account'}
			</button>
		</form>

		<p class="mt-6 text-center text-gray-400 text-sm">
			Already have an account?
			<a href="/login" class="text-primary hover:text-secondary transition">Log in</a>
		</p>
	</div>
</div>
