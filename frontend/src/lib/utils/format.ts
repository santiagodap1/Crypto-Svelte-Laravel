export function formatCurrency(value: number, digits = 2): string {
	return new Intl.NumberFormat('en-US', {
		style: 'currency',
		currency: 'USD',
		maximumFractionDigits: digits
	}).format(value ?? 0);
}

export function formatNumber(value: number): string {
	return new Intl.NumberFormat('en-US').format(value ?? 0);
}

export function formatPercent(value: number, digits = 2): string {
	const parsed = Number(value ?? 0);
	return `${parsed.toFixed(digits)}%`;
}

export function formatCompactCurrency(value: number): string {
	return new Intl.NumberFormat('en-US', {
		style: 'currency',
		currency: 'USD',
		notation: 'compact',
		maximumFractionDigits: 2
	}).format(value ?? 0);
}
