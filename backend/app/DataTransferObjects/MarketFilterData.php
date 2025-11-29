<?php

namespace App\DataTransferObjects;

class MarketFilterData
{
    public function __construct(
        public readonly string $vsCurrency = 'usd',
        public readonly int $perPage = 20,
        public readonly int $page = 1,
        public readonly ?array $ids = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $rawIds = $data['ids'] ?? null;
        $ids = null;

        if ($rawIds) {
            $rawList = is_array($rawIds) ? $rawIds : explode(',', $rawIds);
            $ids = array_values(
                array_filter(
                    array_map(static fn ($id) => trim((string) $id), $rawList),
                    static fn ($id) => $id !== ''
                )
            );
        }

        return new self(
            vsCurrency: $data['vs_currency'] ?? 'usd',
            perPage: (int) ($data['per_page'] ?? 20),
            page: (int) ($data['page'] ?? 1),
            ids: $ids
        );
    }

    public function cacheKey(): string
    {
        $idsKey = $this->ids ? implode('-', $this->ids) : 'all';

        return "{$this->vsCurrency}_{$this->perPage}_{$this->page}_{$idsKey}";
    }

    public function toQueryParams(): array
    {
        return array_filter([
            'vs_currency' => $this->vsCurrency,
            'order' => 'market_cap_desc',
            'per_page' => $this->perPage,
            'page' => $this->page,
            'sparkline' => 'false',
            'price_change_percentage' => '24h',
            'ids' => $this->ids ? implode(',', $this->ids) : null,
        ], static fn ($value) => $value !== null);
    }
}
