<?php

use App\Models\Card;
use Illuminate\Support\Facades\DB;

use function Livewire\Volt\{layout, state, computed, usesPagination};

usesPagination();

layout('layouts.app');

state([
    'style' => 'table',
])->url();

$cards = computed(function () {
    return auth()->user()->cards->groupBy("id")->map(function ($group) {
        $first = $group->first();
        $first->count = $group->count();
        return $first;
    });
});

$setStyle = fn ($style) => $this->style = $style;

$increase = fn ($cardId) => auth()->user()->cards()->attach($cardId);

$reduce = fn ($pivotId) => DB::table('card_user')->whereId($pivotId)->delete();

?>

<div class="py-12 px-8 space-y-8 max-w-7xl mx-auto">
    <span class="isolate inline-flex rounded-md shadow-sm">
        <button type="button" wire:click="setStyle('grid')" class="relative inline-flex items-center gap-x-1.5 rounded-l-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10">
            <svg class="-ml-0.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 0 1 6 3.75h2.25A2.25 2.25 0 0 1 10.5 6v2.25a2.25 2.25 0 0 1-2.25 2.25H6a2.25 2.25 0 0 1-2.25-2.25V6ZM3.75 15.75A2.25 2.25 0 0 1 6 13.5h2.25a2.25 2.25 0 0 1 2.25 2.25V18a2.25 2.25 0 0 1-2.25 2.25H6A2.25 2.25 0 0 1 3.75 18v-2.25ZM13.5 6a2.25 2.25 0 0 1 2.25-2.25H18A2.25 2.25 0 0 1 20.25 6v2.25A2.25 2.25 0 0 1 18 10.5h-2.25a2.25 2.25 0 0 1-2.25-2.25V6ZM13.5 15.75a2.25 2.25 0 0 1 2.25-2.25H18a2.25 2.25 0 0 1 2.25 2.25V18A2.25 2.25 0 0 1 18 20.25h-2.25A2.25 2.25 0 0 1 13.5 18v-2.25Z" />
            </svg>
        </button>
        <button type="button" wire:click="setStyle('table')" class="relative -ml-px inline-flex items-center rounded-r-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-10">
            <svg class="-ml-0.5 h-5 w-5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0 1 12 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
            </svg>
        </button>
    </span>

    @if ($style === 'grid')
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        @foreach ($this->cards as $card)
        <div id="{{ $card->external_id }}" wire:key="{{ $card->external_id }}" class="group relative">
            <img src="{{ $card->images['small'] }}" alt="{{ $card->name }} - {{ $card->flavor_text ?? '' }}" class="shadow rounded-xl">
            <div class="sr-only">
                <dl>
                    <dt>Name</dt>
                    <dd>{{ $card->name }}</dd>
                    <dt>HP</dt>
                    <dd>{{ $card->hp }}</dd>
                </dl>
            </div>
            <div class="absolute rounded-xl top-0 left-0 w-full h-full flex flex-col justify-center items-center bg-sky-700 opacity-0 group-hover:opacity-100 duration-500">
                <button wire:click="addToDex('{{ $card['id'] }}')" wire:loading.attr="disabled">Add<span wire:loading>ing</span></button>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <x-table>
        <x-slot:thead>
            <tr>
                <x-table.th>Name</x-table.th>
                <x-table.th>Count</x-table.th>
                <x-table.th>Supertype</x-table.th>
                <x-table.th>Subtype</x-table.th>
                <x-table.th>Type</x-table.th>
                <x-table.th>HP</x-table.th>
                <x-table.th>Abilities</x-table.th>
                <x-table.th>Attacks</x-table.th>
                <x-table.th>
                    <span class="sr-only">Edit</span>
                </x-table.th>
            </tr>
        </x-slot:thead>
        <x-slot:tbody>
            @foreach ($this->cards as $card)
            <tr>
                <x-table.td>{{ $card->name }}</x-table.td>
                <x-table.td>{{ $card->count }}</x-table.td>
                <x-table.td>{{ $card->supertype }}</x-table.td>
                <x-table.td>{{ $card->subtypes?->implode(', ') }}</x-table.td>
                <x-table.td>{{ $card->types?->implode(', ') }}</x-table.td>
                <x-table.td>{{ $card->hp ?? '-' }}</x-table.td>
                <x-table.td>{{ $card->abilities ?? '-' }}</x-table.td>
                <x-table.td>{{ $card->attacks?->implode('name', ', ') }}</x-table.td>
                <x-table.td>
                    <button type="button" wire:click="increase({{ $card->id }})">
                        Increase
                    </button>
                    <button type="button" wire:click="reduce({{ $card->pivot->id }})">
                        Reduce
                    </button>
                </x-table.td>
            </tr>
            @endforeach
        </x-slot:tbody>
    </x-table>
    @endif
</div>